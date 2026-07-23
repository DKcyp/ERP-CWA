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
use App\Http\Controllers\MaterialManagement\PurchaseOrderListController;
use App\Http\Controllers\MaterialManagement\PurchaseOrderFulfillmentController;
use App\Http\Controllers\MaterialManagement\DailyPurchaseOrderReportController;
use App\Http\Controllers\MaterialManagement\PurchaseInvoiceListController;
use App\Http\Controllers\MaterialManagement\DailyPurchaseInvoiceReportController;
use App\Http\Controllers\MaterialManagement\MonthlyPurchaseBySupplierReportController;
use App\Http\Controllers\MaterialManagement\StbjController;
use App\Http\Controllers\MaterialManagement\SupplierPaymentListController;
use App\Http\Controllers\MaterialManagement\SupplierOutstandingListController;
use App\Http\Controllers\MaterialManagement\SupplierDailyPaymentReportController;
use App\Http\Controllers\MaterialManagement\SupplierDailyPaymentListController;
use App\Http\Controllers\MaterialManagement\PurchaseReturnListController;
use App\Http\Controllers\MaterialManagement\SjbbController;
use App\Http\Controllers\MaterialManagement\StockAdjustmentUseController;
use App\Http\Controllers\MaterialManagement\StockAdjustmentListController;
use App\Http\Controllers\MaterialManagement\StockTransferListController;
use App\Http\Controllers\MaterialManagement\DailyStockTransferReportController;
use App\Http\Controllers\MaterialManagement\StockTransferFulfilmentController;
use App\Http\Controllers\MaterialManagement\DailyStockAdjustmentCostReportController;
use App\Http\Controllers\MaterialManagement\DailyStockAdjustmentTrackReportController;
use App\Http\Controllers\MaterialManagement\DailyStockAdjustmentReportController;

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
            Route::put('/{id}/status', [PurchaseRequestController::class, 'updateStatus'])->name('status');
            Route::delete('/{id}', [PurchaseRequestController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('purchase-request-fulfilment-report')->name('purchase-request-fulfilment.')->group(function () {
            Route::get('/', [PurchaseRequestFulfilmentController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseRequestFulfilmentController::class, 'table'])->name('table');
            Route::get('/{id}', [PurchaseRequestFulfilmentController::class, 'show'])->name('show');
        });

        Route::prefix('purchase-order-list')->name('purchase-order.')->group(function () {
            Route::get('/', [PurchaseOrderListController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseOrderListController::class, 'table'])->name('table');
            Route::put('/{id}/status', [PurchaseOrderListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [PurchaseOrderListController::class, 'show'])->name('show');
        });

        Route::prefix('purchase-fulfillment-report')->name('purchase-fulfill.')->group(function () {
            Route::get('/', [PurchaseOrderFulfillmentController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseOrderFulfillmentController::class, 'table'])->name('table');
            Route::get('/{id}', [PurchaseOrderFulfillmentController::class, 'show'])->name('show');
        });

        Route::prefix('daily-purchase-order-report')->name('daily-po.')->group(function () {
            Route::get('/', [DailyPurchaseOrderReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyPurchaseOrderReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyPurchaseOrderReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyPurchaseOrderReportController::class, 'show'])->name('show');
        });

        Route::prefix('purchase-invoice-list')->name('purchase-invoice.')->group(function () {
            Route::get('/', [PurchaseInvoiceListController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseInvoiceListController::class, 'table'])->name('table');
            Route::post('/', [PurchaseInvoiceListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [PurchaseInvoiceListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [PurchaseInvoiceListController::class, 'show'])->name('show');
            Route::put('/{id}', [PurchaseInvoiceListController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseInvoiceListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('daily-purchase-invoice-report')->name('daily-invoice.')->group(function () {
            Route::get('/', [DailyPurchaseInvoiceReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyPurchaseInvoiceReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyPurchaseInvoiceReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyPurchaseInvoiceReportController::class, 'show'])->name('show');
        });

        Route::prefix('monthly-purchase-by-supplier-report')->name('monthly-supplier.')->group(function () {
            Route::get('/', [MonthlyPurchaseBySupplierReportController::class, 'index'])->name('index');
            Route::get('/table', [MonthlyPurchaseBySupplierReportController::class, 'table'])->name('table');
            Route::get('/summary', [MonthlyPurchaseBySupplierReportController::class, 'summary'])->name('summary');
        });

        Route::prefix('stbj')->name('stbj.')->group(function () {
            Route::get('/', [StbjController::class, 'index'])->name('index');
            Route::get('/table', [StbjController::class, 'table'])->name('table');
            Route::post('/', [StbjController::class, 'store'])->name('store');
            Route::put('/{id}/status', [StbjController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [StbjController::class, 'show'])->name('show');
            Route::put('/{id}', [StbjController::class, 'update'])->name('update');
            Route::delete('/{id}', [StbjController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supplier-payment-list')->name('supplier-payment.')->group(function () {
            Route::get('/', [SupplierPaymentListController::class, 'index'])->name('index');
            Route::get('/table', [SupplierPaymentListController::class, 'table'])->name('table');
            Route::post('/', [SupplierPaymentListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [SupplierPaymentListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [SupplierPaymentListController::class, 'show'])->name('show');
            Route::put('/{id}', [SupplierPaymentListController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierPaymentListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('supp-outstanding-list')->name('supp-outstanding.')->group(function () {
            Route::get('/', [SupplierOutstandingListController::class, 'index'])->name('index');
            Route::get('/table', [SupplierOutstandingListController::class, 'table'])->name('table');
        });

        Route::prefix('daily-supplier-payment-report')->name('daily-supplier-payment.')->group(function () {
            Route::get('/', [SupplierDailyPaymentReportController::class, 'index'])->name('index');
            Route::get('/table', [SupplierDailyPaymentReportController::class, 'table'])->name('table');
            Route::get('/summary', [SupplierDailyPaymentReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [SupplierDailyPaymentReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-supplier-payment-list')->name('daily-supplier-payment-list.')->group(function () {
            Route::get('/', [SupplierDailyPaymentListController::class, 'index'])->name('index');
            Route::get('/table', [SupplierDailyPaymentListController::class, 'table'])->name('table');
        });

        Route::prefix('purchase-return-list')->name('purchase-return.')->group(function () {
            Route::get('/', [PurchaseReturnListController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseReturnListController::class, 'table'])->name('table');
            Route::post('/', [PurchaseReturnListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [PurchaseReturnListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [PurchaseReturnListController::class, 'show'])->name('show');
            Route::put('/{id}', [PurchaseReturnListController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseReturnListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sjbb')->name('sjbb.')->group(function () {
            Route::get('/', [SjbbController::class, 'index'])->name('index');
            Route::get('/table', [SjbbController::class, 'table'])->name('table');
            Route::post('/', [SjbbController::class, 'store'])->name('store');
            Route::put('/{id}/status', [SjbbController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [SjbbController::class, 'show'])->name('show');
            Route::put('/{id}', [SjbbController::class, 'update'])->name('update');
            Route::delete('/{id}', [SjbbController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('stock-adjustment-use')->name('stock-adjustment-use.')->group(function () {
            Route::get('/', [StockAdjustmentUseController::class, 'index'])->name('index');
            Route::get('/table', [StockAdjustmentUseController::class, 'table'])->name('table');
            Route::post('/', [StockAdjustmentUseController::class, 'store'])->name('store');
            Route::put('/{id}/status', [StockAdjustmentUseController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [StockAdjustmentUseController::class, 'show'])->name('show');
            Route::put('/{id}', [StockAdjustmentUseController::class, 'update'])->name('update');
            Route::delete('/{id}', [StockAdjustmentUseController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('stock-adjustment-list')->name('stock-adjustment-list.')->group(function () {
            Route::get('/', [StockAdjustmentListController::class, 'index'])->name('index');
            Route::get('/table', [StockAdjustmentListController::class, 'table'])->name('table');
            Route::post('/', [StockAdjustmentListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [StockAdjustmentListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [StockAdjustmentListController::class, 'show'])->name('show');
            Route::put('/{id}', [StockAdjustmentListController::class, 'update'])->name('update');
            Route::delete('/{id}', [StockAdjustmentListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('stock-transfer-list')->name('stock-transfer.')->group(function () {
            Route::get('/', [StockTransferListController::class, 'index'])->name('index');
            Route::get('/table', [StockTransferListController::class, 'table'])->name('table');
            Route::post('/', [StockTransferListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [StockTransferListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [StockTransferListController::class, 'show'])->name('show');
            Route::put('/{id}', [StockTransferListController::class, 'update'])->name('update');
            Route::delete('/{id}', [StockTransferListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('daily-stock-transfer-report')->name('daily-stock-transfer.')->group(function () {
            Route::get('/', [DailyStockTransferReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyStockTransferReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyStockTransferReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyStockTransferReportController::class, 'show'])->name('show');
        });

        Route::prefix('stock-transfer-fulfilment')->name('stock-transfer-fulfilment.')->group(function () {
            Route::get('/', [StockTransferFulfilmentController::class, 'index'])->name('index');
            Route::get('/table', [StockTransferFulfilmentController::class, 'table'])->name('table');
            Route::get('/{id}', [StockTransferFulfilmentController::class, 'show'])->name('show');
        });

        Route::prefix('daily-stock-adjustment-cost-report')->name('daily-stock-adjustment-cost.')->group(function () {
            Route::get('/', [DailyStockAdjustmentCostReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyStockAdjustmentCostReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyStockAdjustmentCostReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyStockAdjustmentCostReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-stock-adjustment-track-report')->name('daily-stock-adjustment-track.')->group(function () {
            Route::get('/', [DailyStockAdjustmentTrackReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyStockAdjustmentTrackReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyStockAdjustmentTrackReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyStockAdjustmentTrackReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-stock-adjustment-report')->name('daily-stock-adjustment.')->group(function () {
            Route::get('/', [DailyStockAdjustmentReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyStockAdjustmentReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailyStockAdjustmentReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailyStockAdjustmentReportController::class, 'show'])->name('show');
        });

    });

    Route::get('/generator', [ScaffoldController::class, 'index'])->name('generator.index');
    Route::post('/generator', [ScaffoldController::class, 'store'])->name('generator.store');

});

require __DIR__ . '/auth.php';