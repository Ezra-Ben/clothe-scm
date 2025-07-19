@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Employee Profile: {{ $employee->name }}</h3>

    <p><strong>Position:</strong> {{ $employee->position->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $employee->email }}</p>
    <p><strong>Phone:</strong> {{ $employee->phone }}</p>
    <p><strong>Date of Birth:</strong> {{ $employee->dob }}</p>

    <hr>
    <h5>Task Assignments</h5>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Task</th>
                <th>Status</th>
                <th>Duration</th>
                <th>Scheduled At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $allocation)
            <tr>
                <td>{{ $allocation->task->name }}</td>
                <td>{{ ucfirst($allocation->status) }}</td>
                <td>{{ $allocation->duration_minutes ?? 'N/A' }} mins</td>
                <td>{{ $allocation->scheduled_at ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
@endsection
