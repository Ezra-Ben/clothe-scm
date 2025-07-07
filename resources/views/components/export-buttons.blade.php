@props(['route', 'format' => 'csv'])

<div class="btn-group btn-group-sm" role="group">
    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-download me-1"></i>Export
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ $route }}?{{ request()->getQueryString() }}&export=csv">
                <i class="fas fa-file-csv me-2"></i>Export as CSV
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $route }}?{{ request()->getQueryString() }}&export=pdf">
                <i class="fas fa-file-pdf me-2"></i>Export as PDF
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $route }}?{{ request()->getQueryString() }}&export=excel">
                <i class="fas fa-file-excel me-2"></i>Export as Excel
            </a>
        </li>
    </ul>
</div> 