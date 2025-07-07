@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Reports & Analytics', 'url' => route('reports.index')],
        ['label' => 'Production Efficiency', 'url' => route('reports.production-efficiency')]
    ]" />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-industry me-2"></i>Production Efficiency Report
            </h1>
            <p class="text-muted mb-0">Analysis of production batch efficiency and performance metrics</p>
        </div>
        <div class="d-flex gap-2">
            <x-export-buttons :route="route('reports.export')" />
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Analytics
            </a>
        </div>
    </div>

    <!-- Efficiency Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-double fa-2x text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ $efficiencyData->where('status', 'completed')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">Completed Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-spinner fa-2x text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ $efficiencyData->where('status', 'in_progress')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ $efficiencyData->where('status', 'pending')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">Pending Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($efficiencyData->avg('avg_quantity') ?? 0, 0) }}</h4>
                    <small class="text-muted">Avg Batch Size</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Production Status Distribution -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Production Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="productionStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Production Trends -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Monthly Production Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyProductionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Efficiency Metrics -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Efficiency Metrics
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalBatches = $efficiencyData->sum('count');
                        $completedBatches = $efficiencyData->where('status', 'completed')->first()->count ?? 0;
                        $inProgressBatches = $efficiencyData->where('status', 'in_progress')->first()->count ?? 0;
                        $pendingBatches = $efficiencyData->where('status', 'pending')->first()->count ?? 0;
                        
                        $completionRate = $totalBatches > 0 ? round(($completedBatches / $totalBatches) * 100, 2) : 0;
                        $progressRate = $totalBatches > 0 ? round((($completedBatches + $inProgressBatches) / $totalBatches) * 100, 2) : 0;
                    @endphp
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-success bg-opacity-10 rounded">
                                <h3 class="text-success mb-2">{{ $completionRate }}%</h3>
                                <p class="mb-0">Completion Rate</p>
                                <small class="text-muted">{{ $completedBatches }} completed</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-info bg-opacity-10 rounded">
                                <h3 class="text-info mb-2">{{ $progressRate }}%</h3>
                                <p class="mb-0">Progress Rate</p>
                                <small class="text-muted">Including in-progress</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-warning bg-opacity-10 rounded">
                                <h3 class="text-warning mb-2">{{ $pendingBatches }}</h3>
                                <p class="mb-0">Pending Batches</p>
                                <small class="text-muted">Awaiting start</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Efficiency Insights</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>High Completion Rate:</strong> {{ $completionRate }}% of batches are completed successfully
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-chart-line text-info me-2"></i>
                                <strong>Good Progress:</strong> {{ $progressRate }}% of batches are either completed or in progress
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <strong>Queue Management:</strong> {{ $pendingBatches }} batches are waiting to start
                            </li>
                            <li>
                                <i class="fas fa-lightbulb text-primary me-2"></i>
                                <strong>Recommendation:</strong> Focus on reducing pending batches to improve overall efficiency
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Production Status Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($efficiencyData as $status)
                        @php
                            $statusColors = [
                                'completed' => 'success',
                                'in_progress' => 'info',
                                'pending' => 'warning',
                                'cancelled' => 'danger'
                            ];
                            $statusColor = $statusColors[$status->status] ?? 'secondary';
                            $percentage = $totalBatches > 0 ? round(($status->count / $totalBatches) * 100, 1) : 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong class="text-capitalize">{{ str_replace('_', ' ', $status->status) }}</strong>
                                <br><small class="text-muted">{{ $status->count }} batches</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $statusColor }}">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $percentage }}%"></div>
                        </div>
                        @if($status->avg_quantity)
                            <small class="text-muted">Avg Quantity: {{ number_format($status->avg_quantity, 0) }}</small>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Production Status Distribution Chart
    const efficiencyData = @json($efficiencyData);
    const ctx1 = document.getElementById('productionStatusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: efficiencyData.map(item => item.status.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: efficiencyData.map(item => item.count),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Monthly Production Trends Chart
    const monthlyProduction = @json($monthlyProduction);
    const ctx2 = document.getElementById('monthlyProductionChart').getContext('2d');
    
    // Group data by month
    const monthlyData = {};
    monthlyProduction.forEach(item => {
        const monthKey = `${item.year}-${item.month}`;
        if (!monthlyData[monthKey]) {
            monthlyData[monthKey] = { completed: 0, in_progress: 0, pending: 0, total_quantity: 0 };
        }
        monthlyData[monthKey][item.status] = item.count;
        monthlyData[monthKey].total_quantity += item.total_quantity;
    });

    const months = Object.keys(monthlyData).sort();
    const completedData = months.map(month => monthlyData[month].completed);
    const inProgressData = months.map(month => monthlyData[month].in_progress);
    const pendingData = months.map(month => monthlyData[month].pending);

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months.map(month => {
                const [year, monthNum] = month.split('-');
                return new Date(year, monthNum - 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Completed',
                data: completedData,
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
            }, {
                label: 'In Progress',
                data: inProgressData,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
            }, {
                label: 'Pending',
                data: pendingData,
                backgroundColor: 'rgba(255, 205, 86, 0.8)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection 