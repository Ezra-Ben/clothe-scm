<div class="card shadow-sm mb-4">
  <div class="card-header">
    <h5>📋 Recent Activity Feed</h5>
  </div>
  <ul class="list-group list-group-flush">
    @forelse ($recentSuppliers as $name => $count)
      <li class="list-group-item">
        Supplier <strong>{{ $name }}</strong> submitted <strong>{{ $count }}</strong> delivery{{ $count > 1 ? 'ies' : 'y' }} this week.
      </li>
    @empty
      <li class="list-group-item text-muted">No shipments submitted by suppliers this week.</li>
    @endforelse


    @forelse ($approvedRequests as $req)
      <li class="list-group-item">
        {{ $req }}
      </li>
    @empty
      <li class="list-group-item text-muted">No procurement approvals this month.</li>
    @endforelse
  </ul>
</div>
