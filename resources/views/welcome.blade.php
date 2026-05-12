@extends('layouts.app')
@push('pagejs')
    <script src="{{ asset('/static/js/chart.js') }}"></script>
@endpush
@push('css')
    <link rel="preload" as="image" href="{{ asset('/static/images/logging-data-bg.png') }}">
    <link rel="stylesheet" type="text/css" href='{{ asset("/static/css/welcome.css") }}'>
@endpush
@section('content')
    
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container h-100 d-flex align-items-center position-relative" style="z-index: 5;">
            <div class="row w-100">
                <div class="col-md-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">Well Logging Services, Ankleshwar</h1>
                    <p class="lead mb-4">Providing accurate and reliable borehole data collection and analysis for over 60
                        years in Ankleshwar and surrounding regions.</p>
                    <a href="#contact" class="btn btn-primary btn-lg px-4 me-2 rb-2">Contact Us</a>
                    <a href="#services" class="btn btn-outline-light btn-lg px-4">Our Services</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Statistics Pie Chart Section -->
    <section class="stats-section" id="job-stats">
        <div class="container">
            <div class="row">
                <div class="col-md-5 left-content">
                    <div class="col-12 text-center">
                        <h2 class="section-title">Our Logging Job Type Distribution</h2>
                        <p class="lead">Breakdown of our completed jobs for the Financial Year 2024-25</p>
                    </div>
                    <div class="chart-container">
                        <canvas id="jobStatsChartpie"></canvas>
                    </div>
                    <div class="chart-legend" id="chartLegend"></div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="col-12 text-center">
                        <h2 class="section-title">Logging Job Support Distribution</h2>
                        <p class="lead">Breakdown of our completed jobs by different agencies for the Financial Year 2024-25
                        </p>
                    </div>
                    <div class="chart-container">
                        <canvas id="jobStatsChartbar"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="content-section" id="statistics">
        <div class="container">
            <div class="row">
                <div class="col-md-5 left-content">
                    <div class="content-reveal">
                        <h2 class="section-title">Our Achievements</h2>
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <h4>Projects Completed</h4>
                            <p>Successfully delivered logging services for over 500 projects across Gujarat.</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">98%</div>
                            <h4>Accuracy Rate</h4>
                            <p>Consistently high accuracy in data collection and interpretation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="content-reveal right">
                        <div class="stat-item">
                            <div class="stat-number">20+</div>
                            <h4>Years Experience</h4>
                            <p>Two decades of expertise in well logging and borehole analysis.</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <h4>Clients Served</h4>
                            <p>Trusted by major companies in the oil, gas, and water sectors.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="content-section bg-light" id="gallery">
        <div class="container">
            <div class="row mb-5">
                <div class="col-6 text-left">
                    <h2 class="section-title">Project Gallery</h2>
                    <p class="lead">Explore our recent well logging projects and field operations</p>
                </div>
            </div>
            <div class="row h-100">
                <div class="col-md-5 left-content">
                    <div class="content-reveal">
                        <!-- In the Gallery Section, replace the image tags with these: -->

                        <div class="gallery-item">
                            <img src="{{ asset('/static/images/oil-well-drilling.avif') }}"
                                alt="Oil Well Drilling" class="img-fluid rounded">
                            <div class="gallery-caption">Oil Well Drilling Site</div>
                        </div>

                        <div class="gallery-item">
                            <img src="{{ asset('/static/images/data-analysis.png') }}"
                                alt="Data Analysis" class="img-fluid rounded">
                            <div class="gallery-caption">Data Analysis Team</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="content-reveal right">
                        <div class="gallery-item">
                            <img src="{{ asset('/static/images/field-equipment.png') }}"
                                alt="Field Equipment" class="img-fluid rounded">
                            <div class="gallery-caption">Logging Equipment</div>
                        </div>

                        <div class="gallery-item">
                            <img src="{{ asset('/static/images/field-team.avif') }}"
                                alt="Team at Work" class="img-fluid rounded">
                            <div class="gallery-caption">Field Team in Action</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="content-section" id="services">
        <div class="container">
            <div class="row mb-5">
                <div class="col-6"></div>
                <div class="col-6 left-content">
                    <h2 class="section-title">Our Services</h2>
                    <p class="lead">Comprehensive well logging solutions tailored to your needs</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 left-content">
                    <div class="content-reveal">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-water"></i>
                            </div>
                            <h4>Borehole Logging</h4>
                            <p>Comprehensive logging of wells including resistivity, spontaneous potential, gamma ray,
                                cbl-vdl logging and perforation along with backoff jobs.</p>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-oil-can"></i>
                            </div>
                            <h4>Oil & Gas Logging</h4>
                            <p>Advanced logging services for oil and gas exploration wells with modern equipment.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="content-reveal right">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Data Interpretation</h4>
                            <p>Expert analysis and interpretation of logging data with detailed reports.</p>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h4>Equipment Rental</h4>
                            <p>High-quality logging equipment available for rent with technical support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="content-section bg-light" id="contact">
        <div class="container">
            <div class="row mb-5">
                <div class="col-6 text-left">
                    <h2 class="section-title">Contact Us</h2>
                    <p class="lead">Get in touch for your well logging requirements</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 left-content">
                    <div class="content-reveal">
                        <div class="mb-4">
                            <h4>Office Address</h4>
                            <p>ONGC, Ankleshwar<br>Gujarat 393001, India</p>
                        </div>
                        <div class="mb-4">
                            <h4>Phone</h4>
                            <p>+91 1234567890</p>
                        </div>
                        <div class="mb-4">
                            <h4>Email</h4>
                            <p>info@loggingservicesankleshwar.com</p>
                        </div>
                        <div class="mb-4">
                            <h4>Working Hours</h4>
                            <p>Monday - Friday: 09:30 AM - 05:30 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="content-reveal right">
                        <form class="contact-form" action="{{ route('contact.us.store') }}" method='POST'>
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="Phone Number">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="5" name="message" placeholder="Your Message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Job Statistics Pie Chart
            const jobStatsData = {
                labels: [
                    'Open Hole Logging',
                    'Cased Hole Logging',
                    'Production Logging'
                ],
                datasets: [{
                    data: [180, 780, 50],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#f39c12'
                    ],
                    borderWidth: 0
                }]
            };

            const jobStatsCtxpie = document.getElementById('jobStatsChartpie').getContext('2d');
            const jobStatsChartpie = new Chart(jobStatsCtxpie, {
                type: 'pie',
                data: jobStatsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
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

            // Job Statistics Pie Chart
            const jobStatsDatabar = {
                labels: [
                    'Departmental',
                    'Contractual'
                ],
                datasets: [{
                    data: [750, 250],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71'
                    ],
                    borderWidth: 0
                }]
            };

            const jobStatsCtxbar = document.getElementById('jobStatsChartbar').getContext('2d');
            const jobStatsChartbar = new Chart(jobStatsCtxbar, {
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
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
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

            // Create custom legend
            const chartLegend = document.getElementById('chartLegend');
            jobStatsData.labels.forEach((label, i) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';

                const legendColor = document.createElement('div');
                legendColor.className = 'legend-color';
                legendColor.style.backgroundColor = jobStatsData.datasets[0].backgroundColor[i];

                const legendText = document.createElement('span');
                legendText.textContent = `${label}: ${jobStatsData.datasets[0].data[i]} jobs`;

                legendItem.appendChild(legendColor);
                legendItem.appendChild(legendText);
                chartLegend.appendChild(legendItem);
            });

            // Content reveal animation
            const contentReveals = document.querySelectorAll('.content-reveal');

            const revealOnScroll = () => {
                contentReveals.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementBottom = element.getBoundingClientRect().bottom;

                    if (elementTop < window.innerHeight - 100 && elementBottom > 0) {
                        element.classList.add('active');
                    }
                });
            };

            // Initial check
            revealOnScroll();

            // Check on scroll
            window.addEventListener('scroll', revealOnScroll);

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endpush