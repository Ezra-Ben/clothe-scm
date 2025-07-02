<x-app-layout>
    <div class="container mt-4">
        <h2>Order Requests</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderRequests as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->product->name ?? '-' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <a href="{{ route('inventory.order.requests.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>