@if(isset($carriers) && count($carriers))
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Carrier</th>
      <th>Vehicle Type</th>
      <th>Service Area</th>
      <th class="text-end">Status</th>
      <th>Assign</th>
    </tr>
  </thead>
  <tbody>
    @foreach($carriers as $carrier)
    <tr>
      <td>{{ $carrier->user->name }}</td>
      <td>{{ $carrier->vehicle_type }}</td>
      <td>{{ $carrier->service_areas }}</td>
      <td class="text-end">
        @if($carrier->is_busy ?? false)
          <span class="badge bg-danger">Busy</span>
        @else
          <span class="badge bg-success">Free</span>
        @endif
      </td>
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
