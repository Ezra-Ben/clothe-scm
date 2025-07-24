@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Assign Task: {{ $task->name }}</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif
    
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Duration:</strong> {{ $task->average_duration_minutes }} mins</p>

    <p><strong>Required Workers:</strong>
        @foreach ($task->allowedJobTitles as $jobTitle)
            {{ $jobTitle->pivot->required_count }} {{ Str::plural($jobTitle->name, $jobTitle->pivot->required_count) }}@if (!$loop->last), @endif
        @endforeach
    </p>

    @php
        $assignedCounts = $task->allocations()
            ->with('employee.jobTitle')
            ->get()
            ->groupBy(fn($alloc) => $alloc->employee->jobTitle->name ?? 'Unknown')
            ->map(fn($group) => $group->count());
    @endphp

    <p><strong>Already Assigned:</strong>
        @if ($assignedCounts->isEmpty())
            0
        @else
            @foreach ($assignedCounts as $jobTitle => $count)
                {{ $count }} {{ Str::plural($jobTitle, $count) }}@if (!$loop->last), @endif
            @endforeach
        @endif
    </p>

    <hr>
    <h5>Eligible Employees</h5>

    @if($employees->isEmpty())
        <p class="text-muted">No eligible employees found for this task.</p>
    @else
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>View</th>
                    <th>Assign</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->user->name }}</td>
                    <td>{{ $employee->jobTitle->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-info btn-sm">View</a>
                    </td>
                    <td>
                    @if ($employee->status === 'unassigned')
                        <form method="POST" action="{{ route('workforce.assign') }}">
                        @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <button class="btn btn-success btn-sm">Assign</button>
                        </form>
                    @else
                        <span class="badge bg-secondary">Assigned</span>
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('workforce.dashboard', ['department_id' => $departmentId]) }}" class="btn btn-secondary mt-4">
        <i class="bi bi-arrow-left"></i> Back to Workforce Dashboard
    </a>
</div>
@endsection
