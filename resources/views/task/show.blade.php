@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Task: {{ $task->name }}</h3>

    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Average Duration:</strong> {{ $task->average_duration_minutes }} mins</p>

    <h5 class="mt-4">Assigned Employees:</h5>
    @if($task->allocations->count())
        <ul class="list-group mb-3">
            @foreach($task->allocations as $allocation)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $allocation->employee->user->name }}
                    <span class="badge bg-secondary">{{ $allocation->employee->position->name ?? 'N/A' }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No employees assigned yet.</p>
    @endif

    @php
        $employee = auth()->user()->employee;
        $myAllocation = $task->allocations->where('employee_id', $employee->id)->first();
    @endphp

    @if ($task->status !== 'completed' && $myAllocation)
        <form action="{{ route('employee.task.update', $myAllocation->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <p><strong>Current Status:</strong> {{ ucfirst($myAllocation->status) }}</p>

            <label for="status"><strong>Update Status:</strong></label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pending" {{ $myAllocation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Process" {{ $myAllocation->status === 'in process' ? 'selected' : '' }}>In Process</option>
                <option value="Complete" {{ $myAllocation->status === 'complete' ? 'selected' : '' }}>Complete</option>
            </select>

            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    @endif

    <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
@endsection
