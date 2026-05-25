import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend,
    BarController,
    BarElement,
} from 'chart.js';

Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend,
    BarController,
    BarElement,
);

const formatRs = (v) => 'Rs ' + Number(v).toLocaleString(undefined, {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
});

export function mountRevenueChart() {
    const canvas = document.querySelector('[data-revenue-chart]');
    if (!canvas) return;

    let payload;
    try {
        payload = JSON.parse(canvas.getAttribute('data-series') || '{}');
    } catch {
        return;
    }
    const labels = payload.labels ?? [];
    const invoiceValues = payload.invoice_values ?? payload.values ?? [];
    const crValues = payload.cr_values ?? [];
    const totals = payload.values ?? invoiceValues;
    if (!labels.length) return;

    const ctx = canvas.getContext('2d');
    const gradientLine = ctx.createLinearGradient(0, 0, 0, canvas.height || 240);
    gradientLine.addColorStop(0, 'rgba(15, 23, 42, 0.18)');
    gradientLine.addColorStop(1, 'rgba(15, 23, 42, 0.0)');

    new Chart(ctx, {
        data: {
            labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Invoices',
                    data: invoiceValues,
                    backgroundColor: 'rgba(14, 165, 233, 0.65)',
                    hoverBackgroundColor: 'rgba(14, 165, 233, 0.9)',
                    borderRadius: 6,
                    borderSkipped: false,
                    stack: 'revenue',
                    barPercentage: 0.7,
                    categoryPercentage: 0.78,
                },
                {
                    type: 'bar',
                    label: 'CR Tasks',
                    data: crValues,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    hoverBackgroundColor: 'rgba(16, 185, 129, 0.95)',
                    borderRadius: 6,
                    borderSkipped: false,
                    stack: 'revenue',
                    barPercentage: 0.7,
                    categoryPercentage: 0.78,
                },
                {
                    type: 'line',
                    label: 'Total',
                    data: totals,
                    fill: true,
                    backgroundColor: gradientLine,
                    borderColor: '#0f172a',
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.32,
                    order: 0,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: '#475569',
                        boxWidth: 10,
                        boxHeight: 10,
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        font: { size: 11, weight: '600', family: 'Instrument Sans, system-ui, sans-serif' },
                        padding: 14,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(14, 165, 233, 0.4)',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: (ctx) => ` ${ctx.dataset.label}: ${formatRs(ctx.parsed.y)}`,
                    },
                },
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11, family: 'Instrument Sans, system-ui, sans-serif' },
                        maxRotation: 0,
                        autoSkipPadding: 14,
                    },
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11 },
                        callback: (v) => v >= 1000 ? 'Rs ' + (v / 1000).toFixed(v >= 10000 ? 0 : 1) + 'k' : 'Rs ' + v,
                    },
                },
            },
            animation: {
                duration: 900,
                easing: 'easeOutQuart',
            },
        },
    });
}
