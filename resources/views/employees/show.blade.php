@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Employee Profile</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Show Profile --}}
    <div class="card">
        <div class="card-body">
            {{-- Name --}}
            <div class="mb-3">
                <strong>Name:</strong> {{ $employee->user->name }}
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <strong>Email:</strong> {{ $employee->user->email }}
            </div>

            {{-- Date of Birth --}}
            <div class="mb-3">
                <strong>Date of Birth:</strong> {{ $employee->dob }}
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <strong>Department:</strong> {{ $employee->department->name ?? 'N/A' }}
            </div>

            {{-- Job Title --}}
            <div class="mb-3">
                <strong>Job Title:</strong> {{ $employee->jobTitle->name ?? 'N/A' }}
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <strong>Status:</strong> 
                <span class="badge bg-{{ $employee->status === 'assigned' ? 'success' : 'secondary' }}">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>
        </div>

        {{-- Show Edit Button if employee or HR --}}
        <div class="card-footer text-end">
            @if(
                auth()->user()->hasRole('employee') && auth()->user()->id === $employee->user_id
                || auth()->user()->hasRole('hr_manager')
            )
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
                    Edit Profile
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
