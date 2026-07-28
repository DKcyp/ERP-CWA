<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuSearchController;
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
use App\Http\Controllers\Master\CustomerMasterController;
use App\Http\Controllers\Master\CustomerGroupController;
use App\Http\Controllers\Master\CustomerAreaController;
use App\Http\Controllers\Master\WaNameController;
use App\Http\Controllers\Master\CustomerToolsController;
use App\Http\Controllers\Master\CustomerCentreController;
use App\Http\Controllers\Master\CustomerBalanceSummaryController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\BrandController;
use App\Http\Controllers\Master\GroupController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\SeriesController;
use App\Http\Controllers\Master\HierarchyController;
use App\Http\Controllers\Master\QualityController;
use App\Http\Controllers\Master\DiscountMasterController;
use App\Http\Controllers\Master\PriceListController;
use App\Http\Controllers\Master\PriceListController;
use App\Http\Controllers\Master\UomController;
use App\Http\Controllers\MaterialManagement\PurchaseRequestController;
use App\Http\Controllers\MaterialManagement\PurchaseRequestFulfilmentController;
use App\Http\Controllers\MaterialManagement\PurchaseOrderListController;
use App\Http\Controllers\MaterialManagement\PurchaseOrderFulfillmentController;
use App\Http\Controllers\MaterialManagement\DailyPurchaseOrderReportController;
use App\Http\Controllers\MaterialManagement\PurchaseInvoiceListController;
use App\Http\Controllers\MaterialManagement\StockConvertionController;
use App\Http\Controllers\MaterialManagement\MaterialTemplateController;
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
use App\Http\Controllers\SalesDistribution\ArWarehouseReportController;
use App\Http\Controllers\SalesDistribution\PointSettingController;
use App\Http\Controllers\SalesDistribution\CustomerPointPromoRuleController;
use App\Http\Controllers\SalesDistribution\CategoryExceptionController;
use App\Http\Controllers\SalesDistribution\ProductPointClaimSetupController;
use App\Http\Controllers\SalesDistribution\ClaimProductController;
use App\Http\Controllers\SalesDistribution\ClaimProductDailyReportController;
use App\Http\Controllers\SalesDistribution\SalesOrderListController;
use App\Http\Controllers\SalesDistribution\SalesOrderFulfilmentController;
use App\Http\Controllers\SalesDistribution\DailySalesOrderReportController;
use App\Http\Controllers\SalesDistribution\DailySalesOrderInvoiceReportController;
use App\Http\Controllers\SalesDistribution\PackingController;
use App\Http\Controllers\SalesDistribution\SalesInvoiceListController;
use App\Http\Controllers\SalesDistribution\ShipmentPriorityController;
use App\Http\Controllers\SalesDistribution\SalesPromoReportController;
use App\Http\Controllers\SalesDistribution\SalesProfitReportController;
use App\Http\Controllers\SalesDistribution\SalesOmsetReportController;
use App\Http\Controllers\SalesDistribution\SalesVoidReportController;
use App\Http\Controllers\SalesDistribution\SalesCommisionReportController;
use App\Http\Controllers\SalesDistribution\InvoicePaymentReportController;
use App\Http\Controllers\SalesDistribution\ProfitLossReportController;
use App\Http\Controllers\SalesDistribution\SalesReportController;
use App\Http\Controllers\SalesDistribution\TandaTerimaPenagihanController;
use App\Http\Controllers\SalesDistribution\CustomerPaymentListController;
use App\Http\Controllers\SalesDistribution\CustOutstandingListController;
use App\Http\Controllers\SalesDistribution\DailyCustomerPaymentReportController;
use App\Http\Controllers\SalesDistribution\OutstandingPerCustomerReportController;
use App\Http\Controllers\SalesDistribution\CustomerPaymentCheckController;
use App\Http\Controllers\SalesDistribution\CustomerOutstandingPerDateReportController;
use App\Http\Controllers\SalesDistribution\SalesReturnListController;
use App\Http\Controllers\SalesDistribution\DailySalesReturnReportController;
use App\Http\Controllers\SalesDistribution\TandaTerimaInvoiceController;
use App\Http\Controllers\SalesDistribution\DeliveryOrderController;
use App\Http\Controllers\SalesDistribution\ShipmentPreparationController;
use App\Http\Controllers\SalesDistribution\PurchaseNoteController;
use App\Http\Controllers\SalesDistribution\SalesCommissionController;
use App\Http\Controllers\SalesDistribution\TaxController;
use App\Http\Controllers\TransitArea\DailySalesInvoiceReportController;
use App\Http\Controllers\TransitArea\DailySalesPoClosingReportController;
use App\Http\Controllers\TransitArea\DailySalesByBrandReportController;
use App\Http\Controllers\TransitArea\DailyPaymentRecapReportController;
use App\Http\Controllers\TransitArea\ChequeManagementController;
use App\Http\Controllers\TransitArea\RlhpController;
use App\Http\Controllers\TransitArea\ArPerCustomerReportController;
use App\Http\Controllers\TransitArea\CustomerArPositionReportController;
use App\Http\Controllers\TransitArea\InvoiceCustomerArListReportController;
use App\Http\Controllers\TransitArea\SalesmanArListPmbController;
use App\Http\Controllers\TransitArea\InvoiceExpeditionController;
use App\Http\Controllers\TransitArea\ShippingInvoiceExpeditionController;
use App\Http\Controllers\TransitArea\TransitAreaTargetController;
use App\Http\Controllers\TransitArea\UbmDailyControlProgressSalesReportController;
use App\Http\Controllers\TransitArea\TransitAreaNewBrandController;
use App\Http\Controllers\TransitArea\UbmNewProductSalesReportController;
use App\Http\Controllers\TransitArea\UbmCollectionProgressReportController;
use App\Http\Controllers\TransitArea\DailySalesAchievementReportController;
use App\Http\Controllers\TransitArea\PmbController;

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

        Route::prefix('customer-master')->name('customer-master.')->group(function () {
            Route::get('/', [CustomerMasterController::class, 'index'])->name('index');
            Route::get('/table', [CustomerMasterController::class, 'table'])->name('table');
            Route::post('/', [CustomerMasterController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerMasterController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerMasterController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerMasterController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-group')->name('customer-group.')->group(function () {
            Route::get('/', [CustomerGroupController::class, 'index'])->name('index');
            Route::get('/table', [CustomerGroupController::class, 'table'])->name('table');
            Route::post('/', [CustomerGroupController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerGroupController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerGroupController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerGroupController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-area')->name('customer-area.')->group(function () {
            Route::get('/', [CustomerAreaController::class, 'index'])->name('index');
            Route::get('/table', [CustomerAreaController::class, 'table'])->name('table');
            Route::post('/', [CustomerAreaController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerAreaController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerAreaController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerAreaController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('wa-name')->name('wa-name.')->group(function () {
            Route::get('/', [WaNameController::class, 'index'])->name('index');
            Route::get('/table', [WaNameController::class, 'table'])->name('table');
            Route::post('/', [WaNameController::class, 'store'])->name('store');
            Route::get('/{id}', [WaNameController::class, 'show'])->name('show');
            Route::put('/{id}', [WaNameController::class, 'update'])->name('update');
            Route::delete('/{id}', [WaNameController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-tools')->name('customer-tools.')->group(function () {
            Route::get('/', [CustomerToolsController::class, 'index'])->name('index');
            Route::get('/table', [CustomerToolsController::class, 'table'])->name('table');
            Route::post('/', [CustomerToolsController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerToolsController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerToolsController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerToolsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-centre')->name('customer-centre.')->group(function () {
            Route::get('/', [CustomerCentreController::class, 'index'])->name('index');
            Route::get('/table', [CustomerCentreController::class, 'table'])->name('table');
            Route::post('/', [CustomerCentreController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerCentreController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerCentreController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerCentreController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-balance-summary')->name('customer-balance.')->group(function () {
            Route::get('/', [CustomerBalanceSummaryController::class, 'index'])->name('index');
            Route::get('/table', [CustomerBalanceSummaryController::class, 'table'])->name('table');
        });

        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/table', [ProductController::class, 'table'])->name('table');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}', [ProductController::class, 'show'])->name('show');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('brand')->name('brand.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::get('/table', [BrandController::class, 'table'])->name('table');
            Route::post('/', [BrandController::class, 'store'])->name('store');
            Route::get('/{id}', [BrandController::class, 'show'])->name('show');
            Route::put('/{id}', [BrandController::class, 'update'])->name('update');
            Route::delete('/{id}', [BrandController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('group')->name('group.')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('index');
            Route::get('/table', [GroupController::class, 'table'])->name('table');
            Route::post('/', [GroupController::class, 'store'])->name('store');
            Route::get('/{id}', [GroupController::class, 'show'])->name('show');
            Route::put('/{id}', [GroupController::class, 'update'])->name('update');
            Route::delete('/{id}', [GroupController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/table', [CategoryController::class, 'table'])->name('table');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('series')->name('series.')->group(function () {
            Route::get('/', [SeriesController::class, 'index'])->name('index');
            Route::get('/table', [SeriesController::class, 'table'])->name('table');
            Route::post('/', [SeriesController::class, 'store'])->name('store');
            Route::get('/{id}', [SeriesController::class, 'show'])->name('show');
            Route::put('/{id}', [SeriesController::class, 'update'])->name('update');
            Route::delete('/{id}', [SeriesController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('hierarchy')->name('hierarchy.')->group(function () {
            Route::get('/', [HierarchyController::class, 'index'])->name('index');
            Route::get('/table', [HierarchyController::class, 'table'])->name('table');
            Route::post('/', [HierarchyController::class, 'store'])->name('store');
            Route::get('/{id}', [HierarchyController::class, 'show'])->name('show');
            Route::put('/{id}', [HierarchyController::class, 'update'])->name('update');
            Route::delete('/{id}', [HierarchyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('quality')->name('quality.')->group(function () {
            Route::get('/', [QualityController::class, 'index'])->name('index');
            Route::get('/table', [QualityController::class, 'table'])->name('table');
            Route::post('/', [QualityController::class, 'store'])->name('store');
            Route::get('/{id}', [QualityController::class, 'show'])->name('show');
            Route::put('/{id}', [QualityController::class, 'update'])->name('update');
            Route::delete('/{id}', [QualityController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('unit-of-measures')->name('uom.')->group(function () {
            Route::get('/', [UomController::class, 'index'])->name('index');
            Route::get('/table', [UomController::class, 'table'])->name('table');
            Route::post('/', [UomController::class, 'store'])->name('store');
            Route::get('/{id}', [UomController::class, 'show'])->name('show');
            Route::put('/{id}', [UomController::class, 'update'])->name('update');
            Route::delete('/{id}', [UomController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('discount')->name('discount.')->group(function () {
            Route::get('/', [DiscountMasterController::class, 'index'])->name('index');
            Route::get('/table', [DiscountMasterController::class, 'table'])->name('table');
            Route::post('/', [DiscountMasterController::class, 'store'])->name('store');
            Route::get('/{id}', [DiscountMasterController::class, 'show'])->name('show');
            Route::put('/{id}', [DiscountMasterController::class, 'update'])->name('update');
            Route::delete('/{id}', [DiscountMasterController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('price-list')->name('price-list.')->group(function () {
            Route::get('/', [PriceListController::class, 'index'])->name('index');
            Route::get('/table', [PriceListController::class, 'table'])->name('table');
            Route::get('/detail/{id}', [PriceListController::class, 'detail'])->name('detail');
            Route::post('/', [PriceListController::class, 'store'])->name('store');
            Route::get('/{id}', [PriceListController::class, 'show'])->name('show');
            Route::put('/{id}', [PriceListController::class, 'update'])->name('update');
            Route::post('/duplicate', [PriceListController::class, 'duplicate'])->name('duplicate');
            Route::delete('/{id}', [PriceListController::class, 'destroy'])->name('destroy');
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
            Route::post('/', [PurchaseOrderListController::class, 'store'])->name('store');
            Route::put('/{id}/status', [PurchaseOrderListController::class, 'updateStatus'])->name('status');
            Route::get('/{id}', [PurchaseOrderListController::class, 'show'])->name('show');
            Route::put('/{id}', [PurchaseOrderListController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseOrderListController::class, 'destroy'])->name('destroy');
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

        Route::prefix('stock-convertion')->name('stock-convertion.')->group(function () {
            Route::get('/', [StockConvertionController::class, 'index'])->name('index');
            Route::get('/table', [StockConvertionController::class, 'table'])->name('table');
            Route::post('/', [StockConvertionController::class, 'store'])->name('store');
            Route::get('/{id}', [StockConvertionController::class, 'show'])->name('show');
            Route::put('/{id}', [StockConvertionController::class, 'update'])->name('update');
            Route::delete('/{id}', [StockConvertionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('material-template')->name('material-template.')->group(function () {
            Route::get('/', [MaterialTemplateController::class, 'index'])->name('index');
            Route::get('/table', [MaterialTemplateController::class, 'table'])->name('table');
            Route::post('/', [MaterialTemplateController::class, 'store'])->name('store');
            Route::get('/{id}', [MaterialTemplateController::class, 'show'])->name('show');
            Route::put('/{id}', [MaterialTemplateController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaterialTemplateController::class, 'destroy'])->name('destroy');
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

        Route::prefix('ar-warehouse-report')->name('ar-warehouse.')->group(function () {
            Route::get('/', [ArWarehouseReportController::class, 'index'])->name('index');
            Route::get('/table', [ArWarehouseReportController::class, 'table'])->name('table');
        });

        Route::prefix('point-setting')->name('point-setting.')->group(function () {
            Route::get('/', [PointSettingController::class, 'index'])->name('index');
            Route::get('/table', [PointSettingController::class, 'table'])->name('table');
            Route::post('/', [PointSettingController::class, 'store'])->name('store');
            Route::get('/{id}', [PointSettingController::class, 'show'])->name('show');
            Route::put('/{id}', [PointSettingController::class, 'update'])->name('update');
            Route::delete('/{id}', [PointSettingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-point-promo-rule')->name('point-promo-rule.')->group(function () {
            Route::get('/', [CustomerPointPromoRuleController::class, 'index'])->name('index');
            Route::get('/table', [CustomerPointPromoRuleController::class, 'table'])->name('table');
            Route::post('/', [CustomerPointPromoRuleController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerPointPromoRuleController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerPointPromoRuleController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerPointPromoRuleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('category-exception')->name('category-exception.')->group(function () {
            Route::get('/', [CategoryExceptionController::class, 'index'])->name('index');
            Route::get('/table', [CategoryExceptionController::class, 'table'])->name('table');
            Route::post('/', [CategoryExceptionController::class, 'store'])->name('store');
            Route::get('/{id}', [CategoryExceptionController::class, 'show'])->name('show');
            Route::put('/{id}', [CategoryExceptionController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryExceptionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('product-point-claim-setup')->name('product-point-claim-setup.')->group(function () {
            Route::get('/', [ProductPointClaimSetupController::class, 'index'])->name('index');
            Route::get('/table', [ProductPointClaimSetupController::class, 'table'])->name('table');
            Route::post('/', [ProductPointClaimSetupController::class, 'store'])->name('store');
            Route::get('/{id}', [ProductPointClaimSetupController::class, 'show'])->name('show');
            Route::put('/{id}', [ProductPointClaimSetupController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductPointClaimSetupController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('claim-product')->name('claim-product.')->group(function () {
            Route::get('/', [ClaimProductController::class, 'index'])->name('index');
            Route::get('/table', [ClaimProductController::class, 'table'])->name('table');
            Route::post('/', [ClaimProductController::class, 'store'])->name('store');
            Route::get('/{id}', [ClaimProductController::class, 'show'])->name('show');
            Route::put('/{id}', [ClaimProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ClaimProductController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('claim-product-daily-report')->name('claim-product-daily-report.')->group(function () {
            Route::get('/', [ClaimProductDailyReportController::class, 'index'])->name('index');
            Route::get('/table', [ClaimProductDailyReportController::class, 'table'])->name('table');
            Route::get('/summary', [ClaimProductDailyReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [ClaimProductDailyReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-order-list')->name('sales-order.')->group(function () {
            Route::get('/', [SalesOrderListController::class, 'index'])->name('index');
            Route::get('/table', [SalesOrderListController::class, 'table'])->name('table');
            Route::post('/', [SalesOrderListController::class, 'store'])->name('store');
            Route::get('/{id}', [SalesOrderListController::class, 'show'])->name('show');
            Route::put('/{id}', [SalesOrderListController::class, 'update'])->name('update');
            Route::delete('/{id}', [SalesOrderListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sales-order-fulfilment')->name('sales-order-fulfilment.')->group(function () {
            Route::get('/', [SalesOrderFulfilmentController::class, 'index'])->name('index');
            Route::get('/table', [SalesOrderFulfilmentController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesOrderFulfilmentController::class, 'show'])->name('show');
        });

        Route::prefix('daily-sales-order-report')->name('daily-sales-order-report.')->group(function () {
            Route::get('/', [DailySalesOrderReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesOrderReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailySalesOrderReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailySalesOrderReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-sales-order-invoice-report')->name('daily-so-invoice-report.')->group(function () {
            Route::get('/', [DailySalesOrderInvoiceReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesOrderInvoiceReportController::class, 'table'])->name('table');
            Route::get('/summary', [DailySalesOrderInvoiceReportController::class, 'summary'])->name('summary');
            Route::get('/{id}', [DailySalesOrderInvoiceReportController::class, 'show'])->name('show');
        });

        Route::prefix('packing')->name('packing.')->group(function () {
            Route::get('/', [PackingController::class, 'index'])->name('index');
            Route::get('/table', [PackingController::class, 'table'])->name('table');
            Route::post('/', [PackingController::class, 'store'])->name('store');
            Route::get('/{id}', [PackingController::class, 'show'])->name('show');
            Route::put('/{id}', [PackingController::class, 'update'])->name('update');
            Route::delete('/{id}', [PackingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sales-invoice-list')->name('sales-invoice.')->group(function () {
            Route::get('/', [SalesInvoiceListController::class, 'index'])->name('index');
            Route::get('/table', [SalesInvoiceListController::class, 'table'])->name('table');
            Route::post('/', [SalesInvoiceListController::class, 'store'])->name('store');
            Route::get('/{id}', [SalesInvoiceListController::class, 'show'])->name('show');
            Route::put('/{id}', [SalesInvoiceListController::class, 'update'])->name('update');
            Route::delete('/{id}', [SalesInvoiceListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('shipment-priority')->name('shipment-priority.')->group(function () {
            Route::get('/', [ShipmentPriorityController::class, 'index'])->name('index');
            Route::get('/table', [ShipmentPriorityController::class, 'table'])->name('table');
            Route::post('/', [ShipmentPriorityController::class, 'store'])->name('store');
            Route::get('/{id}', [ShipmentPriorityController::class, 'show'])->name('show');
            Route::put('/{id}', [ShipmentPriorityController::class, 'update'])->name('update');
            Route::delete('/{id}', [ShipmentPriorityController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sales-promo-report')->name('sales-promo-report.')->group(function () {
            Route::get('/', [SalesPromoReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesPromoReportController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesPromoReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-profit-report')->name('sales-profit-report.')->group(function () {
            Route::get('/', [SalesProfitReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesProfitReportController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesProfitReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-omset-report')->name('sales-omset-report.')->group(function () {
            Route::get('/', [SalesOmsetReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesOmsetReportController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesOmsetReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-void-report')->name('sales-void-report.')->group(function () {
            Route::get('/', [SalesVoidReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesVoidReportController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesVoidReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-commision-report')->name('sales-commision-report.')->group(function () {
            Route::get('/', [SalesCommisionReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesCommisionReportController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesCommisionReportController::class, 'show'])->name('show');
        });

        Route::prefix('invoice-payment-report')->name('invoice-payment-report.')->group(function () {
            Route::get('/', [InvoicePaymentReportController::class, 'index'])->name('index');
            Route::get('/table', [InvoicePaymentReportController::class, 'table'])->name('table');
            Route::get('/{id}', [InvoicePaymentReportController::class, 'show'])->name('show');
        });

        Route::prefix('profit-loss-report')->name('profit-loss-report.')->group(function () {
            Route::get('/', [ProfitLossReportController::class, 'index'])->name('index');
            Route::get('/table', [ProfitLossReportController::class, 'table'])->name('table');
            Route::get('/{id}', [ProfitLossReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-report')->name('sales-report.')->group(function () {
            Route::get('/', [SalesReportController::class, 'index'])->name('index');
            Route::get('/table', [SalesReportController::class, 'table'])->name('table');
        });

        Route::prefix('tanda-terima-penagihan')->name('tanda-terima-penagihan.')->group(function () {
            Route::get('/', [TandaTerimaPenagihanController::class, 'index'])->name('index');
            Route::get('/table', [TandaTerimaPenagihanController::class, 'table'])->name('table');
            Route::post('/', [TandaTerimaPenagihanController::class, 'store'])->name('store');
            Route::get('/{id}', [TandaTerimaPenagihanController::class, 'show'])->name('show');
            Route::put('/{id}', [TandaTerimaPenagihanController::class, 'update'])->name('update');
            Route::delete('/{id}', [TandaTerimaPenagihanController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-payment-list')->name('customer-payment-list.')->group(function () {
            Route::get('/', [CustomerPaymentListController::class, 'index'])->name('index');
            Route::get('/table', [CustomerPaymentListController::class, 'table'])->name('table');
            Route::post('/', [CustomerPaymentListController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerPaymentListController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerPaymentListController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerPaymentListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('cust-outstanding-list')->name('cust-outstanding-list.')->group(function () {
            Route::get('/', [CustOutstandingListController::class, 'index'])->name('index');
            Route::get('/table', [CustOutstandingListController::class, 'table'])->name('table');
            Route::get('/{id}', [CustOutstandingListController::class, 'show'])->name('show');
        });

        Route::prefix('daily-customer-payment-report')->name('daily-customer-payment-report.')->group(function () {
            Route::get('/', [DailyCustomerPaymentReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyCustomerPaymentReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailyCustomerPaymentReportController::class, 'show'])->name('show');
        });

        Route::prefix('outstanding-per-customer-report')->name('outstanding-per-customer-report.')->group(function () {
            Route::get('/', [OutstandingPerCustomerReportController::class, 'index'])->name('index');
            Route::get('/table', [OutstandingPerCustomerReportController::class, 'table'])->name('table');
            Route::get('/{id}', [OutstandingPerCustomerReportController::class, 'show'])->name('show');
        });

        Route::prefix('customer-payment-check')->name('customer-payment-check.')->group(function () {
            Route::get('/', [CustomerPaymentCheckController::class, 'index'])->name('index');
            Route::get('/table', [CustomerPaymentCheckController::class, 'table'])->name('table');
            Route::post('/', [CustomerPaymentCheckController::class, 'store'])->name('store');
            Route::get('/{id}', [CustomerPaymentCheckController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerPaymentCheckController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerPaymentCheckController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer-outstanding-per-date-report')->name('customer-outstanding-per-date-report.')->group(function () {
            Route::get('/', [CustomerOutstandingPerDateReportController::class, 'index'])->name('index');
            Route::get('/table', [CustomerOutstandingPerDateReportController::class, 'table'])->name('table');
            Route::get('/{id}', [CustomerOutstandingPerDateReportController::class, 'show'])->name('show');
        });

        Route::prefix('sales-return-list')->name('sales-return-list.')->group(function () {
            Route::get('/', [SalesReturnListController::class, 'index'])->name('index');
            Route::get('/table', [SalesReturnListController::class, 'table'])->name('table');
            Route::post('/', [SalesReturnListController::class, 'store'])->name('store');
            Route::get('/{id}', [SalesReturnListController::class, 'show'])->name('show');
            Route::put('/{id}', [SalesReturnListController::class, 'update'])->name('update');
            Route::delete('/{id}', [SalesReturnListController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('daily-sales-return-report')->name('daily-sales-return-report.')->group(function () {
            Route::get('/', [DailySalesReturnReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesReturnReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailySalesReturnReportController::class, 'show'])->name('show');
        });

        Route::prefix('tanda-terima-invoice')->name('tanda-terima-invoice.')->group(function () {
            Route::get('/', [TandaTerimaInvoiceController::class, 'index'])->name('index');
            Route::get('/table', [TandaTerimaInvoiceController::class, 'table'])->name('table');
            Route::post('/', [TandaTerimaInvoiceController::class, 'store'])->name('store');
            Route::get('/{id}', [TandaTerimaInvoiceController::class, 'show'])->name('show');
            Route::put('/{id}', [TandaTerimaInvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [TandaTerimaInvoiceController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('delivery-order')->name('delivery-order.')->group(function () {
            Route::get('/', [DeliveryOrderController::class, 'index'])->name('index');
            Route::get('/table', [DeliveryOrderController::class, 'table'])->name('table');
            Route::post('/', [DeliveryOrderController::class, 'store'])->name('store');
            Route::get('/{id}', [DeliveryOrderController::class, 'show'])->name('show');
            Route::put('/{id}', [DeliveryOrderController::class, 'update'])->name('update');
            Route::delete('/{id}', [DeliveryOrderController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('shipment-preparation')->name('shipment-preparation.')->group(function () {
            Route::get('/', [ShipmentPreparationController::class, 'index'])->name('index');
            Route::get('/table', [ShipmentPreparationController::class, 'table'])->name('table');
            Route::post('/', [ShipmentPreparationController::class, 'store'])->name('store');
            Route::get('/{id}', [ShipmentPreparationController::class, 'show'])->name('show');
            Route::put('/{id}', [ShipmentPreparationController::class, 'update'])->name('update');
            Route::delete('/{id}', [ShipmentPreparationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('purchase-note')->name('purchase-note.')->group(function () {
            Route::get('/', [PurchaseNoteController::class, 'index'])->name('index');
            Route::get('/table', [PurchaseNoteController::class, 'table'])->name('table');
            Route::post('/', [PurchaseNoteController::class, 'store'])->name('store');
            Route::get('/{id}', [PurchaseNoteController::class, 'show'])->name('show');
            Route::put('/{id}', [PurchaseNoteController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseNoteController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sales-commission')->name('sales-commission.')->group(function () {
            Route::get('/', [SalesCommissionController::class, 'index'])->name('index');
            Route::get('/table', [SalesCommissionController::class, 'table'])->name('table');
            Route::post('/', [SalesCommissionController::class, 'store'])->name('store');
            Route::get('/{id}', [SalesCommissionController::class, 'show'])->name('show');
            Route::put('/{id}', [SalesCommissionController::class, 'update'])->name('update');
            Route::delete('/{id}', [SalesCommissionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('tax')->name('tax.')->group(function () {
            Route::get('/', [TaxController::class, 'index'])->name('index');
            Route::get('/table', [TaxController::class, 'table'])->name('table');
            Route::post('/', [TaxController::class, 'store'])->name('store');
            Route::get('/{id}', [TaxController::class, 'show'])->name('show');
            Route::put('/{id}', [TaxController::class, 'update'])->name('update');
            Route::delete('/{id}', [TaxController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('daily-sales-invoice-report')->name('daily-sales-invoice-report.')->group(function () {
            Route::get('/', [DailySalesInvoiceReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesInvoiceReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailySalesInvoiceReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-sales-po-closing-report')->name('daily-sales-po-closing-report.')->group(function () {
            Route::get('/', [DailySalesPoClosingReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesPoClosingReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailySalesPoClosingReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-sales-by-brand-report')->name('daily-sales-by-brand-report.')->group(function () {
            Route::get('/', [DailySalesByBrandReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesByBrandReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailySalesByBrandReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-payment-recap-report')->name('daily-payment-recap-report.')->group(function () {
            Route::get('/', [DailyPaymentRecapReportController::class, 'index'])->name('index');
            Route::get('/table', [DailyPaymentRecapReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailyPaymentRecapReportController::class, 'show'])->name('show');
        });

        Route::prefix('cheque-management')->name('cheque-management.')->group(function () {
            Route::get('/', [ChequeManagementController::class, 'index'])->name('index');
            Route::get('/table', [ChequeManagementController::class, 'table'])->name('table');
            Route::post('/', [ChequeManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [ChequeManagementController::class, 'show'])->name('show');
            Route::put('/{id}', [ChequeManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [ChequeManagementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('rlhp-rincian-laporan-hasil-penagihan')->name('rlhp.')->group(function () {
            Route::get('/', [RlhpController::class, 'index'])->name('index');
            Route::get('/table', [RlhpController::class, 'table'])->name('table');
            Route::post('/', [RlhpController::class, 'store'])->name('store');
            Route::get('/{id}', [RlhpController::class, 'show'])->name('show');
            Route::put('/{id}', [RlhpController::class, 'update'])->name('update');
            Route::delete('/{id}', [RlhpController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ar-per-customer-report')->name('ar-per-customer-report.')->group(function () {
            Route::get('/', [ArPerCustomerReportController::class, 'index'])->name('index');
            Route::get('/table', [ArPerCustomerReportController::class, 'table'])->name('table');
            Route::get('/{id}', [ArPerCustomerReportController::class, 'show'])->name('show');
        });

        Route::prefix('customer-ar-position-report')->name('customer-ar-position-report.')->group(function () {
            Route::get('/', [CustomerArPositionReportController::class, 'index'])->name('index');
            Route::get('/table', [CustomerArPositionReportController::class, 'table'])->name('table');
            Route::get('/{id}', [CustomerArPositionReportController::class, 'show'])->name('show');
        });

        Route::prefix('invoice-customer-ar-list-report')->name('invoice-customer-ar-list-report.')->group(function () {
            Route::get('/', [InvoiceCustomerArListReportController::class, 'index'])->name('index');
            Route::get('/table', [InvoiceCustomerArListReportController::class, 'table'])->name('table');
            Route::get('/{id}', [InvoiceCustomerArListReportController::class, 'show'])->name('show');
        });

        Route::prefix('salesman-ar-list-pmb')->name('salesman-ar-list-pmb.')->group(function () {
            Route::get('/', [SalesmanArListPmbController::class, 'index'])->name('index');
            Route::get('/table', [SalesmanArListPmbController::class, 'table'])->name('table');
            Route::get('/{id}', [SalesmanArListPmbController::class, 'show'])->name('show');
        });

        Route::prefix('invoice-expedition')->name('invoice-expedition.')->group(function () {
            Route::get('/', [InvoiceExpeditionController::class, 'index'])->name('index');
            Route::get('/table', [InvoiceExpeditionController::class, 'table'])->name('table');
            Route::post('/', [InvoiceExpeditionController::class, 'store'])->name('store');
            Route::get('/{id}', [InvoiceExpeditionController::class, 'show'])->name('show');
            Route::put('/{id}', [InvoiceExpeditionController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceExpeditionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('shipping-invoice-expedition')->name('shipping-invoice-expedition.')->group(function () {
            Route::get('/', [ShippingInvoiceExpeditionController::class, 'index'])->name('index');
            Route::get('/table', [ShippingInvoiceExpeditionController::class, 'table'])->name('table');
            Route::post('/', [ShippingInvoiceExpeditionController::class, 'store'])->name('store');
            Route::get('/{id}', [ShippingInvoiceExpeditionController::class, 'show'])->name('show');
            Route::put('/{id}', [ShippingInvoiceExpeditionController::class, 'update'])->name('update');
            Route::delete('/{id}', [ShippingInvoiceExpeditionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('transit-area-target')->name('transit-area-target.')->group(function () {
            Route::get('/', [TransitAreaTargetController::class, 'index'])->name('index');
            Route::get('/table', [TransitAreaTargetController::class, 'table'])->name('table');
            Route::post('/', [TransitAreaTargetController::class, 'store'])->name('store');
            Route::get('/{id}', [TransitAreaTargetController::class, 'show'])->name('show');
            Route::put('/{id}', [TransitAreaTargetController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransitAreaTargetController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ubm-daily-control-progress-sales-report')->name('ubm-daily-control-progress-sales-report.')->group(function () {
            Route::get('/', [UbmDailyControlProgressSalesReportController::class, 'index'])->name('index');
            Route::get('/table', [UbmDailyControlProgressSalesReportController::class, 'table'])->name('table');
            Route::get('/{id}', [UbmDailyControlProgressSalesReportController::class, 'show'])->name('show');
        });

        Route::prefix('transit-area-new-brand')->name('transit-area-new-brand.')->group(function () {
            Route::get('/', [TransitAreaNewBrandController::class, 'index'])->name('index');
            Route::get('/table', [TransitAreaNewBrandController::class, 'table'])->name('table');
            Route::post('/', [TransitAreaNewBrandController::class, 'store'])->name('store');
            Route::get('/{id}', [TransitAreaNewBrandController::class, 'show'])->name('show');
            Route::put('/{id}', [TransitAreaNewBrandController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransitAreaNewBrandController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ubm-new-product-sales-report')->name('ubm-new-product-sales-report.')->group(function () {
            Route::get('/', [UbmNewProductSalesReportController::class, 'index'])->name('index');
            Route::get('/table', [UbmNewProductSalesReportController::class, 'table'])->name('table');
            Route::get('/{id}', [UbmNewProductSalesReportController::class, 'show'])->name('show');
        });

        Route::prefix('ubm-collection-progress-report')->name('ubm-collection-progress-report.')->group(function () {
            Route::get('/', [UbmCollectionProgressReportController::class, 'index'])->name('index');
            Route::get('/table', [UbmCollectionProgressReportController::class, 'table'])->name('table');
            Route::get('/{id}', [UbmCollectionProgressReportController::class, 'show'])->name('show');
        });

        Route::prefix('daily-sales-achievement-report')->name('daily-sales-achievement-report.')->group(function () {
            Route::get('/', [DailySalesAchievementReportController::class, 'index'])->name('index');
            Route::get('/table', [DailySalesAchievementReportController::class, 'table'])->name('table');
            Route::get('/{id}', [DailySalesAchievementReportController::class, 'show'])->name('show');
        });

        Route::prefix('pmb-penetapan-monitoring-bonus')->name('pmb.')->group(function () {
            Route::get('/', [PmbController::class, 'index'])->name('index');
            Route::get('/table', [PmbController::class, 'table'])->name('table');
            Route::post('/', [PmbController::class, 'store'])->name('store');
            Route::get('/{id}', [PmbController::class, 'show'])->name('show');
            Route::put('/{id}', [PmbController::class, 'update'])->name('update');
            Route::delete('/{id}', [PmbController::class, 'destroy'])->name('destroy');
        });

    });

    Route::get('/generator', [ScaffoldController::class, 'index'])->name('generator.index');
    Route::post('/generator', [ScaffoldController::class, 'store'])->name('generator.store');
    
    Route::get('/menu-search', [MenuSearchController::class, 'search'])->name('menu.search');

});

require __DIR__ . '/auth.php';