<!-- resources/views/supplier/contracts/show.blade.php -->

@extends('layouts.app') <!-- if your app uses a layout -->

@section('content')
<div class="container">
    <h2>Contract Details</h2>

    <p><strong>Supplier ID:</strong> {{ $contract->supplier_id }}</p>
    <p><strong>Status:</strong> {{ $contract->status }}</p>
    <p><strong>Uploaded At:</strong> {{ $contract->uploaded_at }}</p>
    <p><strong>File:</strong> 
        <a href="{{ asset('storage/' . $contract->file_url) }}" target="_blank">Download/View File</a>
    </p>

    <a href="{{ url()->previous() }}">Back</a>
</div>
@endsection
