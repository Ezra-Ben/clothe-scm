@extends('layouts.app')
@section('content')
@if(auth()->user()->unreadNotifications->count())
    <div class="position-relative mb-4">
        <button class="btn btn-outline-primary position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#messageNotifications" aria-expanded="false" aria-controls="messageNotifications">
            <i class="bi bi-chat-dots"></i>
            New Messages
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </button>
        <div class="collapse mt-2" id="messageNotifications">
            <div class="card card-body">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
                        <strong>
                            <i class="bi bi-person-circle"></i>
                            {{ $notification->data['sender'] }}:
                        </strong>
                        {{ $notification->data['message'] }}
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success ms-2">Mark as read</button>
                        </form>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
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
                <div class="card-header">
                    <strong>Supplier Information</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3"> <label for="address" class="form-label"><strong>Address</strong></label> 
                    <p>{{ $supplier->address}}</p>
                    </div>
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
 <button id="open-chat-list"
    type="button"
    data-bs-toggle="modal"
    data-bs-target="#chatUserModal"
    style="position:fixed; inset-block-end:30px; inset-inline-end:30px; z-index:9999; background:#0d6efd; color:white; border:none; border-radius:50%; inline-size:60px; block-size:60px; font-size:2rem; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
    💬
</button>
@if(isset($users))
    @include('components.chat-user-modal', ['users' => $users])
@endif
@include('components.chat-widget')
@endsection