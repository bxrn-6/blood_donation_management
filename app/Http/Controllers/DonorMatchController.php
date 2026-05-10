<?php

namespace App\Http\Controllers;

use App\Models\DonorMatch;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorMatchController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $donorMatches = DonorMatch::with(['bloodRequest.hospital', 'donor.user'])
                ->paginate(10);
        } elseif ($user->isDonor()) {
            $donor = $user->donor;
            if ($donor) {
                $donorMatches = DonorMatch::where('donor_id', $donor->id)
                    ->with(['bloodRequest.hospital', 'donor.user'])
                    ->paginate(10);
            } else {
                $donorMatches = collect();
            }
        } else {
            $donorMatches = collect();
        }

        return view('donor-matches.index', compact('donorMatches'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donorMatch = DonorMatch::with(['bloodRequest.hospital', 'donor.user'])
            ->findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && 
            ($user->isDonor() && $donorMatch->donor->user_id != $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        return view('donor-matches.show', compact('donorMatch'));
    }

    /**
     * Accept a donor match (donor only)
     */
    public function accept(string $id)
    {
        $donorMatch = DonorMatch::with('donor')->findOrFail($id);
        
        // Check if user is the matched donor
        $user = Auth::user();
        if (!$user->isDonor() || $donorMatch->donor->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$donorMatch->isPending()) {
            return redirect()->back()
                ->with('error', 'This match has already been responded to.');
        }

        $donorMatch->accept();

        return redirect()->route('donor-matches.show', $donorMatch->id)
            ->with('success', 'You have accepted this blood donation request.');
    }

    /**
     * Decline a donor match (donor only)
     */
    public function decline(Request $request, string $id)
    {
        $donorMatch = DonorMatch::with('donor')->findOrFail($id);
        
        // Check if user is the matched donor
        $user = Auth::user();
        if (!$user->isDonor() || $donorMatch->donor->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$donorMatch->isPending()) {
            return redirect()->back()
                ->with('error', 'This match has already been responded to.');
        }

        $request->validate([
            'response_notes' => 'nullable|string|max:500',
        ]);

        $donorMatch->decline($request->response_notes);

        return redirect()->route('donor-matches.show', $donorMatch->id)
            ->with('success', 'You have declined this blood donation request.');
    }

    /**
     * Display matches for a specific blood request
     */
    public function forRequest(string $requestId)
    {
        $bloodRequest = BloodRequest::with(['hospital', 'donorMatches.donor.user'])
            ->findOrFail($requestId);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $bloodRequest->hospital_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('donor-matches.for-request', compact('bloodRequest'));
    }

    /**
     * Manually match donors for a blood request (admin only)
     */
    public function manualMatch(string $requestId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $bloodRequest = BloodRequest::findOrFail($requestId);
        $compatibleDonors = $bloodRequest->getCompatibleDonors();

        return view('donor-matches.manual-match', compact('bloodRequest', 'compatibleDonors'));
    }

    /**
     * Store manual donor match (admin only)
     */
    public function storeManualMatch(Request $request, string $requestId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'donor_ids' => 'required|array',
            'donor_ids.*' => 'exists:donors,id',
        ]);

        $bloodRequest = BloodRequest::findOrFail($requestId);

        foreach ($request->donor_ids as $donorId) {
            // Check if match already exists
            $existingMatch = DonorMatch::where('blood_request_id', $bloodRequest->id)
                ->where('donor_id', $donorId)
                ->first();

            if (!$existingMatch) {
                DonorMatch::create([
                    'blood_request_id' => $bloodRequest->id,
                    'donor_id' => $donorId,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('donor-matches.for-request', $bloodRequest->id)
            ->with('success', 'Donor matches created successfully.');
    }
}
