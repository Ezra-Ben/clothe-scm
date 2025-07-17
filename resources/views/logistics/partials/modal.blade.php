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
              <input type="text" name="service_area" class="form-control" placeholder="Service Area" value="{{ request('service_area') }}">
            </div>
            <div class="col-md-4">
              <input type="text" name="vehicle_type" class="form-control" placeholder="Vehicle Type" value="{{ request('vehicle_type') }}">
            </div>
            <div class="col-md-4">
              <input type="number" name="required_quantity" class="form-control" placeholder="Required Quantity (optional)" value="{{ request('required_quantity') }}">
            </div>
          </div>

          @if(isset($carriers) && count($carriers))
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Carrier</th>
                <th>Vehicle Type</th>
                <th>Service Area</th>
                <th>Status</th>
                <th>Assign</th>
              </tr>
            </thead>
            <tbody>
              @foreach($carriers as $carrier)
              <tr>
                <td>{{ $carrier->name }}</td>
                <td>{{ $carrier->vehicle_type }}</td>
                <td>{{ $carrier->service_areas }}</td>
                <td><span class="badge bg-success">{{ ucfirst($carrier->status) }}</span></td>
                <td>
                  <form method="POST" action="{{ $assignCarrierPostRoute($carrier) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
            <p class="text-muted">No carriers matched your filters.</p>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>
