@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Procurement Requests</h2>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
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
                        @can('manage-inventory')
                            @if($request->status === 'pending')
                                <form method="POST" action="{{ route('procurement.approve', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('procurement.reject', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                                @can('manage-inventory')
                                    @if($request->status === 'delivery_accepted')
                                     <form method="POST" action="{{ route('admin.procurement.confirmDelivery', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Confirm Delivery</button>
                               </form>
                                 @endif
@endcan
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
@endsection