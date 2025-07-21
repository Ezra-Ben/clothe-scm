@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Resource Capacity Planning</h1>

{{-- 1. CAPACITY OVERVIEW FIRST --}}
<div class="card shadow-sm border-primary mb-5">
    <div class="card-header bg-primary text-white">
        Capacity Overview
    </div>
    <div class="card-body">
        <form action="{{ route('capacity_planning.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="view_start_date" class="form-label">View From:</label>
                <input type="date" class="form-control" id="view_start_date" name="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label for="view_end_date" class="form-label">View To:</label>
                <input type="date" class="form-control" id="view_end_date" name="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary">Update View</button>
            </div>
        </form>
        <div id='calendar' style="height: 600px;"></div>
    </div>
</div>

{{-- 2. ASSIGNMENT FORM AFTERWARDS --}}
<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Assign Resource to Batch
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('capacity_planning.assign') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label for="batch_id" class="form-label">Select Batch</label>
                <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id" name="batch_id" required>
                    <option value="">Choose...</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                            Batch #{{ $batch->id }} ({{ $batch->product->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('batch_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="resource_id" class="form-label">Assign Resource</label>
                <select class="form-select @error('resource_id') is-invalid @enderror" id="resource_id" name="resource_id" required>
                    <option value="">Choose...</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }} ({{ ucfirst($resource->type) }})
                        </option>
                    @endforeach
                </select>
                @error('resource_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="purpose" class="form-label">Purpose/Task</label>
                <input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ old('purpose') }}" placeholder="e.g., Machining, stitching">
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="assigned_start_time" class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control @error('assigned_start_time') is-invalid @enderror" id="assigned_start_time" name="assigned_start_time" value="{{ old('assigned_start_time') }}" required>
                @error('assigned_start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="assigned_end_time" class="form-label">End Time</label>
                <input type="datetime-local" class="form-control @error('assigned_end_time') is-invalid @enderror" id="assigned_end_time" name="assigned_end_time" value="{{ old('assigned_end_time') }}" required>
                @error('assigned_end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Assign Resource</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
    window.calendarEvents = @json(
        collect($calendarData)->flatMap(function($data) {
            return collect($data['events'])->map(function($event) {
                return [
                    'title' => $event['title'],
                    'start' => $event['start'],
                    'end' => $event['end'],
                    'resourceId' => $event['resource_id'],
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#007bff',
                    'textColor' => '#ffffff',
                ];
            });
        })
    );

    window.calendarResources = @json(
        collect($resources)->map(function($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->name . ' (' . ucfirst($resource->type) . ')',
            ];
        })
    );
</script>

<script src="{{ asset('js/capacity_planning_calendar.js') }}"></script>
@endpush
