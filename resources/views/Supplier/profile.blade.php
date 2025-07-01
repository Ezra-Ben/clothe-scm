{{-- filepath: resources/views/supplier/profile.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
@if (session('vendor_validated'))
    <div class="alert alert-success">
        {{ session('vendor_validated') }}
    </div>
@endif

@if (session('address_updated'))
    <div class="alert alert-success">
        {{ session('address_updated') }}
    </div>
@endif
<h2>Supplier Profile</h2>

<form method="POST" action="{{ route('supplier.update') }}">
    @csrf
    @method('PATCH')

    {{-- Vendor Info: readonly --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Vendor Information</strong>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $supplier->vendor->name }}</p>
            <p><strong>Contact:</strong> {{ $supplier->vendor->contact }}</p>
            <p><strong>Registration Number:</strong> {{ $supplier->vendor->registration_number }}</p>
        </div>
    </div>

    {{-- Editable Supplier Info --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Supplier Information</strong>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="address" class="form-label"><strong>Address:</strong></label>
                <textarea name="address" id="address" class="form-control">{{ old('address', $supplier->address) }}</textarea>
            </div>

            @if(isset($supplier->profile_photo_url))
                <img src="{{ $supplier->profile_photo_url }}" alt="Profile Photo" class="img-thumbnail" style="max-inline-size: 200px;">
            @else
                <p><em>No profile photo available.</em></p>
            @endif
        </div>
    </div>

    {{-- Basic Info --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Basic Info</strong>
        </div>
        <div class="card-body">
            <p><strong>Added By:</strong> {{ $supplier->addedBy->name ?? 'Unknown' }}</p>
            <p><strong>Created At:</strong> {{ $supplier->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Last Updated:</strong> {{ $supplier->updated_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    @if (session('address_updated') || request('updated') == '1')
    <a href="{{ route('supplier.dashboard') }}" class="btn btn-success">
        Go to Dashboard
    </a>
    @else
    <button type="submit" class="btn btn-primary">
        Save Changes
    </button>
    @endif
</form>
</div>
@endsection
