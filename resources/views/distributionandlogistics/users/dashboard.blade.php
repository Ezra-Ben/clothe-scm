@extends('layouts.app')

@section('title', 'Your Deliveries')

@section('content')
<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Delivery Tracking</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Carrier</th>
                            <th>Tracking #</th>
                            <th>Service Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->order_id }}</td>
                            <td>{{ $delivery->carrier->name }}</td>
                            <td><code>{{ $delivery->tracking_number }}</code></td>
                            <td><span class="badge bg-info text-dark">{{ Str::title($delivery->service_level) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $delivery->status_color }}">{{ Str::title($delivery->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('distributionandlogistics.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Track
                                </a>
                                @if(in_array($delivery->status, ['pending', 'processing']))
                                <a href="{{ route('distributionandlogistics.deliveries.change.address.form', $delivery) }}" class="btn btn-sm btn-outline-warning">
                                    Change Address
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No deliveries found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
