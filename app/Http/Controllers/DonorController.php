<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    private function requireNonHospital(): void
    {
        if (Auth::user()->isHospital()) {
            abort(403, 'Unauthorized action.');
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->requireNonHospital();

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $donors = Donor::with('user')->paginate(10);
        return view('donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->requireNonHospital();

        $user = Auth::user();
        if (!$user->isDonor()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->donor) {
            return redirect()->route('donors.show', $user->donor->id);
        }

        return view('donors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->requireNonHospital();

        $user = Auth::user();
        if (!$user->isDonor()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->donor) {
            return redirect()->route('donors.show', $user->donor->id);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:18|max:120',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'availability_status' => 'required|in:available,unavailable',
        ]);

        $donor = Donor::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'blood_type' => $request->blood_type,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'availability_status' => $request->availability_status,
        ]);

        return redirect()->route('donors.show', $donor->id)
            ->with('success', 'Donor profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->requireNonHospital();

        $donor = Donor::with(['user', 'donations'])->findOrFail($id);
        if (Auth::user()->isDonor() && Auth::id() !== $donor->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('donors.show', compact('donor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->requireNonHospital();

        $donor = Donor::findOrFail($id);
        
        if (Auth::user()->isAdmin() || Auth::id() === $donor->user_id) {
            return view('donors.edit', compact('donor'));
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->requireNonHospital();

        $donor = Donor::findOrFail($id);
        
        if (!Auth::user()->isAdmin() && Auth::id() !== $donor->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:18|max:120',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'availability_status' => 'required|in:available,unavailable',
        ]);

        $donor->update([
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'blood_type' => $request->blood_type,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'availability_status' => $request->availability_status,
        ]);

        return redirect()->route('donors.show', $donor->id)
            ->with('success', 'Donor profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->requireNonHospital();

        $donor = Donor::findOrFail($id);
        
        // Only admin can delete donor profiles
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $donor->delete();

        return redirect()->route('donors.index')
            ->with('success', 'Donor profile deleted successfully.');
    }

    /**
     * Display donor's donation history.
     */
    public function donationHistory(string $id)
    {
        $this->requireNonHospital();

        $donor = Donor::with('donations')->findOrFail($id);
        if (Auth::user()->isDonor() && Auth::id() !== $donor->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('donors.donation-history', compact('donor'));
    }
}
