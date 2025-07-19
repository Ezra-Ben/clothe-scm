@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Production Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('production_orders.create') }}" class="btn btn-primary mb-3">
        Start New Production Order
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Order ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($productionOrders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->order_id ?? '-' }}</td>
                <td>
                    <a href="{{ route('production_orders.show', $order->id) }}" class="btn btn-sm btn-info">
                        View
                    </a>

                    @if($order->status !== 'completed')
                        <form action="{{ route('production_orders.complete', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Mark Complete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
