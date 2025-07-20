@extends('layouts.app')

@section('title', 'Production Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h1 class="mb-4 text-primary">Production Overview</h1>
     {{-- Navigation Buttons --}}
    <div class="d-flex justify-content-start mb-4 flex-wrap"> 
        
        <a href="{{ route('boms.index') }}" class="btn btn-outline-warning me-2 mb-2">
            <i class="fas fa-list-alt me-1"></i> BOM Management
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-success me-2 mb-2">  
            <i class="fas fa-chart-bar me-1"></i> Production Reports
        </a>
                <a href="{{ route('resources.index') }}" class="btn btn-outline-success me-2 mb-2">  
            <i class="fas fa-chart-bar me-1"></i> Resources Allocation
        </a>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Orders in Production</h5>
                    <p class="card-text display-4 text-primary" id="ordersInProductionCount">{{ $ordersInProduction }}</p>
                    <a href="{{ route('production.orders') }}" class="btn btn-outline-primary btn-sm mt-2">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Batches in Progress</h5>
                    <p class="card-text display-4 text-info" id="batchesInProgressCount">{{ $batchesInProgress }}</p>
                    <a href="{{ route('production.batches') }}" class="btn btn-outline-info btn-sm mt-2">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Active and Upcoming Schedules</h5>
                    <p class="card-text display-4 text-success" id="totalRelevantSchedulesCount">{{ $totalRelevantSchedulesCount }}</p>
                    <a href="{{ route('production.schedules') }}" class="btn btn-outline-success btn-sm mt-2">View Details</a>
                </div>
            </div>
        </div>
    </div>
         
    <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Recent Production Activities</h5>
</div>
<div class="card-body">
    <ul class="list-group list-group-flush" id="recentActivitiesList">
        @forelse($recentActivities as $activity)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $activity->description }}</strong><br>
                    <small class="text-muted">
                        {{ $activity->type }} - {{ $activity->timestamp->format('Y-m-d H:i') }}
                        @if($activity->batch) (Batch: {{ $activity->batch->id }})
                        @endif
                    </small>
                </div>
            
            </li>
        @empty
            <li class="list-group-item text-center text-muted">No recent production activities to display.</li>
        @endforelse
    </ul>
</div>

  <div class="card-header bg-info text-white"> <h5 class="mb-0">Production Bottlenecks</h5>
</div>
<div class="card-body">
    <ul class="list-group list-group-flush" id="bottlenecksList">
        @forelse($bottlenecks as $bottleneck)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $bottleneck['type'] }}</strong><br>
                    <small class="text-muted">{{ $bottleneck['description'] }}</small>
                </div>
                {{-- Bootstrap badge to show severity --}}
                <span class="badge {{ $bottleneck['severity'] == 'High' ? 'bg-danger' : ($bottleneck['severity'] == 'Medium' ? 'bg-warning' : 'bg-secondary') }} rounded-pill">{{ $bottleneck['severity'] }}</span>
            </li>
        @empty
            <li class="list-group-item text-center text-muted">No identified bottlenecks.</li>
        @endforelse
    </ul>
</div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/production/dashboard-counts')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ordersInProductionCount').innerText = data.orders_in_production;
                document.getElementById('batchesInProgressCount').innerText = data.batches_in_progress;
                document.getElementById('upcomingSchedulesCount').innerText = data.upcoming_schedules;
            })
            .catch(error => console.error('Error fetching dashboard counts:', error));

        fetch('/api/production/recent-activities')
            .then(response => response.json())
            .then(data => {
                const activitiesList = document.getElementById('recentActivitiesList');
                activitiesList.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(activity => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.innerText = `${new Date(activity.timestamp).toLocaleString()}: ${activity.description} (Batch: ${activity.batch_id})`;
                        activitiesList.appendChild(li);
                    });
                } else {
                    activitiesList.innerHTML = '<li class="list-group-item text-muted">No recent activities to display.</li>';
                }
            })
            .catch(error => console.error('Error fetching recent activities:', error));
    });
</script>
@endpush