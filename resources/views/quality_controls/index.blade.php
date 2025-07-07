@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Quality Controls', 'url' => route('quality-controls.index')]
    ]" />
    
    <x-form-success />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-check-circle me-2"></i>Quality Controls
            </h1>
            <p class="text-muted mb-0">Monitor and manage quality control processes</p>
        </div>
        <div>
            <a href="{{ route('quality-controls.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Create QC
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('quality-controls.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search QC Records</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by batch number or tester...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="status_filter" class="form-label">Status</label>
                    <select class="form-select" id="status_filter" name="status_filter">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status_filter') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="passed" {{ request('status_filter') == 'passed' ? 'selected' : '' }}>Passed</option>
                        <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_filter" class="form-label">Test Date</label>
                    <select class="form-select" id="date_filter" name="date_filter">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
            @if(request('search') || request('status_filter') || request('date_filter'))
                <div class="mt-3">
                    <a href="{{ route('quality-controls.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                    <small class="text-muted ms-2">
                        Showing {{ $qualityControls->count() }} of {{ $qualityControls->total() }} QC records
                    </small>
                </div>
            @endif
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive" aria-label="Quality Control Records Table">
                <table class="table table-hover" aria-describedby="qc-table-description">
                    <caption id="qc-table-description" class="visually-hidden">List of quality control records with status, tester, and actions</caption>
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Batch</th>
                            <th>Tester</th>
                            <th>Status</th>
                            <th>Tested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qualityControls as $qc)
                            <tr>
                                <td>{{ $qc->id }}</td>
                                <td>
                                    <code>{{ $qc->productionBatch->batch_number ?? 'N/A' }}</code>
                                    @if($qc->productionBatch)
                                        <br><small class="text-muted">Product: {{ $qc->productionBatch->product->name ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $qc->tester->name ?? 'N/A' }}</strong>
                                    @if($qc->tester)
                                        <br><small class="text-muted">{{ $qc->tester->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning',
                                            'in_progress' => 'bg-info',
                                            'passed' => 'bg-success',
                                            'failed' => 'bg-danger'
                                        ];
                                        $statusTooltips = [
                                            'pending' => 'Pending: QC not yet performed',
                                            'in_progress' => 'In Progress: QC ongoing',
                                            'passed' => 'Passed: QC successful',
                                            'failed' => 'Failed: QC did not pass'
                                        ];
                                        $statusColor = $statusColors[$qc->status] ?? 'bg-secondary';
                                        $statusTooltip = $statusTooltips[$qc->status] ?? ucfirst($qc->status);
                                    @endphp
                                    <span class="badge {{ $statusColor }}" tabindex="0" aria-label="Status: {{ ucfirst(str_replace('_', ' ', $qc->status)) }}" data-bs-toggle="tooltip" title="{{ $statusTooltip }}">{{ ucfirst(str_replace('_', ' ', $qc->status)) }}</span>
                                </td>
                                <td>
                                    @if($qc->tested_at)
                                        <small>{{ \Carbon\Carbon::parse($qc->tested_at)->format('M d, Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">Not tested</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Quality Control Actions">
                                        <a href="{{ route('quality-controls.show', $qc) }}" class="btn btn-outline-primary btn-sm" title="View details" aria-label="View details">
                                            <i class="fas fa-eye" data-bs-toggle="tooltip" title="View"></i>
                                        </a>
                                        <a href="{{ route('quality-controls.edit', $qc) }}" class="btn btn-outline-warning btn-sm" title="Edit record" aria-label="Edit record">
                                            <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit"></i>
                                        </a>
                                        <form action="{{ route('quality-controls.destroy', $qc) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete record" aria-label="Delete record" onclick="return confirm('Are you sure you want to delete this quality control?')">
                                                <i class="fas fa-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                                        <p>No quality controls found.</p>
                                        <a href="{{ route('quality-controls.create') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-1"></i>Create your first QC
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($qualityControls->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $qualityControls->appends(request()->query())->links() }}
        </div>
    @endif
    
    <!-- Navigation buttons at the bottom -->
    <div class="mt-4 text-center">
        <div class="btn-group" role="group">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-box me-1"></i>Products
            </a>
            <a href="{{ route('production-batches.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-industry me-1"></i>Production Batches
            </a>
        </div>
    </div>
</div>
@endsection 