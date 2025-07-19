@extends('layouts.app')

@section('title', 'Production Batches')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Production Batches</h1>

    <div class="card shadow-sm bg-white">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>List of Production Batches</span>
            <input type="text" id="batchSearchInput" class="form-control form-control-sm w-25" placeholder="Search batches...">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Batch ID</th>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="batchesTableBody">
                        @forelse($batches as $batch)
                        <tr>
                            <td>{{ $batch->id }}</td>
                            <td>{{ $batch->order_id }}</td>
                            <td>{{ $batch->product->name ?? 'N/A' }}</td>
                            <td>{{ $batch->quantity }}</td>
                            <td><span class="badge {{ $batch->status === 'in_progress' ? 'bg-info' : ($batch->status === 'completed' ? 'bg-success' : 'bg-secondary') }}">{{ Str::title(str_replace('_', ' ', $batch->status)) }}</span></td>
                            <td>{{ $batch->start_date ? $batch->start_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>{{ $batch->end_date ? $batch->end_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('production.batches.show', $batch->id) }}" class="btn btn-outline-info btn-sm">View</a>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateBatchStatusModal" data-batch-id="{{ $batch->id }}" data-current-status="{{ $batch->status }}">Update Status</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No production batches found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</div>

@include('production.modals.update_batch_status_modal')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchSearchInput = document.getElementById('batchSearchInput');
        const batchesTableBody = document.getElementById('batchesTableBody');
        const updateBatchStatusModal = document.getElementById('updateBatchStatusModal');

        batchSearchInput.addEventListener('keyup', function() {
            const searchTerm = batchSearchInput.value.toLowerCase();
            const rows = batchesTableBody.getElementsByTagName('tr');

            Array.from(rows).forEach(row => {
                const cells = row.getElementsByTagName('td');
                let found = false;
                for (let i = 0; i < cells.length; i++) {
                    const cellText = cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                row.style.display = found ? '' : 'none';
            });
        });

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
    });
</script>
@endpush