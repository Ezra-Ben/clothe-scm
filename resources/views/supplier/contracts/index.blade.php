<!-- resources/views/supplier/contracts/index.blade.php -->

@extends('layouts.app') <!-- if your app uses a layout -->

@section('content')
<div class="container">
    <h2>Supplier Contracts</h2>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier ID</th>
                <th>File</th>
                <th>Status</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contracts as $contract)
                <tr>
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->supplier_id }}</td>
                    <td><a href="{{ asset('storage/' . $contract->file_url) }}" target="_blank">View File</a></td>
                    <td>{{ $contract->status }}</td>
                    <td>{{ $contract->uploaded_at }}</td>
                    <td>
                        <a href="{{ route('contracts.show', $contract->id) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
