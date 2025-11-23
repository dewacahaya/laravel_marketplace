// Monthly revenue line chart
const ctx1 = document.getElementById('monthlyRevenueChart');

if (ctx1) {
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: monthlyRevenueLabels,
            datasets: [{
                label: 'Revenue',
                data: monthlyRevenueData,
                borderWidth: 2,
                borderColor: '#4F46E5',
                fill: false,
                tension: 0.3
            }]
        }
    });
}

// Order status doughnut chart
const ctx2 = document.getElementById('orderStatusChart');

if (ctx2) {
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: orderStatusLabels,
            datasets: [{
                data: orderStatusData,
                backgroundColor: [
                    '#4F46E5', // purple
                    '#10B981', // green
                    '#3B82F6', // blue
                    '#F59E0B', // yellow
                    '#EF4444'  // red
                ]
            }]
        }
    });
}
