@extends('layouts.app')

@section('title', 'Carrier: ' . $carrier->name)

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Carrier Profile: {{ $carrier->name }}</h5>
        </div>
        <div class="card-body">

            {{-- Contact Information --}}
            <h6 class="mb-2">📞 Contact Information</h6>
            <p><strong>Phone:</strong> {{ $carrier->contact_phone ?? 'N/A' }}</p>
            @if ($carrier->email)
                <p><strong>Email:</strong> {{ $carrier->email }}</p>
            @endif

            <hr>

            {{-- Service Information --}}
            <h6 class="mb-2">🚚 Service Information</h6>
            <p><strong>Service Areas:</strong></p>
            <ul>
                @foreach (is_array($carrier->service_areas) ? $carrier->service_areas : json_decode($carrier->service_areas ?? '[]') as $area)
                    <li>{{ $area }}</li>
                @endforeach
            </ul>

            <p><strong>Supported Service Levels:</strong></p>
            <ul>
                @foreach (is_array($carrier->supported_service_levels) ? $carrier->supported_service_levels : json_decode($carrier->supported_service_levels ?? '[]') as $level)
                    <li>{{ $level }}</li>
                @endforeach
            </ul>

            <hr>

            {{-- Pricing & Limits --}}
            <h6 class="mb-2">💰 Pricing & Limits</h6>
            <p><strong>Base Rate:</strong> ${{ number_format($carrier->base_rate_usd, 2) }}</p>
            <p><strong>Max Weight:</strong> {{ number_format($carrier->max_weight_kg, 2) }} kg</p>

            <hr>

            {{-- Tracking --}}
            <h6 class="mb-2">🔗 Tracking</h6>
            <p><strong>URL Template:</strong>
                @if ($carrier->tracking_url_template)
                    <a href="{{ $carrier->tracking_url_template }}" target="_blank">{{ $carrier->tracking_url_template }}</a>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </p>
            <p><em>Use <code>{tracking_number}</code> as placeholder</em></p>

            <hr>

            {{-- Recent Deliveries --}}
            <h6 class="mb-2">📦 Recent Deliveries</h6>
            @forelse ($carrier->deliveries as $delivery)
                <div>
                    <a href="{{ route('distributionandlogistics.deliveries.show', $delivery) }}">
                        #{{ $delivery->tracking_number }}
                    </a>
                    <small class="text-muted">({{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') }})</small>
                </div>
            @empty
                <p class="text-muted">No deliveries yet.</p>
            @endforelse

        </div>
    </div>

    <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-secondary btn-sm">
        ← Back to Carrier Directory
    </a>
</div>
@endsection
