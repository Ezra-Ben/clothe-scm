@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Proofs of Delivery (PODs)</h3>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>POD List</h5>
            <input type="text" id="podSearch" class="form-control w-auto" placeholder="Search by Delivered/Received By" onkeyup="filterPods()">
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="podTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Shipment Type</th>
                        <th>Delivered By</th>
                        <th>Received By</th>
                        <th>Received At</th>
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pods as $pod)
                    <tr>
                        <td>{{ $pod->id }}</td>
                        <td>
                            @if($pod->shipment_type === 'App\Models\InboundShipment')
                                Inbound
                            @elseif($pod->shipment_type === 'App\Models\OutboundShipment')
                                Outbound
                            @else
                                Unknown
                            @endif
                        </td>
                        <td>{{ $pod->delivered_by }}</td>
                        <td>{{ $pod->received_by }}</td>
                        <td>{{ optional($pod->received_at)->format('d M Y H:i') }}</td>
                        <td>{{ $pod->condition ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('pods.show', $pod->id) }}" class="btn btn-sm btn-outline-info">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
