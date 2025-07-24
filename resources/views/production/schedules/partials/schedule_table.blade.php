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
                            <td>{{ $schedule->start_date->format('Y-m-d') }}</td>
                            <td>{{ $schedule->end_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge {{ $schedule->status === 'active' ? 'bg-success' : ($schedule->status === 'planned' ? 'bg-primary' : 'bg-secondary') }}">
                                    {{ Str::title(str_replace('_', ' ', $schedule->status)) }}
                                </span>
                            </td>
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
                                @if(!$schedule->status=='completed')
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editScheduleModal"
                                        data-schedule-id="{{ $schedule->id }}"
                                        data-description="{{ $schedule->description }}"
                                        data-start-date="{{ $schedule->start_date->format('Y-m-d') }}"
                                        data-end-date="{{ $schedule->end_date->format('Y-m-d') }}"
                                        data-status="{{ $schedule->status }}"
                                        data-batches="{{ $schedule->batches->pluck('id')->implode(',') }}">
                                    Edit
                                </button>
                                @endif
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this schedule?');">
                                        Delete
                                    </button>
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
