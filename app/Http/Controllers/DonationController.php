<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $donations = Donation::with('donor.user')->paginate(10);
        } elseif ($user->isDonor()) {
            $donor = $user->donor;
            $donations = $donor
                ? Donation::where('donor_id', $donor->id)->with('donor.user')->paginate(10)
                : Donation::where('donor_id', 0)->with('donor.user')->paginate(10);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $donors = Donor::with('user')->get();
        } elseif ($user->isDonor()) {
            $donor = $user->donor;
            if (!$donor) {
                return redirect()->route('donors.create')
                    ->with('info', 'Create your donor profile before donating blood.');
            }
            $donors = collect([$donor]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        return view('donations.create', compact('donors', 'bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $request->validate([
                'donor_id' => 'required|exists:donors,id',
                'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'quantity_donated' => 'required|integer|min:1',
                'donation_date' => 'required|date',
                'screening_result' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $donor = Donor::findOrFail($request->donor_id);
        } elseif ($user->isDonor()) {
            $donor = $user->donor;
            if (!$donor) {
                abort(403, 'Unauthorized action.');
            }

            $request->validate([
                'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'quantity_donated' => 'required|integer|min:1',
                'donation_date' => 'required|date',
                'screening_result' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        if (!$donor->canDonate()) {
            return redirect()->back()
                ->with('error', 'Donor is not eligible to donate at this time.')
                ->withInput();
        }

        $data = $request->only(['blood_type', 'quantity_donated', 'donation_date', 'screening_result', 'notes']);
        $data['donor_id'] = $donor->id;

        Donation::create($data);

        return redirect()->route('donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donation = Donation::with('donor.user')->findOrFail($id);
        
        // Check permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $donation->donor->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $donation = Donation::findOrFail($id);
        
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $donors = Donor::with('user')->get();
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        return view('donations.edit', compact('donation', 'donors', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donation = Donation::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity_donated' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $donation->update($request->all());

        return redirect()->route('donations.show', $donation->id)
            ->with('success', 'Donation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donation = Donation::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donation record deleted successfully.');
    }

    /**
     * Display donation statistics
     */
    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $totalDonations = Donation::count();
        $totalQuantity = Donation::sum('quantity_donated');
        
        $donationsByBloodType = Donation::selectRaw('blood_type, COUNT(*) as count, SUM(quantity_donated) as total')
            ->groupBy('blood_type')
            ->get();

        $recentDonations = Donation::with('donor.user')
            ->orderBy('donation_date', 'desc')
            ->take(10)
            ->get();

        return view('donations.statistics', compact(
            'totalDonations',
            'totalQuantity',
            'donationsByBloodType',
            'recentDonations'
        ));
    }
}
