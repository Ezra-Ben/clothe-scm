@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary">Register New Employee</h1>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        {{-- USER FIELDS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">User Account Info</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                </div>
            </div>
        </div>

        {{-- EMPLOYEE FIELDS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Employee Info</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
                </div>
                <div class="col-md-4">
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-select" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="job_title_id" class="form-label">Job Title</label>
                    <select name="job_title_id" id="job_title_id" class="form-select" required>
                        <option value="">Select Job Title</option>
                        @foreach($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}" {{ old('job_title_id') == $jobTitle->id ? 'selected' : '' }}>
                                {{ $jobTitle->name }}
                            </option>
                        @endforeach
                    </select>
                    </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Proceed to Set Password</button>
    </form>
</div>
@endsection
