@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-person-plus"></i> {{ __('Add Customer') }}
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
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <table class="table table-bordered align-middle mb-0">
                        <tr>
                            <th style="width: 30%;">Name</th>
                            <td>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </td>
                        </tr>
                    </table>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Save Customer
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 