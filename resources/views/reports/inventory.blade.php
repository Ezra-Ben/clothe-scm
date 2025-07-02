@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Inventory Analytics') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Total Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->items->sum('quantity') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 