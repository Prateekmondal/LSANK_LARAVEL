
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        LS, Ank    </title>
    <!-- jquery -->
    <script src="{{ global_asset('static/ajax/jquery.js') }}"
        integrity="sha512-nO7wgHUoWPYGCNriyGzcFwPSF+bPDOR+NvtOYy2wMcWkrnCNPKBcFEkU80XIN14UVja0Gdnff9EmydyLlOL7mQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ global_asset('static/bootstrap-5.2.1/dist/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ global_asset('static/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ global_asset('static/css/select2.min.css') }}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ global_asset('static/fontawesome/css/fontawesome.css') }}">
    <script defer src="{{ global_asset('static/fontawesome/js/all.js') }}"></script>

    <!-- Tempus Dominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ global_asset('static/css/tempusdominus-bootstrap-4.min.css') }}"
        integrity="sha256-XPTBwC3SBoWHSmKasAk01c08M6sIA5gF5+sRxqak2Qs=" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ global_asset('static/css/jquery-ui.css') }}">
    <script src="{{ global_asset('static/js/jquery-ui.js') }}"></script>

    <link rel="stylesheet" href="{{ global_asset('static/css/main.css') }}">
        <link rel="preload" as="image" href="{{ global_asset('static/images/logging-data-bg.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('static/css/welcome.css') }}">

            <script src="{{ global_asset('static/js/chart.js') }}"></script>
        
    <link rel="icon" href="{{ global_asset('static/favicon.ico') }}">
    <script>
        function redirectToTenant(domain) {
            const protocol = window.location.protocol;
            window.location.href = `${protocol}//${domain}:${window.location.port}`;
        }
    </script>
</head>
@php
    $now = \Carbon\Carbon::now();
    if ($now->month >= 4) {
        $fyStart = $now->year;
        $fyEnd = $now->copy()->addYear()->year;
    } else {
        $fyStart = $now->year - 1;
        $fyEnd = $now->year;
    }
    $financialYearLabel = $fyStart . '-' . substr((string) $fyEnd, -2);
