<?php

use App\Http\Controllers\Procurement\ProcurementRequestController;
use App\Http\Controllers\Procurement\ProcurementReplyController;
use App\Http\Controllers\Production\ProductionOrderController;
use App\Http\Controllers\Production\ProductionBatchController;
use App\Http\Controllers\Production\QualityControlController;
use App\Http\Controllers\Logistics\OutboundShipmentController;
use App\Http\Controllers\Logistics\InboundShipmentController;
use App\Http\Controllers\Logistics\LogisticsController;
use App\Http\Controllers\Logistics\CarrierController;
use App\Http\Controllers\Logistics\PodController;
use App\Http\Controllers\Logistics\DashboardController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Carrier\DashboardController as CarrierDashboardController;
use App\Http\Controllers\Inventory\RawMaterialController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CheckoutController;
use App\Http\Controllers\Product\CartController;
use App\Http\Controllers\Order\PaymentController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RoleSelectionController;

// Public routes (no login required)

Route::get('/select-role', [RoleSelectionController::class, 'show'])->name('select.role');
Route::post('/select-role', [RoleSelectionController::class, 'store'])->name('select.role.store');
Route::view('/welcome', 'welcome')->name('welcome');
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');

    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::middleware(['can:manage-products'])->group(function () {
        Route::get('admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
        Route::get('admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('admin/products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    });

    //Order Routes
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pay/{order}', [PaymentController::class, 'redirectToGateway'])->name('pay');
    Route::get('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    //Inventory Routes
    Route::middleware(['can:manage-inventory'])->group(function () {
        Route::resource('inventory', InventoryController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::resource('raw-materials', RawMaterialController::class);

    });

    //Procurement Routes - Accessible to both suppliers and procurement managers
    Route::get('procurement/requests', [ProcurementRequestController::class, 'index'])->name('procurement.requests.index');
    
    //Procurement Routes - Admin/Manager only (moved specific routes first)
    Route::middleware(['can:manage-procurement'])->group(function () {
        Route::get('procurement/requests/create', [ProcurementRequestController::class, 'create'])->name('procurement.requests.create');
        Route::post('procurement/requests/', [ProcurementRequestController::class, 'store'])->name('procurement.requests.store');
        Route::get('procurement/requests/{procurementRequest}/edit', [ProcurementRequestController::class, 'edit'])->name('procurement.requests.edit');
        Route::put('procurement/requests/{procurementRequest}', [ProcurementRequestController::class, 'update'])->name('procurement.requests.update');
        Route::delete('procurement/requests/{procurementRequest}', [ProcurementRequestController::class, 'destroy'])->name('procurement.requests.destroy');
        Route::get('procurement/requests/{procurementRequest}/replies', [ProcurementReplyController::class, 'indexForRequest'])->name('procurement.replies.indexForRequest');
        Route::post('/procurement/replies/{reply}/accept-delivery', [ProcurementReplyController::class, 'acceptDelivery'])->name('procurement.replies.acceptDelivery');
        Route::post('/procurement/replies/{reply}/reject-delivery', [ProcurementReplyController::class, 'rejectDelivery'])->name('procurement.replies.rejectDelivery');
    });
    
    //parameterized routes after specific ones
    Route::get('procurement/requests/{procurementRequest}', [ProcurementRequestController::class, 'show'])->name('procurement.requests.show');
    Route::get('procurement/replies/{replyId}', [ProcurementReplyController::class, 'show'])->name('procurement.replies.show');
    
    // Notification routes
    Route::post('notifications/{notification}/mark-read', function($notificationId) {auth()->user()->unreadNotifications()->where('id', $notificationId)->first()?->markAsRead();return back();})->name('notifications.markRead');
    Route::post('notifications/mark-all-read', function() {auth()->user()->unreadNotifications->markAsRead();return back()->with('success', 'All notifications marked as read.');})->name('notifications.markAllRead');

    Route::middleware(['can:supplier'])->group(function () {
        Route::get('procurement/replies', [ProcurementReplyController::class, 'index'])->name('procurement.replies.index');
        Route::get('procurement/replies/create/{requestId}', [ProcurementReplyController::class, 'create'])->name('procurement.replies.create');
        Route::post('procurement/replies/store/{requestId}', [ProcurementReplyController::class, 'store'])->name('procurement.replies.store');
        Route::get('procurement/replies/edit/{replyId}', [ProcurementReplyController::class, 'edit'])->name('procurement.replies.edit');
        Route::put('procurement/replies/update/{replyId}', [ProcurementReplyController::class, 'update'])->name('procurement.replies.update');
        Route::delete('procurement/replies/{replyId}', [ProcurementReplyController::class, 'destroy'])->name('procurement.replies.destroy');
        Route::post('/procurement/replies/{reply}/mark-delivered', [ProcurementReplyController::class, 'markDelivered'])->name('procurement.replies.markDelivered');
        Route::post('procurement/requests/{procurementRequest}/accept', [ProcurementRequestController::class, 'accept'])->name('procurement.requests.accept');
        Route::post('procurement/requests/{procurementRequest}/reject', [ProcurementRequestController::class, 'reject'])->name('procurement.requests.reject');
    });
    // Production Routes
    Route::middleware(['can:manage-production'])->group(function () {
        Route::get('/production-orders', [ProductionOrderController::class, 'index'])->name('production_orders.index');
        Route::get('/production-orders/create', [ProductionOrderController::class, 'create'])->name('production_orders.create');
        Route::post('/production-orders', [ProductionOrderController::class, 'store'])->name('production_orders.store');
        Route::get('/production-orders/{id}', [ProductionOrderController::class, 'show'])->name('production_orders.show');
        Route::post('/production-orders/{id}/complete', [ProductionOrderController::class, 'complete'])->name('production_orders.complete');
        Route::resource('production_batches', ProductionBatchController::class);
        Route::resource('quality_control', QualityControlController::class);

    });

    // Supplier  Routes
    Route::middleware(['can:supplier'])->group(function () {
        Route::get('/supplier/profile', [SupplierController::class, 'profile'])->name('supplier.profile');
        Route::patch('/supplier/update', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/supplier/dashboard', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');
        Route::get('/supplier/performance', [PerformanceController::class, 'index'])->name('supplier.performance');
        Route::get('/supplier/contracts', [ContractController::class, 'index'])->name('supplier.contracts.index');
	Route::get('/supplier/contracts/{contractId}', [ContractController::class, 'show'])->name('supplier.contracts.show');
    });


    Route::middleware(['can:manage-suppliers'])->group(function () {
	Route::get('/supplier/select', [AdminSupplierController::class, 'select'])->name('admin.select.supplier');
  	Route::get('/manage/supplier/contracts', [ContractController::class, 'index'])->name('manage.supplier.contracts.index');
	Route::get('/supplier/{id}/contracts/create', [ContractController::class, 'create'])->name('manage.supplier.contracts.create');
    Route::get('/supplier/{contractId}/contracts/{id}', [ContractController::class, 'show'])->name('manage.supplier.contracts.show');
	Route::post('/supplier/{id}/contracts', [ContractController::class, 'store'])->name('manage.supplier.contracts.store');
 	Route::put('/supplier/{id}/contracts/{contractId}', [ContractController::class, 'update'])->name('manage.supplier.contracts.update');
    Route::get('/supplier/{id}/performance', [PerformanceController::class, 'index'])->name('manage.supplier.performance');
 	Route::post('/supplier/{id}/performance', [PerformanceController::class, 'store'])->name('manage.supplier.performance.store');
    });


    Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
    Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');


    // Logistics Routes
   
        Route::resource('pods', PodController::class);
        Route::resource('carriers', CarrierController::class);
        Route::resource('logistics/inbound', InboundShipmentController::class);
        Route::resource('logistics/outbound', OutboundShipmentController::class);
        Route::get('/logistics/dashboard', [LogisticsController::class, 'index'])->name('logistics.dashboard');
        Route::post('logistics/outbound/{shipment}/assign-carrier/{carrier}', [OutboundShipmentController::class, 'assignCarrier'])->name('logistics.outbound.assignCarrier');
        Route::post('logistics/inbound/{inboundShipment}/assign-carrier/{carrier}', [InboundShipmentController::class, 'assignCarrier'])->name('logistics.inbound.assignCarrier');
        Route::patch('logistics/inbound/{shipment}/update-status', [InboundShipmentController::class, 'updateStatus'])->name('logistics.inbound.updateStatus');
        Route::patch('logistics/outbound/{shipment}/update-status', [OutboundShipmentController::class, 'updateStatus'])->name('logistics.outbound.updateStatus');

        Route::get('logistics/outbound/{shipment}/filter-carriers', [OutboundShipmentController::class, 'filterCarriers'])->name('logistics.outbound.filterCarriers');
   
    Route::get('carrier/dashboard', [CarrierDashboardController::class, 'index'])->name('carrier.dashboard');
});

require __DIR__.'/auth.php';