document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('resourceUtilizationChart');
    const data = window.utilizationData || [];

    if (!ctx || !data.length) return;

    const labels = data.map(item => item.name);
    const utilizationRates = data.map(item => item.utilization_rate);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Utilization Rate (%)',
                data: utilizationRates,
                backgroundColor: utilizationRates.map(rate =>
                    rate > 80 ? 'rgba(220, 53, 69, 0.7)' :
                    rate > 50 ? 'rgba(255, 193, 7, 0.7)' :
                    'rgba(25, 135, 84, 0.7)'
                ),
                borderColor: utilizationRates.map(rate =>
                    rate > 80 ? 'rgba(220, 53, 69, 1)' :
                    rate > 50 ? 'rgba(255, 193, 7, 1)' :
                    'rgba(25, 135, 84, 1)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Utilization Rate (%)' }
                },
                x: {
                    title: { display: true, text: 'Resource' }
                }
            },
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Resource Utilization by Resource' }
            }
        }
    });
});
