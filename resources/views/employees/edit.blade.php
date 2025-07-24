@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Employee Profile</h2>

<form action="{{ route('employees.update', $employee->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Email --}}
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $employee->user->email) }}"
               {{ $canEditEmployee ? '' : 'readonly' }}>
    </div>


    {{-- Department --}}
    <div class="mb-3">
        <label>Department</label>
        <select name="department_id" class="form-control" {{ $canEditHR ? '' : 'disabled' }}>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Job Title --}}
    <div class="mb-3">
        <label>Job Title</label>
        <select name="job_title_id" class="form-control" {{ $canEditHR ? '' : 'disabled' }}>
            @foreach($jobTitles as $jobTitle)
                <option value="{{ $jobTitle->id }}" {{ $employee->job_title_id == $jobTitle->id ? 'selected' : '' }}>
                    {{ $jobTitle->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Date of Birth (Always Readonly) --}}
    <div class="mb-3">
        <label>Date of Birth</label>
        <input type="date" name="dob" class="form-control"
               value="{{ $employee->dob }}" readonly>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
