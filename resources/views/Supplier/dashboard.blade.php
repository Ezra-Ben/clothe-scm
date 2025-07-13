@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Supplier Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Vendor Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $supplier->vendor->name }}</p>
                    <p><strong>Contact:</strong> {{ $supplier->vendor->contact }}</p>
                    <p><strong>Registration Number:</strong> {{ $supplier->vendor->registration_number }}</p>
                    <p><strong>Product Category:</strong> {{ $supplier->vendor->product_category }}</p>
                    <p><strong>Business License:</strong> 
                        <a href="{{ $supplier->vendor->business_license_url }}" target="_blank">View License</a>
                    </p>
                </div>
            </div>
            <div class="mb-3">
                    <a href="{{ route('supplier.profile', ['updated' => true]) }}" class="btn btn-primary">
                       Go to Profile
                    </a>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Notifications</strong>
                    <div>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger me-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Mark All Read</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        @php
                            $cssClass = 'bg-light'; // default
                            if (isset($notification->data['type']) && $notification->data['type'] == 'delivery_accepted') {
                                $cssClass = 'border-success bg-success bg-opacity-10';
                            } elseif (isset($notification->data['rejection_reason'])) {
                                $cssClass = 'border-danger bg-danger bg-opacity-10';
                            }
                        @endphp
                        <div class="mb-3 p-2 border rounded {{ $cssClass }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Mark as read">×</button>
                                </form>
                            </div>
                            <p class="mb-2">{{ $notification->data['message'] }}</p>
                            
                            {{-- Smart routing based on notification type --}}
                            @if(isset($notification->data['reply_id']))
                                <a href="{{ route('procurement.replies.show', $notification->data['reply_id']) }}" class="btn btn-primary btn-sm"
                                   onclick="markAsRead('{{ $notification->id }}')">
                                    View Reply Details
                                </a>
                            @elseif(isset($notification->data['request_id']))
                                <a href="{{ route('procurement.requests.show', $notification->data['request_id']) }}" class="btn btn-primary btn-sm"
                                   onclick="markAsRead('{{ $notification->id }}')">
                                    View Request
                                </a>
                            @endif
                            
                            {{-- Show rejection reason if present --}}
                            @if(isset($notification->data['rejection_reason']))
                                <div class="mt-2">
                                    <small><strong>Reason:</strong> {{ $notification->data['rejection_reason'] }}</small>
                                </div>
                            @endif
                        </div>
                    @empty
                            <p class="text-muted">No new notifications</p>
                    @endforelse
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Statistics</strong>
                </div>
                <div class="card-body">
                    <p><strong>Total Contracts:</strong> {{ $supplier->contracts->count() }}</p>
                    @if($supplier->performances && $supplier->performances->isNotEmpty())
                       <p><strong>Average Performance Rating:</strong>
                           {{ number_format($supplier->performances->avg('rating'), 2) }}
                       </p>
                    @else
                    <p><strong>Average Performance Rating:</strong> <em>No Performance Record</em></p>
                    @endif                
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Contracts</strong>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Contract Number</th>
                                <th>Status</th>
                                <th>Uploaded By</th>
                                <th>Uploaded At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($supplier->contracts && $supplier->contracts->isNotEmpty())
                            @foreach($supplier->contracts as $contract)
                                <tr>
                                    <td>{{ $contract->contract_number }}</td>
                                    <td>{{ ucfirst($contract->status) }}</td>
                                    <td>{{ $contract->addedBy ? $contract->addedBy->name : 'N/A' }}</td>
                                    <td>{{ $contract->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                         @else
    			 <tr>
       				 <td colspan="4" class="text-muted text-center"><em>No Contracts Found</em></td>
   			 </tr>
			 @endif

                         </tbody>
                    </table>
                <div class="d-flex justify-content-start gap-3 mt-3">
                    <a href="{{ route('supplier.contracts.index') }}" class="btn btn-outline-primary">
        			View Contracts
    		        </a>
                </div>
            </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <strong>My Procurement Replies</strong>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Track your responses to procurement requests and manage deliveries.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('procurement.replies.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-reply me-1"></i>My Replies
                        </a>
                        <a href="{{ route('procurement.requests.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-search me-1"></i>Browse Requests
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <strong>Performance Records</strong>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
				<th>Note</th>
                                <th>Rating</th>
                                <th>Created By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
			 @if($supplier->performances && $supplier->performances->isNotEmpty())
                            @foreach($supplier->performances as $record)
                                <tr>
				    <td>{{ \Illuminate\Support\Str::words($record->performance_note, 2, '...') }}</td>
                                    <td>{{ $record->rating }}</td>
                                    <td>{{ $record->createdBy->name }}</td>
                                    <td>{{ $record->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        @else
 			<tr>
                            <td colspan="4" class="text-muted text-center"><em>No Performance Records</em></td>
    			</tr>
			@endif
                        </tbody>
                    </table>
		<div class="d-flex justify-content-start gap-3 mt-3">
   			<a href="{{ route('supplier.performance') }}" class="btn btn-outline-success">
                                View Performance
                        </a>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection