{{-- filepath: resources/views/supplier/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Supplier Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Vendor Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $supplier->vendor->name }}</p>
                    <p><strong>Contact:</strong> {{ $supplier->vendor->contact }}</p>
                    <p><strong>Registration Number:</strong> {{ $supplier->vendor->registration_number }}</p>
                    <p><strong>Product Bulk:</strong> {{ $supplier->vendor->product_bulk }}</p>
                    <p><strong>Product Category:</strong> {{ $supplier->vendor->product_category }}</p>
                    <p><strong>Business License:</strong> 
                        <a href="{{ $supplier->vendor->business_license_url }}" target="_blank">View License</a>
                    </p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Supplier Information</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('supplier.update', $supplier->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="address" class="form-label"><strong>Address</strong></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $supplier->address }}">
                        </div>
                        {{-- Add profile photo upload if needed --}}
                        <button type="submit" class="btn btn-primary">Update Address</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Statistics</strong>
                </div>
                <div class="card-body">
                    <p><strong>Total Contracts:</strong> {{ $supplier->totalContracts ?? $supplier->contracts->count() }}</p>
                    <p><strong>Average Performance Rating:</strong> {{ number_format($supplier->averageRating ?? $supplier->performanceRecords->avg('rating'), 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Contracts</strong>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Status</th>
                                <th>Uploaded By</th>
                                <th>Uploaded At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->contracts as $contract)
                                <tr>
                                    <td><a href="{{ $contract->file_url }}" target="_blank">View</a></td>
                                    <td>{{ ucfirst($contract->status) }}</td>
                                    <td>{{ $contract->uploaded_by }}</td>
                                    <td>{{ $contract->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Performance Records</strong>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Note</th>
                                <th>Rating</th>
                                <th>Created By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->performanceRecords as $record)
                                <tr>
                                    <td>{{ $record->performance_note }}</td>
                                    <td>{{ $record->rating }}</td>
                                    <td>{{ $record->created_by }}</td>
                                    <td>{{ $record->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection