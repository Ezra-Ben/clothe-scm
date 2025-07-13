@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Procurement Request #{{ $procurementRequest->id }}</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p><strong>Supplier:</strong> {{ $procurementRequest->supplier->vendor->user->name ?? '-' }}</p>
    <p><strong>Raw Material:</strong> {{ $procurementRequest->rawMaterial->name ?? '-' }}</p>
    <p><strong>Quantity:</strong> {{ $procurementRequest->quantity }}</p>
    <p><strong>Status:</strong> {{ ucfirst($procurementRequest->status) }}</p>
    <p><strong>Created At:</strong> {{ $procurementRequest->created_at->format('Y-m-d H:i') }}</p>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('procurement.requests.index') }}" class="btn btn-secondary">
            Back to Requests
        </a>

        @can('admin')
            <a href="{{ route('procurement.replies.indexForRequest', $procurementRequest->id) }}" class="btn btn-info">
                View Replies
            </a>
        @endcan

        @can('supplier')
            @if($procurementRequest->status === 'pending')
                <!-- Show Accept and Reject buttons for pending requests -->
                <form method="POST" action="{{ route('procurement.requests.accept', $procurementRequest->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Accept Request</button>
                </form>

                <!-- Reject button with modal trigger -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    Reject Request
                </button>

            @elseif($procurementRequest->status === 'accepted')
                <!-- Show inactive Accepted button and Create Reply button -->
                <button type="button" class="btn btn-success" disabled>
                    Accepted <i class="bi bi-check-circle"></i> 
                </button>
                <a href="{{ route('procurement.replies.create', $procurementRequest->id) }}" class="btn btn-primary">
                    Create Reply
                </a>
            @elseif($procurementRequest->status === 'rejected')
                <!-- Show inactive Rejected button -->
                <button type="button" class="btn btn-danger" disabled>
                    Rejected <i class="bi bi-x-circle"></i>
                </button>
            @endif
            <!-- For rejected status, only Back to Requests button is shown -->
        @endcan
    </div>

    @can('supplier')
        @if($procurementRequest->status === 'pending')
            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('procurement.requests.reject', $procurementRequest->id) }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Reason for Rejection *</label>
                                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" 
                                              placeholder="Please provide a detailed reason for rejecting this request..." required></textarea>
                                </div>
                                <p class="text-muted small">
                                    <strong>Note:</strong> This will set the request status to "Rejected" and create an official reply record.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endcan

</div>

@endsection
