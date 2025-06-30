{{-- filepath: resources/views/inventory/index.blade.php --}}
<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Inventory Overview</h2>
        <table class="table table-bordered bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->product->name }}</td>
                    <td>
                        <span class="badge {{ $inventory->product->type === 'raw' ? 'bg-info' : 'bg-primary' }}">
                            {{ ucfirst($inventory->product->type) }}
                        </span>
                    </td>
                    <td>{{ $inventory->product->sku }}</td>
                    <td>{{ $inventory->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>