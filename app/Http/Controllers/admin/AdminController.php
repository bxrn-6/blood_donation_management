<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $totalDonors = Donor::count();
        $availableBloodUnits = BloodInventory::where('status', 'Available')->sum('quantity');
        $pendingRequests = BloodRequest::where('status', 'Pending')->count();
        $totalDonations = Donation::count();

        $donors = Donor::with('user')
            ->when($request->filled('donor_search'), function ($query) use ($request) {
                $term = '%' . $request->donor_search . '%';
                $query->where('full_name', 'like', $term)
                    ->orWhere('contact_number', 'like', $term)
                    ->orWhere('address', 'like', $term)
                    ->orWhereHas('user', function ($query) use ($term) {
                        $query->where('name', 'like', $term)
                              ->orWhere('email', 'like', $term);
                    });
            })
            ->orderBy('full_name')
            ->paginate(8)
            ->withQueryString();

        $bloodInventory = BloodInventory::when($request->filled('inventory_search'), function ($query) use ($request) {
                $term = '%' . $request->inventory_search . '%';
                $query->where('blood_type', 'like', $term)
                    ->orWhere('status', 'like', $term);
            })
            ->when($request->filled('inventory_status'), function ($query) use ($request) {
                $query->where('status', $request->inventory_status);
            })
            ->orderBy('expiration_date')
            ->paginate(8)
            ->withQueryString();

        $bloodRequests = BloodRequest::with('hospital')
            ->when($request->filled('request_search'), function ($query) use ($request) {
                $term = '%' . $request->request_search . '%';
                $query->where('request_id', 'like', $term)
                    ->orWhere('hospital_name', 'like', $term)
                    ->orWhere('blood_type_needed', 'like', $term);
            })
            ->when($request->filled('request_status'), function ($query) use ($request) {
                $query->where('status', $request->request_status);
            })
            ->orderBy('request_date', 'desc')
            ->paginate(8)
            ->withQueryString();

        $donations = Donation::with('donor.user')
            ->when($request->filled('donation_search'), function ($query) use ($request) {
                $term = '%' . $request->donation_search . '%';
                $query->where('blood_type', 'like', $term)
                    ->orWhere('status', 'like', $term)
                    ->orWhereHas('donor', function ($query) use ($term) {
                        $query->where('full_name', 'like', $term);
                    });
            })
            ->orderBy('donation_date', 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'totalDonors',
            'availableBloodUnits',
            'pendingRequests',
            'totalDonations',
            'donors',
            'bloodInventory',
            'bloodRequests',
            'donations'
        ));
    }
}
