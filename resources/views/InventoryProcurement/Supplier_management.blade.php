{{-- filepath: resources/views/suppliers/index.blade.php --}}
<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Suppliers</h2>
        <table class="table table-bordered bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Last Supplied</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->vendor->name ?? $supplier->name }}</td>
                    <td>
                        <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $supplier->last_supplied_at ? $supplier->last_supplied_at->format('Y-m-d') : 'Never' }}</td>
                    <td>
                        @can('manage-suppliers')
                            @if(!$supplier->is_active)
                                <form method="POST" action="{{ route('suppliers.activate', $supplier->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Activate</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('suppliers.deactivate', $supplier->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Deactivate</button>
                                </form>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>