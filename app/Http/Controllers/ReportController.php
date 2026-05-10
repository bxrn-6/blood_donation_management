<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationsExport;
use App\Exports\InventoryExport;
use App\Exports\RequestsExport;

class ReportController extends Controller
{

    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Display the database tables index.
     */
    public function tables()
    {
        $database = config('database.connections.' . config('database.default') . '.database');
        $tableKey = 'Tables_in_' . $database;
        $rawTables = DB::select('SHOW TABLES');

        $tables = array_map(function ($row) use ($tableKey) {
            return $row->$tableKey;
        }, $rawTables);

        $tableData = [];
        foreach ($tables as $table) {
            try {
                $tableData[] = [
                    'name' => $table,
                    'count' => DB::table($table)->count(),
                ];
            } catch (\Throwable $e) {
                $tableData[] = [
                    'name' => $table,
                    'count' => null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return view('tables.index', ['tables' => $tableData]);
    }

    /**
     * Generate donation reports.
     */
    public function donations(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Donation::with('donor.user');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('donation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('donation_date', '<=', $request->end_date);
        }

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        $donations = $query->orderBy('donation_date', 'desc')->paginate(20);
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        // Statistics
        $totalDonations = $query->count();
        $totalQuantity = $query->sum('quantity_donated');
        $averageQuantity = $totalDonations > 0 ? round($totalQuantity / $totalDonations, 2) : 0;

        return view('reports.donations', compact(
            'donations',
            'bloodTypes',
            'totalDonations',
            'totalQuantity',
            'averageQuantity'
        ));
    }

    /**
     * Generate blood inventory reports.
     */
    public function inventory(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = BloodInventory::query();

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventory = $query->orderBy('blood_type')->get();
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $statuses = ['Available', 'Low', 'Expired'];

        // Summary statistics
        $summary = [];
        foreach ($bloodTypes as $bloodType) {
            $summary[$bloodType] = [
                'total' => $inventory->where('blood_type', $bloodType)->sum('quantity'),
                'available' => $inventory->where('blood_type', $bloodType)
                    ->where('status', 'Available')
                    ->sum('quantity'),
                'low' => $inventory->where('blood_type', $bloodType)
                    ->where('status', 'Low')
                    ->sum('quantity'),
                'expired' => $inventory->where('blood_type', $bloodType)
                    ->where('status', 'Expired')
                    ->sum('quantity'),
            ];
        }

        return view('reports.inventory', compact(
            'inventory',
            'bloodTypes',
            'statuses',
            'summary'
        ));
    }

    /**
     * Generate blood request reports.
     */
    public function requests(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = BloodRequest::with('hospital');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('request_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('request_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by urgency level
        if ($request->filled('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        $bloodRequests = $query->orderBy('request_date', 'desc')->paginate(20);
        $statuses = ['Pending', 'Approved', 'Fulfilled', 'Rejected'];
        $urgencyLevels = ['low', 'medium', 'high', 'critical'];

        // Statistics
        $totalRequests = $query->count();
        $fulfilledRequests = $query->where('status', 'Fulfilled')->count();
        $pendingRequests = $query->where('status', 'Pending')->count();
        $fulfillmentRate = $totalRequests > 0 ? round(($fulfilledRequests / $totalRequests) * 100, 2) : 0;

        return view('reports.requests', compact(
            'bloodRequests',
            'statuses',
            'urgencyLevels',
            'totalRequests',
            'fulfilledRequests',
            'pendingRequests',
            'fulfillmentRate'
        ));
    }

    /**
     * Generate donor matching reports.
     */
    public function donorMatches(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = DonorMatch::with(['bloodRequest.hospital', 'donor.user']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('matched_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('matched_at', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donorMatches = $query->orderBy('matched_at', 'desc')->paginate(20);
        $statuses = ['pending', 'accepted', 'declined'];

        // Statistics
        $totalMatches = $query->count();
        $acceptedMatches = $query->where('status', 'accepted')->count();
        $declinedMatches = $query->where('status', 'declined')->count();
        $acceptanceRate = $totalMatches > 0 ? round(($acceptedMatches / $totalMatches) * 100, 2) : 0;

        return view('reports.donor-matches', compact(
            'donorMatches',
            'statuses',
            'totalMatches',
            'acceptedMatches',
            'declinedMatches',
            'acceptanceRate'
        ));
    }

    /**
     * Generate comprehensive dashboard report.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Key metrics
        $totalDonors = Donor::count();
        $activeDonors = Donor::where('availability_status', 'available')->count();
        $totalDonations = Donation::count();
        $totalQuantity = Donation::sum('quantity_donated');
        $totalRequests = BloodRequest::count();
        $fulfilledRequests = BloodRequest::where('status', 'Fulfilled')->count();

        // Recent activity
        $recentDonations = Donation::with('donor.user')
            ->orderBy('donation_date', 'desc')
            ->take(5)
            ->get();

        $recentRequests = BloodRequest::with('hospital')
            ->orderBy('request_date', 'desc')
            ->take(5)
            ->get();

        // Blood type distribution
        $donationsByBloodType = Donation::selectRaw('blood_type, COUNT(*) as count, SUM(quantity_donated) as total')
            ->groupBy('blood_type')
            ->get();

        $inventoryByBloodType = BloodInventory::selectRaw('blood_type, SUM(quantity) as total')
            ->where('status', 'Available')
            ->groupBy('blood_type')
            ->get();

        // Monthly trends (last 6 months)
        $monthlyDonations = Donation::selectRaw('YEAR(donation_date) as year, MONTH(donation_date) as month, COUNT(*) as count')
            ->where('donation_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('reports.dashboard', compact(
            'totalDonors',
            'activeDonors',
            'totalDonations',
            'totalQuantity',
            'totalRequests',
            'fulfilledRequests',
            'recentDonations',
            'recentRequests',
            'donationsByBloodType',
            'inventoryByBloodType',
            'monthlyDonations'
        ));
    }

    /**
     * Export donations to Excel.
     */
    public function exportDonations(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return Excel::download(new DonationsExport($request), 'donations_report.xlsx');
    }

    /**
     * Export inventory to Excel.
     */
    public function exportInventory(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return Excel::download(new InventoryExport($request), 'inventory_report.xlsx');
    }

    /**
     * Export requests to Excel.
     */
    public function exportRequests(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return Excel::download(new RequestsExport($request), 'requests_report.xlsx');
    }

    /**
     * Print report.
     */
    public function printReport($type, Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        switch ($type) {
            case 'donations':
                $data = $this->getDonationReportData($request);
                return view('reports.print.donations', $data);
            case 'inventory':
                $data = $this->getInventoryReportData($request);
                return view('reports.print.inventory', $data);
            case 'requests':
                $data = $this->getRequestReportData($request);
                return view('reports.print.requests', $data);
            default:
                abort(404, 'Report type not found.');
        }
    }

    private function getDonationReportData($request)
    {
        $query = Donation::with('donor.user');
        
        if ($request->filled('start_date')) {
            $query->whereDate('donation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('donation_date', '<=', $request->end_date);
        }
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        return ['donations' => $query->orderBy('donation_date', 'desc')->get()];
    }

    private function getInventoryReportData($request)
    {
        $query = BloodInventory::query();
        
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return ['inventory' => $query->orderBy('blood_type')->get()];
    }

    private function getRequestReportData($request)
    {
        $query = BloodRequest::with('hospital');
        
        if ($request->filled('start_date')) {
            $query->whereDate('request_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('request_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        return ['bloodRequests' => $query->orderBy('request_date', 'desc')->get()];
    }
}
