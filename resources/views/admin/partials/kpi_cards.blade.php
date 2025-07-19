<div class="card mb-3 bg-light">
    <div class="card-body">
        <h5 class="card-title">📊 System KPIs</h5>
        <ul class="list-group list-group-flush">

            <li class="list-group-item">
                <i class="bi bi-people-fill text-primary me-2"></i>
                <strong>Total Active Customers:</strong>
                {{ $metrics['active_customers'] }}
            </li>

            <li class="list-group-item">
                <i class="bi bi-truck text-success me-2"></i>
                <strong>Suppliers Registered:</strong>
                {{ $metrics['registered_suppliers'] }}
            </li>

            <li class="list-group-item">
                <i class="bi bi-hourglass-split text-warning me-2"></i>
                <strong>Pending Procurement Requests:</strong>
                {{ $metrics['pending_procurements'] }}
            </li>

            <li class="list-group-item">
                <i class="bi bi-cart-check-fill text-info me-2"></i>
                <strong>Orders This Month:</strong>
                {{ $metrics['orders_this_month'] }}
            </li>

            <li class="list-group-item">
                <i class="bi bi-currency-dollar text-secondary me-2"></i>
                <strong>Current Inventory Value:</strong>
                ${{ number_format($metrics['inventory_value'], 2) }}
            </li>

            <li class="list-group-item">
                <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>
                <strong>Low Stock Items:</strong>
                {{ $metrics['low_stock_items'] }}
            </li>

        </ul>
    </div>
</div>