@endphp
<body><nav class="navbar navbar-light navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand mr-0 mb-0 h3" href="/">
            <img src="/static/images/ongc.png" class="d-inline-block align-middle" alt="" height="50rem">
            Logging Services{{ tenancy()->initialized ? ', ' . ucfirst(tenant()->name) : '' }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home" role="button" aria-expanded="false">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#job-stats" role="button" aria-expanded="false">Statistics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#achievements" role="button" aria-expanded="false">Achievements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#gallery" role="button" aria-expanded="false">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services" role="button" aria-expanded="false">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact" role="button" aria-expanded="false">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 py-2 d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-geo-alt-fill me-1"></i> Select Location
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdownMenuLink">
                        <li>
                            @foreach($tenants as $tenant)
                                @if($tenant->is_active && $tenant->domains->isNotEmpty())
                                    <a class="dropdown-item py-2 d-flex align-items-center" href="#" onclick="redirectToTenant('{{ $tenant->domains->first()->domain }}'); return false;">
                                        <i class="bi bi-building me-2 text-warning"></i>
                                        <span>{{ $tenant->name }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>    
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container h-100 d-flex align-items-center position-relative" style="z-index: 5;">
            <div class="row w-100">
                <div class="col-md-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">Well Logging Services</h1>
                    <p class="lead mb-4">Providing accurate and reliable borehole data collection and analysis for over 60
                        years.</p>
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
                        <p class="lead">Breakdown of our completed jobs for the Financial Year {{ $financialYearLabel }}</p>
                    </div>
                    <div class="chart-container" style="position: relative; height:45vh; width:100%">
                        <canvas id="jobStatsChartpie" style="height:100%; width:100%"></canvas>
                    </div>
                    <div class="chart-legend" id="chartLegendpie"></div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 right-content">
                    <div class="col-12 text-center">
                        <h2 class="section-title">Logging Job Support Distribution</h2>
                        <p class="lead">Breakdown of our completed jobs by different agencies for the Financial Year {{ $financialYearLabel }}
                        </p>
                    </div>
                    <div class="chart-container" style="position: relative; height:45vh; width:100%">
                        <canvas id="jobStatsChartbar" style="height:100%; width:100%"></canvas>
                    </div>
                    <div class="chart-legend" id="chartLegendbar"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="content-section" id="achievements">
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
            <div class="row mb-1">
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
<footer class="text-center text-lg-start h-auto">
    <!-- Section: Links -->
    <div class="container-fluid text-center text-md-start pt-3">
        <!-- Grid row -->
        <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-5 col-lg-5 col-xl-5 mb-4">
                <!-- Content -->
                <p class="text-uppercase h4 fw-bold mb-md-4 mb-5">LOGGING SERVICES, ANKLESWAR</p>
                <p class="text-justify h6">Logging Services, Ankleshwar Asset since its inception in 1960 has
                    maintained high standards
                    of work ethics and has kept the flag of success flying high in the field of oil and gas
                    exploration, enhancement, hydrocarbon
                    accretion and production by providing best log quality product, analyzing log data</p>
            </div>
            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mb-4"></div>
            <!-- Grid column -->
            <div class="col-md-3 col-lg-3 col-xl-3 mb-4">
                <!-- Links -->
                <p class="text-uppercase h4 fw-bold mb-4 mb-md-5">Useful links</p>
                <p class="text-start h6"><a href="/" class="text-reset text-white">Home</a></p>
                <p class="text-start h6"><a href="/profile/edit" class="text-reset text-white">Profile</a></p>
                <p class="text-start h6"><a href="/jcr/create" class="text-reset text-white">Add New JCR</a></p>
                <p class="text-start h6"><a href="/checklists" class="text-reset text-white">Checklists</a></p>
                <p class="text-start h6"><a href="/time-registers" class="text-reset text-white">Time Registers</a></p>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2 mb-4">
                <p class="text-uppercase h4 fw-bold mb-4 mb-md-5">Contact</p>
                <p class="text-start h6"><i class="fa fa-home"></i> ONGC, Ankleshwar<br>Gujarat-393001</p>
                <p class="text-start h6"><i class="fa fa-envelope"></i> info@example.com</p>
                <p class="text-start h6"><i class="fa fa-phone"></i> +91 123 456 7890</p>
                <p class="text-start h6"><i class="fa fa-print"></i> +91 123 456 7890</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-5">
                <h6 class="text-uppercase h5 fw-bold mb-5">Developed & Designed By:</h6>
                <p class="text-start h6 mt-3">
                    <a href="https://prateekmondal.great-site.net" target="_blank" style="text-decoration: none; color: black;"><strong>Prateek Mondal</strong></a>
                </p>
                <p class="text-start h6">Sr. Geophysicist(W)</p>
                <p class="text-start h6">CPF: 134283</p>
                <p class="text-start h6"><i class="fa fa-envelope"></i> prateekmondal@hotmail.com</p>
            </div>
            <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-3 my-auto">
                <a href="https://prateekmondal.great-site.net" target="_blank">
                    <img class="rounded-circle" src="/static/prateek.png" width="100">
                </a>
            </div>
            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mb-4"></div>
            <!-- Grid column -->
            <div class="col-4 col-sm-4 col-md-2 col-lg-2 mb-5">
                <!-- Content -->
            </div>
            <div class="col-6 col-sm-6 col-md-2 col-lg-2 my-auto">
            </div>
        </div>
    </div>
    <div class="text-left p-3">Copyright &copy
        2026 <a class="text-reset fw-bold" href="/">Logging Services, Ankleswar</a> All rights
        reserved.
    </div>
</footer>

<script src="{{ global_asset('static/js/select2.min.js') }}"
    integrity="sha512-qiKM6FJbI5x5+GL5CEbAUK0suRhjXVMRXnH/XQJaaQ6iQPf05XxbFBE4jS6VJzPGIRg7xREZTrGJIZVk1MLclA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Moment.js -->
<script src="{{ global_asset('static/js/moment.min.js') }}" integrity="sha256-VBLiveTKyUZMEzJd6z2mhfxIqz3ZATCuVMawPZGzIfA="
    crossorigin="anonymous"></script>

<!-- mask -->
<script src="{{ global_asset('static/js/jquery.mask.min.js') }}"
    integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ global_asset('static/js/jquery.inputmask.min.js') }}"
    integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ global_asset('static/js/tempusdominus-bootstrap-4.min.js') }}" integrity="sha256-z0oKYg6xiLq3yJGsp/LsY9XykbweQlHl42jHv2XTBz4="
    crossorigin="anonymous"></script>

