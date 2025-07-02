<x-app-layout>
    <div class="container mt-4">
        <h2>Order Request #{{ $order->id }}</h2>
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Product:</strong> {{ $order->product->name ?? '-' }}</li>
            <li class="list-group-item"><strong>Quantity:</strong> {{ $order->quantity }}</li>
            <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
        </ul>
        <div class="alert alert-info">
            {{ $message }}
        </div>
        <a href="{{ route('inventory.order.requests') }}" class="btn btn-secondary">Back to List</a>
    </div>
</x-app-layout>