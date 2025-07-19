<div>
    <canvas id="salesChart"></canvas>
</div>

<script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    const months = {!! json_encode(collect(range(1, 12))->map(fn($m) => date("M", mktime(0, 0, 0, $m, 1)))) !!};

    const actualData = Array(12).fill(0);
    const forecastData = Array(12).fill(0);

    @foreach($actualSales as $s)
        actualData[{{ $s->month - 1 }}] += {{ round($s->total) }};
    @endforeach

    @foreach($forecastedSales as $s)
        forecastData[{{ $s->month - 1 }}] += {{ round($s->total) }};
    @endforeach

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Actual Sales',
                    data: actualData,
                    borderColor: 'blue',
                    tension: 0.3
                },
                {
                    label: 'Forecasted Sales',
                    data: forecastData,
                    borderColor: 'orange',
                    borderDash: [5, 5],
                    tension: 0.3
                }
            ]
        }
    });
</script>
