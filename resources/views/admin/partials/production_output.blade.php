<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Production Output (Weekly)</h5>
    <canvas id="productionChart"></canvas>
  </div>
</div>

<script>
const ctxProd = document.getElementById('productionChart').getContext('2d');

const productionData = {!! json_encode($weeklyProductionData) !!};

const labels = Object.keys(productionData);
const completedData = labels.map(week => productionData[week]['completed'] || 0);
const pendingData = labels.map(week => productionData[week]['pending'] || 0);
const failedData = labels.map(week => productionData[week]['failed'] || 0);

new Chart(ctxProd, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Completed',
        data: completedData,
        backgroundColor: '#198754'
      },
      {
        label: 'Pending',
        data: pendingData,
        backgroundColor: '#ffc107'
      },
      {
        label: 'Failed',
        data: failedData,
        backgroundColor: '#dc3545'
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      x: { stacked: true },
      y: { stacked: true, beginAtZero: true }
    },
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
