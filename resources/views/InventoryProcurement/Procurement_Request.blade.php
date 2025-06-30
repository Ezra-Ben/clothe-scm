{{-- filepath: resources/views/procurement/requests/index.blade.php --}}
<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Procurement Requests</h2>
        <table class="table table-bordered bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Product</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->product->name }}</td>
                    <td>{{ $request->supplier->vendor->name ?? $request->supplier->name }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>
                        <span class="badge 
                            @if($request->status === 'pending') bg-warning
                            @elseif($request->status === 'approved') bg-success
                            @elseif($request->status === 'rejected') bg-danger
                            @else bg-secondary @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @can('approve-procurement')
                            @if($request->status === 'pending')
                                <form method="POST" action="{{ route('procurement.approve', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('procurement.reject', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">No action</span>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>