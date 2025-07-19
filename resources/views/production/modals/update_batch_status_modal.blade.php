<div class="modal fade" id="updateBatchStatusModal" tabindex="-1" aria-labelledby="updateBatchStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateBatchStatusModalLabel">Update Batch Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateBatchStatusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="modalBatchId" name="batch_id">
                    <div class="mb-3">
                        <label for="newBatchStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newBatchStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
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