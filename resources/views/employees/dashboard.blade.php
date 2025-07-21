@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Employee Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Notifications --}}
    @if(auth()->user()->unreadNotifications->count())
        <div class="alert alert-info">
            <h5>New Notifications</h5>
            <ul class="list-group mb-3">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $notification->data['message'] }}
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary">Mark as read</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <h4>Profile</h4>
        <p><strong>Name:</strong> {{ $employee->user->name }}</p>
        <p><strong>Position:</strong> {{ $employee->position->name ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $employee->user->email }}</p>
        <p><strong>Phone:</strong> {{ $employee->user->contact }}</p>
        <p><strong>DOB:</strong> {{ $employee->dob }}</p>
    </div>

    <div>
        <h4>Assigned Tasks</h4>
        @if($allocations->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Scheduled</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allocations as $allocation)
                        <tr>
                            <td>{{ $allocation->task->name }}</td>
                            <td>
                                <span class="badge 
                                    @if($allocation->status == 'Complete') bg-success
                                    @elseif($allocation->status == 'In Process') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ $allocation->status }}
                                </span>
                            </td>
                            <td>{{ $allocation->scheduled_at ?? 'Not Set' }}</td>
                            <td>
                                <a href="{{ route('employee.task.show', $allocation->task->id) }}" class="btn btn-sm btn-outline-info">View</a>

                                @if(in_array($allocation->status, ['Pending', 'In Process']))
                                    <form method="POST" action="{{ route('employee.task.update', $allocation->id) }}" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Complete">
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Mark this task as complete?')">Mark Complete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tasks assigned yet.</p>
        @endif
    </div>
</div>
@endsection
