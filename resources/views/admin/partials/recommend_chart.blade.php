<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Product Recommendation Sales (Monthly)</h5>
    <canvas id="recommendChart"></canvas>
  </div>
</div>

@push('scripts')
<script>
    const recommendCtx = document.getElementById('recommendChart').getContext('2d');

    const months = {!! json_encode(collect(range(1, 12))->map(fn($m) => date("M", mktime(0, 0, 0, $m, 1)))) !!};
    const productIds = {!! json_encode($topProductIds) !!};

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

    const datasets = [];

    productIds.forEach(id => {
        datasets.push({
            label: `Product ${id} (Actual)`,
            data: salesData[id],
            borderColor: 'blue',
            fill: false,
            tension: 0.3
        });
        datasets.push({
            label: `Product ${id} (Forecast)`,
            data: forecastsData[id],
            borderColor: 'orange',
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
