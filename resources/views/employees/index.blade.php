@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Employees by Department</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            Register New Employee
        </a>
    </div>

    @foreach($departments as $department)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>{{ $department->name }}</strong>
                <span class="badge bg-secondary float-end">{{ $department->employees->count() }} Employees</span>
            </div>
            <div class="card-body table-responsive">
                @if($department->employees->count())
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department->employees as $employee)
                                <tr>
                                    <td>{{ $employee->user->name }}</td>
                                    <td>{{ $employee->user->email }}</td>
                                    <td>{{ $employee->jobTitle->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->status === 'assigned' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        @if(auth()->user()->hasRole('hr_manager'))
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No employees in this department yet.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
