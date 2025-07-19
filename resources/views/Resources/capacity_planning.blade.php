@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Resource Capacity Planning</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Assign Resource to Batch
    </div>
    <div class="card-body">
        <form action="{{ route('capacity_planning.assign') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label for="batch_id" class="form-label">Select Batch</label>
                <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id" name="batch_id" required>
                    <option value="">Choose...</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>Batch #{{ $batch->id }} ({{ $batch->product->name ?? 'N/A' }})</option>
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
                        <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : '' }}>{{ $resource->name }} ({{ ucfirst($resource->type) }})</option>
                    @endforeach
                </select>
                @error('resource_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="purpose" class="form-label">Purpose/Task</label>
                <input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ old('purpose') }}" placeholder="e.g., Machining,stiching">
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

<div class="card shadow-sm border-primary">
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

@push('scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Prepare events for FullCalendar
        var events = [];
        @foreach($calendarData as $resourceId => $data)
            @foreach($data['events'] as $event)
                events.push({
                    title: '{{ $event['title'] }}',
                    start: '{{ $event['start'] }}',
                    end: '{{ $event['end'] }}',
                    resourceId: '{{ $event['resource_id'] }}',
                    // Add more properties for custom rendering or info
                    backgroundColor: '#007bff', // Blue
                    borderColor: '#007bff',
                    textColor: '#ffffff'
                });
            @endforeach
        @endforeach

        // Prepare resources for FullCalendar
        var resources = [
            @foreach($resources as $resource)
                {
                    id: '{{ $resource->id }}',
                    title: '{{ $resource->name }} ({{ ucfirst($resource->type) }})',
                },
            @endforeach
        ];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'resourceTimeGridDay', // Or 'resourceTimeGridWeek'
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'resourceTimeGridDay,resourceTimeGridWeek,dayGridMonth'
            },
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives', // For commercial use, you'd need a license
            resources: resources,
            events: events,
            editable: true, // Enable dragging and resizing events (requires eventDrop and eventResize callbacks)
            eventColor: '#378006', // Default event color
            resourceAreaWidth: '15%', // Adjust width for resource names

            // Handle event drops (rescheduling)
            eventDrop: function(info) {
                if (!confirm("Are you sure you want to reschedule " + info.event.title + "?")) {
                    info.revert();
                } else {
                    // Send AJAX request to update assignment in database
                    // axios.put('/resource-assignments/' + info.event.id, {
                    //     start: info.event.startStr,
                    //     end: info.event.endStr,
                    //     resource_id: info.newResource.id // If changed resource
                    // }).then(response => {
                    //     console.log('Assignment updated!');
                    // }).catch(error => {
                    //     console.error('Error updating assignment:', error);
                    //     info.revert(); // Revert if AJAX fails
                    // });
                    alert('Assignment rescheduled! (Implement AJAX call to save)');
                }
            },
            // Handle event resizing
            eventResize: function(info) {
                if (!confirm("Are you sure you want to resize " + info.event.title + "?")) {
                    info.revert();
                } else {
                    alert('Assignment duration changed! (Implement AJAX call to save)');
                }
            },
            eventDidMount: function(info) {
                // Example: Add a tooltip or custom styling based on event data
                // info.el.querySelector('.fc-event-title').innerHTML += `<br>Batch: ${info.event.extendedProps.batch_id}`;
            },
            slotMinTime: "06:00:00", // Start time for the day view
            slotMaxTime: "22:00:00", // End time for the day view
            nowIndicator: true, // Show current time
        });

        calendar.render();
    });
</script>
@endpush
@endsection