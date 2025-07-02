@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-plus-circle"></i> {{ __('Place New Order') }}
    </h2>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm mx-auto" style="max-width: 700px;">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <table class="table table-bordered align-middle mb-0">
                        <tr>
                            <th style="width: 30%;">Customer</th>
                            <td>
                                <select name="customer_id" id="customer_id" class="form-control">
                                    <!-- @foreach($customers as $customer) -->
                                    <!-- <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option> -->
                                    <!-- @endforeach -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>
                                <select name="address_id" id="address_id" class="form-control">
                                    <!-- @foreach($addresses as $address) -->
                                    <!-- <option value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>{{ $address->address_line1 }}</option> -->
                                    <!-- @endforeach -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Order Items</th>
                            <td>
                                <!-- Add JS or server-side logic to add multiple items -->
                                <input type="text" name="items[]" class="form-control mb-2" placeholder="Product ID, Quantity" value="{{ old('items.0') }}">
                                <!-- Add more item rows as needed -->
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>
                                <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{ old('total') }}">
                            </td>
                        </tr>
                    </table>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 