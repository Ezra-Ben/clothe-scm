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
