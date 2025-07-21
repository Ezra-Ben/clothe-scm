<div class="card mb-4">
  <div class="card-body fixed-chart-height">
    <h5 class="card-title">Customer Segment Distribution</h5>
    <canvas id="segmentChart"></canvas>
  </div>
</div>

@push('scripts')
<script>
const ctxPie = document.getElementById('segmentChart').getContext('2d');
new Chart(ctxPie, {
  type: 'doughnut',
  data: {
    labels: ['Seasonal', 'High Value', 'Occasional'],
    datasets: [{
      data: [
        {{ $segmentCounts['0'] ?? 0 }},
        {{ $segmentCounts['1'] ?? 0 }},
        {{ $segmentCounts['2'] ?? 0 }}
      ],
      backgroundColor: ['#6c757d','#198754','#ffc107']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
@endpush