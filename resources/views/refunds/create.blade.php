@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-plus-circle"></i> {{ __('Create Refund') }}
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
        <div class="card shadow-sm mx-auto" style="max-width: 500px;">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('refunds.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Order</label>
                        <select name="order_id" id="order_id" class="form-control">
                            <!-- @foreach($orders as $order) -->
                            <!-- <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>{{ $order->id }}</option> -->
                            <!-- @endforeach -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_id" class="form-label">Payment</label>
                        <select name="payment_id" id="payment_id" class="form-control">
                            <!-- @foreach($payments as $payment) -->
                            <!-- <option value="{{ $payment->id }}" {{ old('payment_id') == $payment->id ? 'selected' : '' }}>{{ $payment->id }}</option> -->
                            <!-- @endforeach -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <!-- @foreach($customers as $customer) -->
                            <!-- <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option> -->
                            <!-- @endforeach -->
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}">
                        </div>
                        <div class="col">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" name="status" id="status" class="form-control" value="{{ old('status') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason') }}">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Create Refund
                        </button>
                        <a href="{{ route('refunds.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 