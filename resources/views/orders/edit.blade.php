@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Edit Order') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('orders.update', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <!-- Populate with customers, select current -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address_id" class="form-label">Address</label>
                        <select name="address_id" id="address_id" class="form-control">
                            <!-- Populate with addresses, select current -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="items" class="form-label">Order Items</label>
                        <!-- Add JS or server-side logic to edit multiple items -->
                        <input type="text" name="items[]" class="form-control" value="" placeholder="Product ID, Quantity">
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{ $order->total }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 