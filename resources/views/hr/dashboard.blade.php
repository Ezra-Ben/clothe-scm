@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">HR Dashboard</h2>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Employees</h5>
                    <p class="card-text fs-3">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Departments</h5>
                    <p class="card-text fs-3">{{ $totalDepartments }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Job Titles</h5>
                    <p class="card-text fs-3">{{ $totalJobTitles }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Employees Per Department --}}
    <div class="card">
        <div class="card-header">Employees by Department</div>
        <div class="card-body">
            @foreach($departments as $department)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $department->name }}</strong>
                        <span>{{ $department->employees_count }} employees</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $department->percentage }}%;"
                             aria-valuenow="{{ $department->percentage }}"
                             aria-valuemin="0" aria-valuemax="100">
                             {{ number_format($department->percentage) }}%
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('employees.index') }}" class="btn btn-primary">Manage Employees</a>
        </div>
    </div>
</div>
@endsection
