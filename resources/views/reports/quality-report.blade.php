@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Reports & Analytics', 'url' => route('reports.index')],
        ['label' => 'Quality Report', 'url' => route('reports.quality-report')]
    ]" />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-check-circle me-2"></i>Quality Control Report
            </h1>
            <p class="text-muted mb-0">Comprehensive quality control analysis and trends</p>
        </div>
        <div class="d-flex gap-2">
            <x-export-buttons :route="route('reports.export')" />
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Analytics
            </a>
        </div>
    </div>

    <!-- Quality Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-double fa-2x text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ $qcData->where('status', 'passed')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">Passed Tests</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <h4 class="mb-1">{{ $qcData->where('status', 'failed')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">Failed Tests</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ $qcData->where('status', 'pending')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">Pending Tests</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-spinner fa-2x text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ $qcData->where('status', 'in_progress')->first()->count ?? 0 }}</h4>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Quality Status Distribution -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Quality Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="qualityStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Quality Trends -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Monthly Quality Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyQualityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quality Metrics -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quality Metrics
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalQC = $qcData->sum('count');
                        $passedQC = $qcData->where('status', 'passed')->first()->count ?? 0;
                        $failedQC = $qcData->where('status', 'failed')->first()->count ?? 0;
                        $passRate = $totalQC > 0 ? round(($passedQC / $totalQC) * 100, 2) : 0;
                        $failRate = $totalQC > 0 ? round(($failedQC / $totalQC) * 100, 2) : 0;
                    @endphp
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="text-center p-4 bg-success bg-opacity-10 rounded">
                                <h3 class="text-success mb-2">{{ $passRate }}%</h3>
                                <p class="mb-0">Pass Rate</p>
                                <small class="text-muted">{{ $passedQC }} out of {{ $totalQC }} tests</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-4 bg-danger bg-opacity-10 rounded">
                                <h3 class="text-danger mb-2">{{ $failRate }}%</h3>
                                <p class="mb-0">Fail Rate</p>
                                <small class="text-muted">{{ $failedQC }} out of {{ $totalQC }} tests</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Quality Insights</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>High Pass Rate:</strong> {{ $passRate }}% of quality tests are passing
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <strong>Attention Needed:</strong> {{ $failRate }}% failure rate requires investigation
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-chart-line text-info me-2"></i>
                                <strong>Trend Analysis:</strong> Monthly quality trends show consistent patterns
                            </li>
                            <li>
                                <i class="fas fa-lightbulb text-primary me-2"></i>
                                <strong>Recommendation:</strong> Focus on reducing failure rates through process improvements
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
                        <i class="fas fa-list me-2"></i>Quality Status Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($qcData as $status)
                        @php
                            $statusColors = [
                                'passed' => 'success',
                                'failed' => 'danger',
                                'pending' => 'warning',
                                'in_progress' => 'info'
                            ];
                            $statusColor = $statusColors[$status->status] ?? 'secondary';
                            $percentage = $totalQC > 0 ? round(($status->count / $totalQC) * 100, 1) : 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong class="text-capitalize">{{ str_replace('_', ' ', $status->status) }}</strong>
                                <br><small class="text-muted">{{ $status->count }} records</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $statusColor }}">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $percentage }}%"></div>
                        </div>
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
    // Quality Status Distribution Chart
    const qcData = @json($qcData);
    const ctx1 = document.getElementById('qualityStatusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: qcData.map(item => item.status.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: qcData.map(item => item.count),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
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

    // Monthly Quality Trends Chart
    const monthlyQC = @json($monthlyQC);
    const ctx2 = document.getElementById('monthlyQualityChart').getContext('2d');
    
    // Group data by month
    const monthlyData = {};
    monthlyQC.forEach(item => {
        const monthKey = `${item.year}-${item.month}`;
        if (!monthlyData[monthKey]) {
            monthlyData[monthKey] = { passed: 0, failed: 0, pending: 0, in_progress: 0 };
        }
        monthlyData[monthKey][item.status] = item.count;
    });

    const months = Object.keys(monthlyData).sort();
    const passedData = months.map(month => monthlyData[month].passed);
    const failedData = months.map(month => monthlyData[month].failed);
    const pendingData = months.map(month => monthlyData[month].pending);

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months.map(month => {
                const [year, monthNum] = month.split('-');
                return new Date(year, monthNum - 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Passed',
                data: passedData,
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
            }, {
                label: 'Failed',
                data: failedData,
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
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