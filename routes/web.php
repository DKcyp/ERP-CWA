<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ScaffoldController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleMenusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\AreaController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Master\SupplierGroupController;
use App\Http\Controllers\Master\SupplierBalanceSummaryController;
use App\Http\Controllers\Master\SupplierCenterController;
use App\Http\Controllers\MaterialManagement\PurchaseRequestController;
use App\Http\Controllers\MaterialManagement\PurchaseRequestFulfilmentController;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/updatePassword', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    Route::middleware('cekRole')->group(function () {
        Route::prefix('role-menu')->name('role.')->group(function () {
            Route::get('/', [RoleMenusController::class, 'index'])->name('index');
            Route::get('/table', [RoleMenusController::class, 'table'])->name('table');
            Route::post('/', [RoleMenusController::class, 'store'])->name('store');
            Route::post('/show', [RoleMenusController::class, 'show'])->name('show');
            Route::post('/show-role', [RoleMenusController::class, 'showRole'])->name('showRole');
            Route::post('/save-role-menu', [RoleMenusController::class, 'saveRoleMenu'])->name('saveRoleMenu');
            Route::post('/delete', [RoleMenusController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('configuration')->name('configuration.')->group(function () {
            Route::get('/', [ConfigurationController::class, 'index'])->name('index');
            Route::get('/data', [ConfigurationController::class, 'getConfig'])->name('getConfig');
            Route::post('/', [ConfigurationController::class, 'store'])->name('store');
            Route::post('/logo', [ConfigurationController::class, 'uploadLogo'])->name('uploadLogo');
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/table', [UserController::class, 'table'])->name('table');
            Route::get('/roles', [UserController::class, 'getRoles'])->name('getRoles');
            Route::get('/Department', [UserController::class, 'getDepartment'])->name('getDepartment');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::post('/show', [UserController::class, 'show'])->name('show');
            Route::post('/delete', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/table', [MenuController::class, 'table'])->name('table');
            Route::post('/', [MenuController::class, 'store'])->name('store');
            Route::post('/delete', [MenuController::class, 'destroy'])->name('destroy');
            Route::post('/sort', [MenuController::class, 'sort'])->name('sort');
        });

        Route::prefix('area')->name('area.')->group(function () {
            Route::get('/', [AreaController::class, 'index'])->name('index');
            Route::get('/table', [AreaController::class, 'table'])->name('table');
            Route::get('/kategori-area', [AreaController::class, 'getKategoriArea'])->name('getKategoriArea');

            Route::post('/', [AreaController::class, 'store'])->name('store');
            Route::delete('/{area}', [AreaController::class, 'destroy'])->name('destroy');
            Route::put('/{area}', [AreaController::class, 'update'])->name('update');
            
            Route::get('/{area}', [AreaController::class, 'show'])->name('show');
        });

        Route::prefix('supplier-master')->name('supplier.')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('index');
            Route::get('/table', [SupplierController::class, 'table'])->name('table');
            Route::get('/supplier-groups', [SupplierController::class, 'getSupplierGroups'])->name('getSupplierGroups');
            Route::get('/supplier-centers', [SupplierController::class, 'getSupplierCenters'])->name('getSupplierCenters');
            Route::post('/', [SupplierController::class, 'store'])->name('store');
            Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
            Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supplier-group')->name('supplier-group.')->group(function () {
            Route::get('/', [SupplierGroupController::class, 'index'])->name('index');
            Route::get('/table', [SupplierGroupController::class, 'table'])->name('table');
            Route::post('/', [SupplierGroupController::class, 'store'])->name('store');
            Route::get('/{id}', [SupplierGroupController::class, 'show'])->name('show');
            Route::put('/{id}', [SupplierGroupController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierGroupController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supplier-center')->name('supplier-center.')->group(function () {
            Route::get('/', [SupplierCenterController::class, 'index'])->name('index');
            Route::get('/table', [SupplierCenterController::class, 'table'])->name('table');
            Route::post('/', [SupplierCenterController::class, 'store'])->name('store');
            Route::get('/{id}', [SupplierCenterController::class, 'show'])->name('show');
            Route::put('/{id}', [SupplierCenterController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierCenterController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supplier-balance-summary')->name('supplier-balance-summary.')->group(function () {
            Route::get('/', [SupplierBalanceSummaryController::class, 'index'])->name('index');
            Route::get('/table', [SupplierBalanceSummaryController::class, 'table'])->name('table');
        });

        Route::prefix('purchase-request-list')->name('purchase-request.')->group(function () {
            Route::get('/', [PurchaseRequestController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseRequestController::class, 'table'])->name('table');
            Route::post('/', [PurchaseRequestController::class, 'store'])->name('store');
            Route::get('/{id}', [PurchaseRequestController::class, 'show'])->name('show');
            Route::put('/{id}', [PurchaseRequestController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseRequestController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('purchase-request-fulfilment-report')->name('purchase-request-fulfilment.')->group(function () {
            Route::get('/', [PurchaseRequestFulfilmentController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseRequestFulfilmentController::class, 'table'])->name('table');
            Route::get('/{id}', [PurchaseRequestFulfilmentController::class, 'show'])->name('show');
        });

    });

    Route::get('/generator', [ScaffoldController::class, 'index'])->name('generator.index');
    Route::post('/generator', [ScaffoldController::class, 'store'])->name('generator.store');

});

require __DIR__ . '/auth.php';