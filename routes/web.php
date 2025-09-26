<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\HouseOwnerAuthController;
use App\Http\Controllers\Admin\HouseOwnerController as AdminHouseOwnerController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\BuildingController as AdminBuildingController;
use App\Http\Controllers\Admin\FlatController as AdminFlatController;
use App\Http\Controllers\Admin\BillController as AdminBillController;
use App\Http\Controllers\HouseOwner\BuildingController;
use App\Http\Controllers\HouseOwner\FlatController;
use App\Http\Controllers\HouseOwner\BillController;
use App\Http\Controllers\HouseOwner\BillCategoryController;
use App\Http\Controllers\HouseOwner\TenantController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
        Route::resource('house-owners', AdminHouseOwnerController::class);

        Route::get('/house-owners/{houseOwner}/buildings', [AdminBuildingController::class, 'indexByHouseOwner'])->name('house-owners.buildings.index');
        Route::post('/house-owners/{houseOwner}/deactivate', [AdminHouseOwnerController::class, 'deactivate'])->name('house-owners.deactivate');
        Route::post('/house-owners/{houseOwner}/activate', [AdminHouseOwnerController::class, 'activate'])->name('house-owners.activate');
        Route::resource('tenants', AdminTenantController::class);
        Route::resource('buildings', AdminBuildingController::class);
        Route::resource('flats', AdminFlatController::class);
        Route::resource('bills', AdminBillController::class);
        
        Route::post('/bills/{bill}/mark-paid', [AdminBillController::class, 'markAsPaid'])->name('bills.mark-paid');
    });
});


Route::prefix('house-owner')->name('house_owner.')->group(function () {
    Route::get('/login', [HouseOwnerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HouseOwnerAuthController::class, 'login']);
    Route::post('/logout', [HouseOwnerAuthController::class, 'logout'])->name('logout');
    
    Route::middleware(['role:house_owner'])->group(function () {
        Route::get('/dashboard', [HouseOwnerAuthController::class, 'dashboard'])->name('dashboard');
        Route::resource('buildings', BuildingController::class);
        Route::resource('flats', FlatController::class);
        Route::get('/bills/overdue', [BillController::class, 'overdue'])->name('bills.overdue');
        Route::post('/flats/{flat}/assign-tenant', [FlatController::class, 'assignTenant'])->name('flats.assign-tenant');
        Route::delete('/flats/{flat}/remove-tenant', [FlatController::class, 'removeTenant'])->name('flats.remove-tenant');
        Route::resource('bills', BillController::class);
        Route::resource('bill-categories', BillCategoryController::class);
        Route::resource('tenants', TenantController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);
        
        Route::post('/bills/{bill}/mark-paid', [BillController::class, 'markAsPaid'])->name('bills.mark-paid');
        Route::post('/bills/{bill}/pay', [BillController::class, 'markAsPaid'])->name('bills.pay');
        
        Route::get('/reports/monthly', [BillController::class, 'monthlyReport'])->name('reports.monthly');
        
        // AJAX endpoints for bill creation
        Route::get('/buildings/{building}/flats', [BillController::class, 'getBuildingFlats'])->name('buildings.flats');
        Route::get('/flats/{flat}/previous-dues', [BillController::class, 'getFlatPreviousDues'])->name('flats.previous-dues');
    });
});
