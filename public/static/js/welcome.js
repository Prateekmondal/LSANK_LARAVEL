document.addEventListener('DOMContentLoaded', function () {
    // Shared chart font configuration
    const chartFontConfig = {
        family: "'Inter', sans-serif",
        size: 12,
        weight: '500'
    };

    // 1. Job Statistics Pie Chart
    const pieCtx = document.getElementById('jobStatsChartpie');
    if (pieCtx) {
        const jobStatsData = {
            labels: ['Open Hole Logging', 'Cased Hole Logging', 'Production Logging'],
            datasets: [{
                data: [180, 780, 50],
                backgroundColor: [
                    '#ff9500', // ONGC Amber
                    '#00e5ff', // Electric Cyan
                    '#8b5cf6'  // Electric Purple
                ],
                borderWidth: 2,
                borderColor: '#0f131a', // Match card surface
                hoverOffset: 15
            }]
        };

        const jobStatsChartpie = new Chart(pieCtx.getContext('2d'), {
            type: 'doughnut',
            data: jobStatsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        bodyFont: chartFontConfig,
                        titleFont: { ...chartFontConfig, weight: '700' },
                        padding: 12,
                        borderRadius: 8,
                        borderColor: 'rgba(0, 229, 255, 0.2)',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return ` ${label}: ${value} jobs (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Create beautiful custom legend
        const chartLegend = document.getElementById('chartLegend');
        if (chartLegend) {
            chartLegend.innerHTML = ''; // Clear original
            jobStatsData.labels.forEach((label, i) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';

                const legendColor = document.createElement('div');
                legendColor.className = 'legend-color';
                legendColor.style.backgroundColor = jobStatsData.datasets[0].backgroundColor[i];
                legendColor.style.boxShadow = `0 0 10px ${jobStatsData.datasets[0].backgroundColor[i]}80`;

                const legendText = document.createElement('span');
                legendText.textContent = `${label}: ${jobStatsData.datasets[0].data[i]} jobs`;

                legendItem.appendChild(legendColor);
                legendItem.appendChild(legendText);
                chartLegend.appendChild(legendItem);
            });
        }
    }

    // 2. Job Support Bar Chart
    const barCtx = document.getElementById('jobStatsChartbar');
    if (barCtx) {
        const jobStatsDatabar = {
            labels: ['Departmental', 'Contractual'],
            datasets: [{
                data: [750, 250],
                backgroundColor: [
                    '#00e5ff', // Electric Cyan
                    '#ff9500'  // ONGC Amber
                ],
                borderWidth: 0,
                borderRadius: 8,
                barPercentage: 0.6,
                maxBarThickness: 45
            }]
        };

        const jobStatsChartbar = new Chart(barCtx.getContext('2d'), {
            type: 'bar',
            data: jobStatsDatabar,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        bodyFont: chartFontConfig,
                        titleFont: { ...chartFontConfig, weight: '700' },
                        padding: 12,
                        borderRadius: 8,
                        borderColor: 'rgba(255, 149, 0, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return ` ${value} Jobs (${percentage}%)`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: chartFontConfig
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: chartFontConfig
                        }
                    }
                }
            }
        });
    }

    // 3. Scroll Reveal Interactions
    const revealElements = document.querySelectorAll('.reveal-init');
    const checkReveal = () => {
        const triggerBottom = window.innerHeight * 0.85;
        revealElements.forEach(el => {
            const elTop = el.getBoundingClientRect().top;
            if (elTop < triggerBottom) {
                el.classList.add('reveal-active');
            }
        });
    };

    window.addEventListener('scroll', checkReveal);
    checkReveal(); // Trigger once on mount

    // 4. Smooth Anchor Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const targetEl = document.querySelector(href);
            if (targetEl) {
                e.preventDefault();
                targetEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
