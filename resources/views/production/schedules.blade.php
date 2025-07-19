@extends('layouts.app')

@section('title', 'Production Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Production Schedules</h1>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
            <i class="bi bi-plus-circle me-1"></i> Create New Schedule
        </button>
    </div>

    <div class="card shadow-sm bg-white">
        <div class="card-header bg-success text-white">
            <h5>Upcoming and Current Schedules</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Schedule ID</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Assigned Batches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->id }}</td>
                            <td>{{ $schedule->description }}</td>
                            <td>{{ $schedule->start_date->format('Y-m-d') }}</td>
                            <td>{{ $schedule->end_date->format('Y-m-d') }}</td>
                            <td><span class="badge {{ $schedule->status === 'active' ? 'bg-success' : ($schedule->status === 'planned' ? 'bg-primary' : 'bg-secondary') }}">{{ Str::title(str_replace('_', ' ', $schedule->status)) }}</span></td>
                            <td>
                                @if($schedule->batches->count())
                                    @foreach($schedule->batches as $batch)
                                        <span class="badge bg-secondary">{{ $batch->id }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editScheduleModal" data-schedule-id="{{ $schedule->id }}" data-description="{{ $schedule->description }}" data-start-date="{{ $schedule->start_date->format('Y-m-d') }}" data-end-date="{{ $schedule->end_date->format('Y-m-d') }}" data-status="{{ $schedule->status }}">Edit</button>
                                <form action="{{ route('production.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No production schedules found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-labelledby="createScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createScheduleModalLabel">Create New Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('production.schedules.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduleDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="scheduleDescription" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="scheduleStartDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="scheduleEndDate" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleStatus" class="form-label">Status</label>
                        <select class="form-select" id="scheduleStatus" name="status">
                            <option value="planned">Planned</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleBatches" class="form-label">Associate Batches (IDs, comma-separated)</label>
                        <input type="text" class="form-control" id="scheduleBatches" name="batch_ids">
                        <small class="form-text text-muted">e.g., 1,5,10 (Ensure these batches exist and are relevant)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editScheduleId" name="schedule_id">
                    <div class="mb-3">
                        <label for="editScheduleDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editScheduleDescription" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="editScheduleStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="editScheduleStartDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editScheduleEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="editScheduleEndDate" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editScheduleStatus" class="form-label">Status</label>
                        <select class="form-select" id="editScheduleStatus" name="status">
                            <option value="planned">Planned</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editScheduleBatches" class="form-label">Associate Batches (IDs, comma-separated)</label>
                        <input type="text" class="form-control" id="editScheduleBatches" name="batch_ids">
                        <small class="form-text text-muted">e.g., 1,5,10 (Ensure these batches exist and are relevant)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editScheduleModal = document.getElementById('editScheduleModal');
        editScheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const scheduleId = button.getAttribute('data-schedule-id');
            const description = button.getAttribute('data-description');
            const startDate = button.getAttribute('data-start-date');
            const endDate = button.getAttribute('data-end-date');
            const status = button.getAttribute('data-status');

            const modalForm = editScheduleModal.querySelector('#editScheduleForm');
            modalForm.action = `/production/schedules/${scheduleId}`;

            editScheduleModal.querySelector('#editScheduleId').value = scheduleId;
            editScheduleModal.querySelector('#editScheduleDescription').value = description;
            editScheduleModal.querySelector('#editScheduleStartDate').value = startDate;
            editScheduleModal.querySelector('#editScheduleEndDate').value = endDate;
            editScheduleModal.querySelector('#editScheduleStatus').value = status;
        });
    });
</script>
@endpush