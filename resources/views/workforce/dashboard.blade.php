@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Workforce Allocator Dashboard</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

    <div class="mb-5">
        <h4>Unassigned Tasks</h4>
        @if($unassignedTasks->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Required Workers</th>
                    <th>Assigned</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unassignedTasks as $task)
                <tr>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->scheduled_date }}</td>
                    <td>{{ $task->positionRequirements->sum('pivot.required_count') }}</td>
                    <td>{{ $task->allocations()->count() }}</td>
                    <td><a href="{{ route('workforce.assign.view', $task->id) }}" class="btn btn-sm btn-primary">Assign</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>No Unassigned tasks.</p>
        @endif
    </div>

    <div>
        <h4>Assigned Tasks</h4>
        @if($assignedTasks->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Scheduled</th>
                    <th>Assigned Workers</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignedTasks as $task)
                <tr>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->scheduled_date }}</td>
                    <td>{{ $task->allocations()->count() }} / {{ $task->positionRequirements->sum('pivot.required_count') }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>No assigned tasks.</p>
        @endif
    </div>
</div>
@endsection