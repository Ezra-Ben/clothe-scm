@extends('layouts.app')

@section('title', 'Batch Details: ' . $batch->id)

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Batch #{{ $batch->id }} Details</h1>

    <div class="card shadow-sm mb-4 bg-white">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Batch Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order ID:</strong> <a href="{{ route('production.orders.show', $batch->order->id) }}">{{ $batch->order->id }}</a></p>
                    <p><strong>Product:</strong> {{ $batch->product->name ?? 'N/A' }}</p>
                    <p><strong>Quantity:</strong> {{ $batch->quantity }}</p>
                    <p><strong>Current Status:</strong> <span class="badge bg-info">{{ Str::title(str_replace('_', ' ', $batch->status)) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Start Date:</strong> {{ $batch->start_date ? $batch->start_date->format('Y-m-d H:i') : 'N/A' }}</p>
                    <p><strong>End Date:</strong> {{ $batch->end_date ? $batch->end_date->format('Y-m-d H:i') : 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $batch->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $batch->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <hr>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateBatchStatusModal" data-batch-id="{{ $batch->id }}" data-current-status="{{ $batch->status }}">Update Status</button>
        </div>
    </div>

    <div class="card shadow-sm bg-white">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Production Activities</h5>
        </div>
        <div class="card-body">
            @if($batch->activities->count())
                <ul class="list-group list-group-flush">
                    @foreach($batch->activities as $activity)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $activity->description }}</strong>
                                <br>
                                <small class="text-muted">{{ $activity->timestamp->format('Y-m-d H:i:s') }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ $activity->type ?? 'General' }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No activities recorded for this batch yet.</p>
            @endif
            <button type="button" class="btn btn-outline-secondary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#addActivityModal" data-batch-id="{{ $batch->id }}">Add New Activity</button>
        </div>
    </div>
</div>

@include('production.modals.update_batch_status_modal')

<div class="modal fade" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="addActivityModalLabel">Add New Activity for Batch #<span id="activityModalBatchIdDisplay"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addActivityForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="activityModalBatchId" name="batch_id">
                    <div class="mb-3">
                        <label for="activityDescription" class="form-label">Activity Description</label>
                        <textarea class="form-control" id="activityDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="activityType" class="form-label">Activity Type</label>
                        <input type="text" class="form-control" id="activityType" name="type" placeholder="e.g., QC Check, Material Added">
                    </div>
                    <div class="mb-3">
                        <label for="activityTimestamp" class="form-label">Timestamp</label>
                        <input type="datetime-local" class="form-control" id="activityTimestamp" name="timestamp" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateBatchStatusModal = document.getElementById('updateBatchStatusModal');
        updateBatchStatusModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const batchId = button.getAttribute('data-batch-id');
            const currentStatus = button.getAttribute('data-current-status');

            const modalBatchIdInput = updateBatchStatusModal.querySelector('#modalBatchId');
            const newBatchStatusSelect = updateBatchStatusModal.querySelector('#newBatchStatus');

            modalBatchIdInput.value = batchId;
            newBatchStatusSelect.value = currentStatus;
        });

        const updateBatchStatusForm = document.getElementById('updateBatchStatusForm');
        updateBatchStatusForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const batchId = document.getElementById('modalBatchId').value;
            const newStatus = document.getElementById('newBatchStatus').value;

            fetch(`/api/production/batches/${batchId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Batch status updated:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Error updating batch status:', error);
                alert('Failed to update batch status. Please try again.');
            });

            const modal = bootstrap.Modal.getInstance(updateBatchStatusModal);
            modal.hide();
        });


        const addActivityModal = document.getElementById('addActivityModal');
        addActivityModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const batchId = button.getAttribute('data-batch-id');
            addActivityModal.querySelector('#activityModalBatchId').value = batchId;
            addActivityModal.querySelector('#activityModalBatchIdDisplay').innerText = batchId;
        });

        const addActivityForm = document.getElementById('addActivityForm');
        addActivityForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const batchId = document.getElementById('activityModalBatchId').value;
            const description = document.getElementById('activityDescription').value;
            const type = document.getElementById('activityType').value;
            const timestamp = document.getElementById('activityTimestamp').value;

            fetch(`/api/production/batches/${batchId}/activities`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ description, type, timestamp })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Activity added:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Error adding activity:', error);
                alert('Failed to add activity. Please try again.');
            });

            const modal = bootstrap.Modal.getInstance(addActivityModal);
            modal.hide();
        });
    });
</script>
@endpush