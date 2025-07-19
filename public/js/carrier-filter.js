document.addEventListener('DOMContentLoaded', function() {
  const inputs = ['service_area', 'vehicle_type', 'required_quantity'];
  inputs.forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', filterCarriers);
    }
  });

  function filterCarriers() {
    const service_area = document.getElementById('service_area').value;
    const vehicle_type = document.getElementById('vehicle_type').value;
    const required_quantity = document.getElementById('required_quantity').value;
    const shipmentId = window.carrierFilterShipmentId || null;
    if (!shipmentId) return;

    fetch(`/logistics/outbound/${shipmentId}/filter-carriers?service_area=${encodeURIComponent(service_area)}&vehicle_type=${encodeURIComponent(vehicle_type)}&required_quantity=${encodeURIComponent(required_quantity)}`)
      .then(response => response.text())
      .then(html => {
        document.getElementById('carrier-table-wrapper').innerHTML = html;
      });
  }
});