<script src="{{ global_asset('static/bootstrap-5.2.1/dist/js/bootstrap.bundle.min.js') }}"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8"
    crossorigin="anonymous"></script>
<script src="{{ global_asset('static/js/bootstrap-datetimepicker.min.js') }}"
    integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Chart labels and colors
            const jobLabels = ['Open Hole Logging', 'Cased Hole Logging', 'Production Logging'];
            const jobColors = ['#3498db', '#2ecc71', '#f39c12'];
            const ownerLabels = ['Departmental', 'Contractual'];

            // Job Statistics Pie Chart (initial empty data)
            const jobStatsData = {
                labels: jobLabels,
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: jobColors,
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
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0) || 1;
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, animateRotate: true }
                }
            });

            // Job Statistics Bar Chart for ownership
            const jobStatsDatabar = {
                labels: ownerLabels,
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#3498db', '#2ecc71'],
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
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0) || 1;
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, animateRotate: true }
                }
            });

            // Create or refresh custom legend
            const chartLegendpie = document.getElementById('chartLegendpie');
            function renderLegend() {
                if (!chartLegendpie) return;
                chartLegendpie.innerHTML = '';
                jobStatsData.labels.forEach((label, i) => {
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';

                    const legendColor = document.createElement('div');
                    legendColor.className = 'legend-color';
                    legendColor.style.backgroundColor = jobStatsData.datasets[0].backgroundColor[i] || jobColors[i] || '#ccc';

                    const legendText = document.createElement('span');
                    legendText.textContent = `${label}: ${jobStatsData.datasets[0].data[i] || 0} jobs`;

                    legendItem.appendChild(legendColor);
                    legendItem.appendChild(legendText);
                    chartLegendpie.appendChild(legendItem);
                });

                // Create custom legend
                const chartLegendbar = document.getElementById('chartLegendbar');
                jobStatsDatabar.labels.forEach((label, i) => {
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';

                    const legendColor = document.createElement('div');
                    legendColor.className = 'legend-color';
                    legendColor.style.backgroundColor = jobStatsDatabar.datasets[0].backgroundColor[i];

                    const legendText = document.createElement('span');
                    legendText.textContent = `${label}: ${jobStatsDatabar.datasets[0].data[i] || 0} jobs`;

                    legendItem.appendChild(legendColor);
                    legendItem.appendChild(legendText);
                    chartLegendbar.appendChild(legendItem);
                });
            }

            // Server-passed aggregated stats (no polling)
            const initialStats = {!! json_encode($aggregatedStats ?? ['loggingType' => ['Open Hole Logging' => 0, 'Cased Hole Logging' => 0, 'Production Logging' => 0], 'owner' => ['Departmental' => 0, 'Contractual' => 0]]) !!};

            (function applyInitialStats() {
                const logging = initialStats.loggingType || {};
                const owners = initialStats.owner || {};

                jobStatsChartpie.data.datasets[0].data = [
                    logging['OH'] || 0,
                    logging['CH'] || 0,
                    logging['PL'] || 0,
                ];
                jobStatsChartpie.update();

                jobStatsChartbar.data.datasets[0].data = [
                    owners['departmental'] || 0,
                    owners['contractual'] || 0,
                ];
                jobStatsChartbar.update();

                renderLegend();
            })();

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

</body>

</html>