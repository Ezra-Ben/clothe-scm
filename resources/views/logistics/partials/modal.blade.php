<div class="modal fade" id="assignCarrierModal" tabindex="-1" aria-labelledby="assignCarrierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="GET" action="{{ $assignCarrierAction }}">
        <div class="modal-header">
          <h5 class="modal-title">Filter Carriers</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <input type="text" id="service_area" name="service_area" class="form-control" placeholder="Service Area">
            </div>
            <div class="col-md-4">
              <input type="text" id="vehicle_type" name="vehicle_type" class="form-control" placeholder="Vehicle Type">
            </div>
            <div class="col-md-4">
              <input type="number" id="required_quantity" name="required_quantity" class="form-control" placeholder="Required Quantity (optional)">
            </div>
          </div>

          <div id="carrier-table-wrapper">
            @include('logistics.partials.carrier_table', [
                'carriers' => $carriers,
                'assignCarrierPostRoute' => $assignCarrierPostRoute
            ])
          </div>

          <script>
            window.carrierFilterShipmentId = {{ $shipment->id }};
          </script>
          <script src="{{ asset('js/carrier-filter.js') }}"></script>
        </div>
      </form>
    </div>
  </div>
</div>
