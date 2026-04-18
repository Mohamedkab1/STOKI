document.addEventListener('DOMContentLoaded', function () {
  const data = window.chartData;
  if (!data || !document.getElementById('mouvementsChart')) return;

  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const gridColor  = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
  const tickColor  = isDark ? '#94a3b8' : '#888888';

  new Chart(document.getElementById('mouvementsChart'), {
    type: 'line',
    data: {
      labels: data.labels,
      datasets: [
        {
          label: 'Entrées',
          data: data.entrees,
          borderColor: '#16a34a',
          backgroundColor: 'rgba(22,163,74,0.08)',
          borderWidth: 2,
          pointRadius: 3,
          pointHoverRadius: 5,
          pointBackgroundColor: '#16a34a',
          tension: 0.4,
          fill: true
        },
        {
          label: 'Sorties',
          data: data.sorties,
          borderColor: '#dc2626',
          backgroundColor: 'rgba(220,38,38,0.08)',
          borderWidth: 2,
          pointRadius: 3,
          pointHoverRadius: 5,
          pointBackgroundColor: '#dc2626',
          tension: 0.4,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: isDark ? '#1e293b' : '#ffffff',
          titleColor: isDark ? '#f1f5f9' : '#1a1a2e',
          bodyColor: isDark ? '#94a3b8' : '#6c757d',
          borderColor: isDark ? '#334155' : '#dee2e6',
          borderWidth: 1,
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y + ' unités'
          }
        }
      },
      scales: {
        x: {
          grid: { color: gridColor },
          ticks: { font: { size: 11 }, color: tickColor, maxTicksLimit: 10 }
        },
        y: {
          grid: { color: gridColor },
          ticks: { font: { size: 11 }, color: tickColor, stepSize: 5 },
          beginAtZero: true
        }
      }
    }
  });
});
