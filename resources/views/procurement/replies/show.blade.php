@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Procurement Reply #{{ $reply->id }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Reply Details</strong>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Request ID</dt>
                <dd class="col-sm-8">{{ $reply->procurement_request_id }}</dd>

                @cannot('supplier')
                <dt class="col-sm-4">Supplier</dt>
                <dd class="col-sm-8">
                    @if($reply->supplier && $reply->supplier->vendor && $reply->supplier->vendor->user)
                        {{ $reply->supplier->vendor->user->name }}
                    @else
                        <em class="text-muted">Supplier information not available</em>
                    @endif
                </dd>
                @endcannot

                <dt class="col-sm-4">Confirmed Quantity</dt>
                <dd class="col-sm-8">{{ $reply->quantity_confirmed }}</dd>

                <dt class="col-sm-4">Expected Delivery</dt>
                <dd class="col-sm-8">{{ $reply->expected_delivery_date }}</dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">
                    <span class="badge bg-{{ 
                        $reply->status == 'confirmed' ? 'success' : 
                        ($reply->status == 'shipped' ? 'info' : 
                        ($reply->status == 'delivered' ? 'warning' :
                        ($reply->status == 'delivered_accepted' ? 'primary' :
                        ($reply->status == 'delivered_rejected' ? 'danger' :
                        ($reply->status == 'rejected' ? 'danger' : 'secondary')))))
                    }}">
                        {{ ucfirst(str_replace('_', ' ', $reply->status)) }}
                    </span>
                </dd>

                @if(in_array($reply->status, ['rejected', 'delivered_rejected']) && $reply->rejection_reason)
                    <dt class="col-sm-4">Rejection Reason</dt>
                    <dd class="col-sm-8">
                        <div class="alert alert-danger">
                            {{ $reply->rejection_reason }}
                        </div>
                    </dd>
                @else
                    <dt class="col-sm-4">Remarks</dt>
                    <dd class="col-sm-8">{{ $reply->remarks ?: '-' }}</dd>
                @endif
            </dl>
        </div>
    </div>

    {{-- Supplier-only: Delivered Materials --}}
    @can('supplier')
        @if ($reply->status == 'confirmed')
            <form action="{{ route('procurement.replies.markDelivered', $reply->id) }}" method="POST" class="mb-3">
                @csrf
                <button type="submit" class="btn btn-info">Delivered Materials</button>
            </form>
        @endif
    @endcan

    {{-- Admin/Procurement Manager: Accept or Reject Delivery --}}
    @can('manage-procurement')
        @if ($reply->status == 'delivered')
            {{-- Show action buttons when delivery needs review --}}
            <div class="d-flex justify-content-between mb-3">
                {{-- Accept Delivery Button --}}
                <form action="{{ route('procurement.replies.acceptDelivery', $reply->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Accept</button>
                </form>

                {{-- Reject Delivery Button with Modal --}}
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectDeliveryModal">
                    Reject
                </button>
            </div>

            {{-- Rejection Modal --}}
            <div class="modal fade" id="rejectDeliveryModal" tabindex="-1" aria-labelledby="rejectDeliveryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectDeliveryModalLabel">Reject Delivery</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('procurement.replies.rejectDelivery', $reply->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required placeholder="Please provide a reason for rejecting this delivery..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject Delivery</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif ($reply->status == 'delivered_accepted')
            {{-- Show read-only accepted status --}}
            <div class="mb-3">
                <button type="button" class="btn btn-success" disabled>
                    <i class="bi bi-check-circle"></i> Accepted
                </button>
                <small class="text-muted ms-2">This delivery has been accepted and added to inventory.</small>
            </div>
        @elseif ($reply->status == 'delivered_rejected')
            {{-- Show read-only rejected status --}}
            <div class="mb-3">
                <button type="button" class="btn btn-danger" disabled>
                    <i class="bi bi-x-circle"></i> Rejected
                </button>
                <small class="text-muted ms-2">This delivery has been rejected after inspection.</small>
            </div>
        @endif
    @endcan

    @can('supplier')
    <a href="{{ route('procurement.replies.index') }}" class="btn btn-secondary">
        Back to Replies
    </a>
    @endcan
    @can('manage-procurement')
        <a href="{{ route('procurement.replies.indexForRequest', $reply->procurement_request_id) }}" class="btn btn-secondary">
            Back to Replies
        </a>
    @endcan
</div>
@endsection
