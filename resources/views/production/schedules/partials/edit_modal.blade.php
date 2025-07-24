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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
