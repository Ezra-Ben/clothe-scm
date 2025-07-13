@extends('layouts.app')

@section('content')
<h1>Your Procurement Replies</h1>

<table class="table">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Confirmed Qty</th>
            <th>Status</th>
            <th>Expected Delivery</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($replies as $reply)
        <tr>
            <td>{{ $reply->procurement_request_id }}</td>
            <td>{{ $reply->quantity_confirmed }}</td>
            <td>{{ $reply->status }}</td>
            <td>{{ $reply->expected_delivery_date }}</td>
            <td>
                <a href="{{ route('procurement.replies.show', $reply->id) }}" class="btn btn-sm btn-info">Open</a>
                <a href="{{ route('procurement.replies.edit', $reply->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('procurement.replies.destroy', $reply->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
