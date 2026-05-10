<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $totalRequests = BloodRequest::where('hospital_id', $user->id)->count();
        $pendingRequests = BloodRequest::where('hospital_id', $user->id)
            ->where('status', 'Pending')
            ->count();
        $approvedRequests = BloodRequest::where('hospital_id', $user->id)
            ->where('status', 'Approved')
            ->count();
        $fulfilledRequests = BloodRequest::where('hospital_id', $user->id)
            ->where('status', 'Fulfilled')
            ->count();

        $query = BloodRequest::with('hospital')
            ->where('hospital_id', $user->id)
            ->when($request->filled('request_search'), function ($query) use ($request) {
                $term = '%' . $request->request_search . '%';
                $query->where('request_id', 'like', $term)
                    ->orWhere('hospital_name', 'like', $term)
                    ->orWhere('blood_type_needed', 'like', $term);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('blood_type'), function ($query) use ($request) {
                $query->where('blood_type_needed', $request->blood_type);
            });

        $bloodRequests = $query->orderBy('request_date', 'desc')
            ->paginate(8)
            ->withQueryString();

        $matchingDonors = [];
        foreach ($bloodRequests as $bloodRequest) {
            $matchingDonors[$bloodRequest->id] = $bloodRequest->getCompatibleDonors();
        }

        return view('hospital.dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'fulfilledRequests',
            'bloodRequests',
            'matchingDonors'
        ));
    }
}
