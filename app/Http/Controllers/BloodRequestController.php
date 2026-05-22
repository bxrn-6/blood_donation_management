<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonorMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodInventory;

class BloodRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $bloodRequests = BloodRequest::with('hospital')
                ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $bloodRequests = BloodRequest::where('hospital_id', $user->id)
                ->with('hospital')
                ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('blood-requests.index', compact('bloodRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $urgencyLevels = ['low', 'medium', 'high', 'critical'];
        
        return view('blood-requests.create', compact('bloodTypes', 'urgencyLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blood_type_needed' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_requested' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'notes' => 'nullable|string',
        ]);

        $bloodRequest = BloodRequest::create([
            'request_id' => BloodRequest::generateRequestId(),
            'hospital_id' => Auth::id(),
            'hospital_name' => Auth::user()->name,
            'blood_type_needed' => $request->blood_type_needed,
            'quantity_requested' => $request->quantity_requested,
            'urgency_level' => $request->urgency_level,
            'request_date' => now()->toDateString(),
            'notes' => $request->notes,
        ]);

        // Auto-match donors
        $this->autoMatchDonors($bloodRequest);

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('success', 'Blood request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bloodRequest = BloodRequest::with(['hospital', 'donorMatches.donor'])->findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $bloodRequest->hospital_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('blood-requests.show', compact('bloodRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $bloodRequest->hospital_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($bloodRequest->status !== 'Pending') {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'Cannot edit a request that is already processed.');
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $urgencyLevels = ['low', 'medium', 'high', 'critical'];
        
        return view('blood-requests.edit', compact('bloodRequest', 'bloodTypes', 'urgencyLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $bloodRequest->hospital_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($bloodRequest->status !== 'Pending') {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'Cannot update a request that is already processed.');
        }

        $request->validate([
            'blood_type_needed' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_requested' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'notes' => 'nullable|string',
        ]);

        $bloodRequest->update($request->all());

        // Re-match donors after update
        DonorMatch::where('blood_request_id', $bloodRequest->id)->delete();
        $this->autoMatchDonors($bloodRequest);

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('success', 'Blood request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $bloodRequest->hospital_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($bloodRequest->status !== 'Pending') {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'Cannot delete a request that is already processed.');
        }

        $bloodRequest->delete();

        return redirect()->route('blood-requests.index')
            ->with('success', 'Blood request deleted successfully.');
    }

    /**
     * Approve a blood request (admin only)
     */
    public function approve(string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

// Ensure the user is an admin
if (!Auth::user()->isAdmin()) {
    abort(403, 'Unauthorized action.');
}

// Check inventory availability for the requested blood type
$availableUnits = BloodInventory::getAvailableByBloodType($bloodRequest->blood_type_needed);
if ($availableUnits < $bloodRequest->quantity_requested) {
    return redirect()
        ->route('blood-requests.show', $bloodRequest->id)
        ->with('error', "Insufficient inventory: requested {$bloodRequest->quantity_requested} units of {$bloodRequest->blood_type_needed}, but only {$availableUnits} available.");
}

// Approve the request
$bloodRequest->status = 'Approved';
$bloodRequest->save();

// Auto-match compatible donors
$this->autoMatchDonors($bloodRequest);

return redirect()
    ->route('blood-requests.show', $bloodRequest->id)
    ->with('success', 'Blood request approved successfully. Compatible donors have been matched.');


    }

    /**
     * Reject a blood request (admin only)
     */
    public function reject(string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $bloodRequest->status = 'Rejected';
        $bloodRequest->save();

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('success', 'Blood request rejected successfully.');
    }

    /**
     * Fulfill a blood request (admin only)
     */
    public function fulfill(string $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $bloodRequest->status = 'Fulfilled';
        $bloodRequest->save();

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('success', 'Blood request marked as fulfilled.');
    }

    /**
     * Auto-match compatible donors for a blood request
     */
    private function autoMatchDonors(BloodRequest $bloodRequest)
    {
        $compatibleDonors = $bloodRequest->getCompatibleDonors();

        foreach ($compatibleDonors as $donor) {
            DonorMatch::create([
                'blood_request_id' => $bloodRequest->id,
                'donor_id' => $donor->id,
                'status' => 'pending',
                'matched_at' => now(),
            ]);
        }
    }
}