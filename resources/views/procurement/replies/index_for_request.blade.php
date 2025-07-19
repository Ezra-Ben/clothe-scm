@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Replies for Request #{{ $procurementRequest->id }}</h1>
    <a href="{{ route('procurement.requests.show', $procurementRequest->id) }}" class="btn btn-outline-primary">
        Back to Request
    </a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Confirmed Qty</th>
            <th>Status</th>
            <th>Expected Delivery</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($replies as $reply)
        <tr>
            <td>{{ $reply->supplier->vendor->user->name }}</td>
            <td>{{ $reply->quantity_confirmed }}</td>
            <td>
                <span class="badge bg-{{ 
                    $reply->status == 'confirmed' ? 'success' : 
                    ($reply->status == 'shipped' ? 'info' : 
                    ($reply->status == 'delivered' ? 'primary' :
                    ($reply->status == 'rejected' ? 'danger' : 'secondary')))
                }}">
                    {{ ucfirst($reply->status) }}
                </span>
            </td>
            <td>{{ $reply->expected_delivery_date ?: '-' }}</td>
            <td>
                <a href="{{ route('procurement.replies.show', $reply->id) }}" class="btn btn-sm btn-info">Open</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
