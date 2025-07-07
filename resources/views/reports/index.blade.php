@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Reports & Analytics', 'url' => route('reports.index')]
    ]" />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-chart-line me-2"></i>Reports & Analytics
            </h1>
            <p class="text-muted mb-0">Comprehensive insights into your supply chain performance</p>
        </div>
        <div class="d-flex gap-2">
            <x-export-buttons :route="route('reports.export')" />
            <div class="btn-group">
                <a href="{{ route('reports.product-performance') }}" class="btn btn-outline-primary">
                    <i class="fas fa-box me-1"></i>Product Performance
                </a>
                <a href="{{ route('reports.quality-report') }}" class="btn btn-outline-success">
                    <i class="fas fa-check-circle me-1"></i>Quality Report
                </a>
                <a href="{{ route('reports.production-efficiency') }}" class="btn btn-outline-info">
                    <i class="fas fa-industry me-1"></i>Production Efficiency
                </a>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <x-dashboard-widget 
                title="Total Products" 
                value="{{ $stats['total_products'] }}" 
                icon="box" 
                color="primary" />
        </div>
        <div class="col-md-2">
            <x-dashboard-widget 
                title="Production Batches" 
                value="{{ $stats['total_batches'] }}" 
                icon="industry" 
                color="info" />
        </div>
        <div class="col-md-2">
            <x-dashboard-widget 
                title="QC Records" 
                value="{{ $stats['total_qc_records'] }}" 
                icon="check-circle" 
                color="success" />
        </div>
        <div class="col-md-2">
            <x-dashboard-widget 
                title="Low Stock Items" 
                value="{{ $stats['low_stock_products'] }}" 
                icon="exclamation-triangle" 
                color="warning" />
        </div>
        <div class="col-md-2">
            <x-dashboard-widget 
                title="Completed Batches" 
                value="{{ $stats['completed_batches'] }}" 
                icon="check-double" 
                color="success" />
        </div>
        <div class="col-md-2">
            <x-dashboard-widget 
                title="Passed QC" 
                value="{{ $stats['passed_qc'] }}" 
                icon="award" 
                color="success" />
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Monthly Trends Chart -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>Monthly Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock Levels Chart -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Stock Levels
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="stockLevelsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row g-4">
        <!-- Quality Control Statistics -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>Quality Control Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <h4 class="text-success mb-1">{{ $qcStats['passed'] }}</h4>
                                <small class="text-muted">Passed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                <h4 class="text-danger mb-1">{{ $qcStats['failed'] }}</h4>
                                <small class="text-muted">Failed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                <h4 class="text-warning mb-1">{{ $qcStats['pending'] }}</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <h4 class="text-info mb-1">{{ $qcStats['in_progress'] }}</h4>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Efficiency -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-industry me-2"></i>Production Efficiency
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Completion Rate</span>
                            <span class="fw-bold">{{ $productionEfficiency['completion_rate'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $productionEfficiency['completion_rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Total Batches</small>
                            <div class="fw-bold">{{ $productionEfficiency['total_batches'] }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Completed</small>
                            <div class="fw-bold text-success">{{ $productionEfficiency['completed_batches'] }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Pending</small>
                            <div class="fw-bold text-warning">{{ $productionEfficiency['pending_batches'] }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">In Progress</small>
                            <div class="fw-bold text-info">{{ $productionEfficiency['in_progress_batches'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const monthlyData = @json($monthlyData);
    const ctx1 = document.getElementById('monthlyTrendsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Products',
                data: monthlyData.map(item => item.products),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Batches',
                data: monthlyData.map(item => item.batches),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }, {
                label: 'QC Records',
                data: monthlyData.map(item => item.qc_records),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
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

    // Stock Levels Chart
    const stockData = @json($stockLevels);
    const ctx2 = document.getElementById('stockLevelsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [stockData.in_stock, stockData.low_stock, stockData.out_of_stock],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
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
});
</script>
@endsection 