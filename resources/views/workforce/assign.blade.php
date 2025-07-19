@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Assign Task: {{ $task->name }}</h3>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Duration:</strong> {{ $task->average_duration_minutes }} mins</p>
    <p><strong>Required Workers:</strong>  @foreach ($task->positionRequirements as $position)
        {{ $position->pivot->required_count }} {{ Str::plural($position->name, $position->pivot->required_count) }}@if (!$loop->last), @endif
    @endforeach</p>

@php
    $assignedCounts = $task->allocations()
        ->with('employee.position')
        ->get()
        ->groupBy(fn($alloc) => $alloc->employee->position->name ?? 'Unknown')
        ->map(fn($group) => $group->count());
@endphp

    <p><strong>Already Assigned:</strong> @if ($assignedCounts->isEmpty())
        0
    @else
        @foreach ($assignedCounts as $position => $count)
            {{ $count }} {{ Str::plural($position, $count) }}@if (!$loop->last), @endif
        @endforeach
    @endif</p>

    <hr>
    <h5>Eligible Employees</h5>
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
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->position->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-info btn-sm">View</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('workforce.assign') }}">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button class="btn btn-success btn-sm">Assign</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
