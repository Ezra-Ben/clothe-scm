@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Resource Utilization Report</h1>

<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Filter Report
    </div>
    <div class="card-body">
        <form action="{{ route('reports.resource_utilization') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label for="resource_type" class="form-label">Resource Type</label>
                <select class="form-select" id="resource_type" name="resource_type">
                    <option value="">All Types</option>
                    <option value="machine" {{ request('resource_type') == 'machine' ? 'selected' : '' }}>Machine</option>
                    <option value="labor" {{ request('resource_type') == 'labor' ? 'selected' : '' }}>Labor</option>
                    <option value="workstation" {{ request('resource_type') == 'workstation' ? 'selected' : '' }}>Workstation</option>
                    {{-- Add more types as defined in your Resource model --}}
                </select>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('reports.resource_utilization') }}" class="btn btn-outline-secondary">Reset Filters</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Resource Utilization Overview
    </div>
    <div class="card-body">
        @if(empty($utilizationData))
            <p class="text-center">No utilization data available for the selected filters.
                <br>Ensure resources have assignments within the specified date range.</p>
        @else
            <div class="mb-4">
                <canvas id="resourceUtilizationChart" style="max-height: 400px;"></canvas>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Resource Name</th>
                            <th>Resource Type</th>
                            <th>Total Available Hours</th>
                            <th>Assigned Hours</th>
                            <th>Utilization Rate (%)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($utilizationData as $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td>{{ ucfirst($data['type']) }}</td>
                                <td>{{ number_format($data['available_hours'], 2) }}</td>
                                <td>{{ number_format($data['assigned_hours'], 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $data['utilization_rate'] > 80 ? 'danger' : ($data['utilization_rate'] > 50 ? 'warning' : 'success') }}">
                                        {{ number_format($data['utilization_rate'], 2) }}%
                                    </span>
                                </td>
                                <td><span class="badge bg-{{ $data['status'] == 'available' ? 'success' : ($data['status'] == 'in_use' ? 'info' : 'secondary') }}">{{ ucfirst(str_replace('_', ' ', $data['status'])) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('resourceUtilizationChart');
        if (ctx && @json(!empty($utilizationData))) {
            const labels = @json(array_column($utilizationData, 'name'));
            const utilizationRates = @json(array_column($utilizationData, 'utilization_rate'));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Utilization Rate (%)',
                        data: utilizationRates,
                        backgroundColor: utilizationRates.map(rate => {
                            if (rate > 80) return 'rgba(220, 53, 69, 0.7)'; // Danger Red
                            if (rate > 50) return 'rgba(255, 193, 7, 0.7)';  // Warning Yellow
                            return 'rgba(25, 135, 84, 0.7)'; // Success Green
                        }),
                        borderColor: utilizationRates.map(rate => {
                            if (rate > 80) return 'rgba(220, 53, 69, 1)';
                            if (rate > 50) return 'rgba(255, 193, 7, 1)';
                            return 'rgba(25, 135, 84, 1)';
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Utilization Rate (%)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Resource'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: 'Resource Utilization by Resource'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection