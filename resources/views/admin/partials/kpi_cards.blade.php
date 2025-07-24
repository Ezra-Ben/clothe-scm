<div class="kpi-wrapper">
  <h5 class="kpi-title mb-3">
    <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>
    System KPIs
  </h5>

  <div class="kpi-item">
    <strong>Total Active Customers:</strong>
    <div class="kpi-icon-value">
      <i class="bi bi-people-fill text-primary"></i>
      <span>{{ $metrics['active_customers'] }}</span>
    </div>
  </div>

  <div class="kpi-item">
    <strong>Suppliers Registered:</strong>
    <div class="kpi-icon-value">
      <i class="bi bi-truck text-success"></i>
      <span>{{ $metrics['registered_suppliers'] }}</span>
    </div>
  </div>

  <div class="kpi-item">
    <strong>Pending Procurement Requests:</strong>
    <div class="kpi-icon-value">
      <i class="bi bi-hourglass-split text-warning"></i>
      <span>{{ $metrics['pending_procurements'] }}</span>
    </div>
  </div>

  <div class="kpi-item">
    <strong>Orders This Month:</strong>
    <div class="kpi-icon-value">
      <i class="bi bi-cart-check-fill text-info"></i>
      <span>{{ $metrics['orders_this_month'] }}</span>
    </div>
  </div>

  <div class="kpi-item">
    <strong>Current Inventory Value:</strong>
    <div class="kpi-icon-value">
      <span>UGX {{ number_format($metrics['inventory_value'], 2) }}</span>
    </div>
  </div>

  <div class="kpi-item">
    <strong>Low Stock Items:</strong>
    <div class="kpi-icon-value">
      <i class="bi bi-exclamation-circle-fill text-danger"></i>
      <span>{{ $metrics['low_stock_items'] }}</span>
    </div>
   </div>

   
    <div class="kpi-item">
      <strong>Employees:</strong>
      <div class="kpi-icon-value">
        <i class="bi bi-people-fill text-primary"></i>
        <span>{{ $metrics['employee_count'] }}</span>
      </div>
    </div>
</div>
