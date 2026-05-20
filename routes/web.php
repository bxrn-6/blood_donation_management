<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorMatchController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DonorDashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Admin Dashboard Route
Route::redirect('/', '/welcome2');
Route::view('/welcome2', 'welcome2');

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()->isHospital()) {
        return redirect()->route('hospital.dashboard');
    }

    if (auth()->user()->isDonor()) {
        return redirect()->route('donor.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});


Route::middleware(['auth', \App\Http\Middleware\DonorMiddleware::class])->group(function () {
    Route::get('donor/dashboard', [DonorDashboardController::class, 'index'])->name('donor.dashboard');
});


Route::middleware(['auth', 'hospital'])->group(function () {
    Route::get('hospital/dashboard', [HospitalController::class, 'index'])->name('hospital.dashboard');
});

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Donor Management routes
    Route::resource('donors', DonorController::class);
    Route::get('donors/{id}/donation-history', [DonorController::class, 'donationHistory'])->name('donors.donation-history');
    
    // Blood Inventory routes
    Route::resource('blood-inventory', BloodInventoryController::class);
    Route::get('blood-inventory/summary', [BloodInventoryController::class, 'summary'])->name('blood-inventory.summary');
    Route::post('blood-inventory/update-expired', [BloodInventoryController::class, 'updateExpiredStatus'])->name('blood-inventory.update-expired');
    
    // Blood Request routes
    Route::resource('blood-requests', BloodRequestController::class);
    Route::post('blood-requests/{id}/approve', [BloodRequestController::class, 'approve'])->name('blood-requests.approve');
    Route::post('blood-requests/{id}/reject', [BloodRequestController::class, 'reject'])->name('blood-requests.reject');
    Route::post('blood-requests/{id}/fulfill', [BloodRequestController::class, 'fulfill'])->name('blood-requests.fulfill');
    
    // Donation Management routes
    Route::resource('donations', DonationController::class);
    Route::get('donations/statistics', [DonationController::class, 'statistics'])->name('donations.statistics');
    
    // Donor Matching routes
    Route::resource('donor-matches', DonorMatchController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::post('donor-matches/{id}/accept', [DonorMatchController::class, 'accept'])->name('donor-matches.accept');
    Route::post('donor-matches/{id}/decline', [DonorMatchController::class, 'decline'])->name('donor-matches.decline');
    Route::get('donor-matches/for-request/{requestId}', [DonorMatchController::class, 'forRequest'])->name('donor-matches.for-request');
    Route::get('donor-matches/manual-match/{requestId}', [DonorMatchController::class, 'manualMatch'])->name('donor-matches.manual-match');
    Route::post('donor-matches/store-manual-match/{requestId}', [DonorMatchController::class, 'storeManualMatch'])->name('donor-matches.store-manual-match');
    
    // Reports routes
    Route::get('tables', [ReportController::class, 'tables'])->name('tables.index');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/donations', [ReportController::class, 'donations'])->name('donations');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/requests', [ReportController::class, 'requests'])->name('requests');
        Route::get('/donor-matches', [ReportController::class, 'donorMatches'])->name('donor-matches');
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        
        // Export routes
        Route::get('/export/donations', [ReportController::class, 'exportDonations'])->name('export.donations');
        Route::get('/export/inventory', [ReportController::class, 'exportInventory'])->name('export.inventory');
        Route::get('/export/requests', [ReportController::class, 'exportRequests'])->name('export.requests');
        
        // Print routes
        Route::get('/print/{type}', [ReportController::class, 'printReport'])->name('print');
    });
});

require __DIR__.'/auth.php';
