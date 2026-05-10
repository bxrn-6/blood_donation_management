<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorDashboardController extends Controller
{
    /**
     * Display the donor dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('donors.create')
                ->with('info', 'Please complete your donor profile before viewing the dashboard.');
        }

        $donations = $donor->donations()->orderByDesc('donation_date')->paginate(10);
        $lastDonation = $donor->donations()->orderByDesc('donation_date')->first();
        $nextEligibleDate = $donor->last_donation_date
            ? $donor->last_donation_date->copy()->addDays(56)
            : now();

        return view('donor.dashboard', compact('donor', 'donations', 'lastDonation', 'nextEligibleDate'));
    }
}
