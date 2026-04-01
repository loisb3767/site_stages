const canvas = document.getElementById('chartDuree');
const labels = JSON.parse(canvas.dataset.labels);
const values = JSON.parse(canvas.dataset.values);

const ctx = canvas.getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: values,
            backgroundColor: ['#00a5cf', '#004e64', '#58c7e7'],
            borderColor: '#0f262c',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});