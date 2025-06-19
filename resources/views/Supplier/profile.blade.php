{{-- filepath: resources/views/supplier/profile.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Supplier Profile</h2>
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
    <div class="card mb-4">
        <div class="card-header">
            <strong>Supplier Information</strong>
        </div>
        <div class="card-body">
            <p><strong>Address:</strong> {{ $supplier->address }}</p>
            @if(isset($supplier->profile_photo_url))
                <img src="{{ $supplier->profile_photo_url }}" alt="Profile Photo" class="img-thumbnail" style="max-inline-size: 200px;">
            @else
                <p><em>No profile photo available.</em></p>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Basic Info</strong>
        </div>
        <div class="card-body">
            <p><strong>Added By (User ID):</strong> {{ $supplier->added_by }}</p>
            <p><strong>Created At:</strong> {{ $supplier->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Last Updated:</strong> {{ $supplier->updated_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
</div>
@endsection