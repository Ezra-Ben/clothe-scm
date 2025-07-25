<?php

use App\Http\Controllers\Procurement\ProcurementRequestController;
use App\Http\Controllers\Procurement\ProcurementReplyController;
use App\Http\Controllers\Production\ProductionOrderController;
use App\Http\Controllers\Production\CapacityPlanningController;
use App\Http\Controllers\Production\ProductionBatchController;
use App\Http\Controllers\Production\QualityControlController;
use App\Http\Controllers\Production\ResourceController;
use App\Http\Controllers\Production\BomItemController;
use App\Http\Controllers\Production\ReportController;
use App\Http\Controllers\Production\BomController;
use App\Http\Controllers\Production\ScheduleController;
use App\Http\Controllers\Logistics\OutboundShipmentController;
use App\Http\Controllers\Logistics\InboundShipmentController;
use App\Http\Controllers\Logistics\LogisticsController;
use App\Http\Controllers\Logistics\CarrierController;
use App\Http\Controllers\Logistics\PodController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Supplier\PerformanceController;
use App\Http\Controllers\Supplier\ContractController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\HRController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Workforce\WorkforceController;
use App\Http\Controllers\Workforce\TaskController;
use App\Http\Controllers\Carrier\DashboardController as CarrierDashboardController;
use App\Http\Controllers\Inventory\RawMaterialController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CheckoutController;
use App\Http\Controllers\Product\CartController;
use App\Http\Controllers\Order\PaymentController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Auth\RoleSelectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Conversation;

// Public routes (no login required)

Route::view('/welcome', 'welcome')->name('welcome');
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/select-role', [RoleSelectionController::class, 'show'])->name('select.role');
Route::post('/select-role', [RoleSelectionController::class, 'store'])->name('select.role.store');
Route::get('/employees/set-password', [EmployeeController::class, 'setPassword'])->name('employees.set_password');
Route::post('/employees/finalize-registration', [EmployeeController::class, 'finalizeRegistration'])->name('employees.finalize_registration');


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

    Route::middleware(['can:admin'])->group(function (){
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

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
    Route::post('notifications/{notification}/mark-read', function($notificationId) {
        $notification = auth()->user()->unreadNotifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'read']);
        }
        return response()->json(['status' => 'not found'], 404);
        })->name('notifications.markRead');
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
        Route::resource('boms', BomController::class);
        Route::resource('resources', ResourceController::class);
        Route::resource('boms.items', BomItemController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('quality_control', QualityControlController::class);
        Route::resource('production_batches', ProductionBatchController::class);
        Route::get('/production-orders', [ProductionOrderController::class, 'index'])->name('production_orders.index');
        Route::get('/production/report', [ProductionOrderController::class, 'report'])->name('production.report');
        Route::get('/production-orders/create', [ProductionOrderController::class, 'create'])->name('production_orders.create');
        Route::post('/production-orders', [ProductionOrderController::class, 'store'])->name('production_orders.store');
        Route::get('/production-orders/{id}', [ProductionOrderController::class, 'show'])->name('production_orders.show');
        Route::post('/production-orders/{id}/complete', [ProductionOrderController::class, 'complete'])->name('production_orders.complete');
        Route::get('reports/resource-utilization', [ReportController::class, 'resourceUtilization'])->name('reports.resource_utilization');
        Route::get('capacity-planning', [CapacityPlanningController::class, 'index'])->name('capacity_planning.index');
        Route::post('capacity-planning/assign', [CapacityPlanningController::class, 'assign'])->name('capacity_planning.assign');
        Route::post('/capacity/update-assignment', [CapacityPlanningController::class, 'updateAssignment'])->name('capacity_planning.updateAssignment');

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

    Route::resource('employees', EmployeeController::class);
    Route::get('/employee/dashboard', [DashboardController::class, 'index'])->name('employee.dashboard');
    Route::get('/employee/tasks/{task}', [DashboardController::class, 'show'])->name('employee.task.show');
    Route::patch('/employee/allocations/{allocation}', [DashboardController::class, 'updateStatus'])->name('employee.task.update');

    Route::resource('tasks', TaskController::class);
    Route::get('/hr/dashboard', [HRController::class, 'dashboard'])->name('hr.dashboard');
    Route::get('/workforce/assign/{task}', [WorkforceController::class, 'assignView'])->name('workforce.assign.view');
    Route::post('/workforce/assign/employee', [WorkforceController::class, 'assign'])->name('workforce.assign');
    Route::get('/workforce/dashboard', [WorkforceController::class, 'dashboard'])->name('workforce.dashboard');

    // Chat & Messaging
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.startConversation');
    Route::get('/conversations/{id}/info', function ($id) {
    $conversation = Conversation::with(['userOne', 'userTwo'])->findOrFail($id);

    $userId = auth()->id();
    if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
        abort(403);
    }

    return response()->json($conversation);
    });

});

require __DIR__.'/auth.php';