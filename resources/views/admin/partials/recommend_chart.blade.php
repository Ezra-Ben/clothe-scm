<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Top Products' Recommendation Sales (Monthly)</h5>
    <canvas id="recommendChart"></canvas>
  </div>
</div>

@push('scripts')
<script>
    const recommendCtx = document.getElementById('recommendChart').getContext('2d');

    const months = {!! json_encode(collect(range(1, 12))->map(fn($m) => date("M", mktime(0, 0, 0, $m, 1)))) !!};
    const productIds = {!! json_encode($topProductIds) !!};
    const productNames = {!! json_encode($topProducts) !!};

    const salesData = {};
    const forecastsData = {};

    productIds.forEach(id => {
        salesData[id] = Array(12).fill(0);
        forecastsData[id] = Array(12).fill(0);
    });

    @foreach($productSales as $row)
        salesData[{{ $row->product_id }}][{{ $row->month - 1 }}] = {{ round($row->total) }};
    @endforeach

    @foreach($productForecasts as $row)
        forecastsData[{{ $row->product_id }}][{{ $row->month - 1 }}] = {{ round($row->total) }};
    @endforeach

    const colorPalette = [
        '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd',
        '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'
    ];

    const datasets = [];

    productIds.forEach((id, index) => {
        const color = colorPalette[index % colorPalette.length];
        const name = productNames[id] || `Product ${id}`;
            datasets.push({
                label: `${name} (Actual)`,
                data: salesData[id],
                borderColor: color,
                fill: false,
                tension: 0.3
            });
            datasets.push({
                label: `${name} (Forecast)`,
                data: forecastsData[id],
                borderColor: color,
                borderDash: [5, 5],
                fill: false,
                tension: 0.3
            });
        });

        new Chart(recommendCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            }
        });
</script>
@endpush
