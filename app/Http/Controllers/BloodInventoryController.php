<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodInventoryController extends Controller
{
    private function requireAdmin(): void
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = BloodInventory::orderBy('blood_type')->get();
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        return view('blood-inventory.index', compact('inventory', 'bloodTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->requireAdmin();

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('blood-inventory.create', compact('bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'expiration_date' => 'required|date|after:donation_date',
        ]);

        BloodInventory::create($request->all());

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = BloodInventory::findOrFail($id);
        return view('blood-inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->requireAdmin();

        $inventory = BloodInventory::findOrFail($id);
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('blood-inventory.edit', compact('inventory', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->requireAdmin();

        $inventory = BloodInventory::findOrFail($id);

        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|integer|min:0',
            'donation_date' => 'required|date',
            'expiration_date' => 'required|date|after:donation_date',
        ]);

        $inventory->update($request->all());
        $inventory->updateStatus();

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->requireAdmin();

        $inventory = BloodInventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory record deleted successfully.');
    }

    /**
     * Update status of expired blood units
     */
    public function updateExpiredStatus()
    {
        $this->requireAdmin();

        $expiredCount = BloodInventory::where('expiration_date', '<', now())
            ->where('status', '!=', 'Expired')
            ->update(['status' => 'Expired']);

        return redirect()->route('blood-inventory.index')
            ->with('success', "Updated {$expiredCount} expired blood units.");
    }

    /**
     * Get inventory summary by blood type
     */
    public function summary()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $summary = [];

        foreach ($bloodTypes as $bloodType) {
            $summary[$bloodType] = [
                'total' => BloodInventory::where('blood_type', $bloodType)->sum('quantity'),
                'available' => BloodInventory::where('blood_type', $bloodType)
                    ->where('status', 'Available')
                    ->where('expiration_date', '>', now())
                    ->sum('quantity'),
                'low' => BloodInventory::where('blood_type', $bloodType)
                    ->where('status', 'Low')
                    ->sum('quantity'),
                'expired' => BloodInventory::where('blood_type', $bloodType)
                    ->where('status', 'Expired')
                    ->sum('quantity'),
            ];
        }

        return view('blood-inventory.summary', compact('summary'));
    }
}
