@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/static/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('pagecss')
    <link rel="stylesheet" href="{{ asset('/static/css/dashboard.css') }}">
    <style>
        .datepicker-inline,
        .datepicker table {
        width: 100% !important;
        }
    </style>
@endpush

@section('content')
<body>
    <div class="container-fluid mt-4">
        <div class="row g-3">
            <!-- Left Panel -->
            <div class="col-12 col-md-4">
                <!-- User Profile Section -->
                <div class="card w-100 my-1 p-4">
                    <div class="row g-2">
                        <div class="col-5">
                            <img src="{{ Storage::url('images/profile_image/'.$user->avatar) }}" alt="Profile Picture" class="profile-pic" style="width: 8em; height: 8em; border-radius: 50%;">
                        </div>
                        <div class="col-7">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                <p class="mb-0"><strong>{{ $user->name }}</strong></p>
                                <a class="btn btn-outline-primary btn-sm mx-2" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            <p class="text-muted mb-1 small">{{ $user->designation }}</p>
                            <p class="text-muted mb-1 small">{{ $user->email }}</p>
                            <p class="text-muted mb-1 small">+91-{{ $user->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Job Counts Section -->
                <div class="card w-100 my-1 p-4">
                    <h5 class="mb-3">Job Statistics</h5>
                    <div class="row g-3">
                        <div class="col-12 col-lg-4 py-2">
                            <div class="text-center">
                                <h3 class="fw-bold text-primary">{{ $oh }}</h3>
                                <div class="job-count-label text-wrap">Open hole log</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 py-2">
                            <div class="text-center">
                                <h3 class="fw-bold text-primary">{{ $ch }}</h3>
                                <div class="job-count-label text-wrap">Cased hole log</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 py-2">
                            <div class="text-center">
                                <h3 class="fw-bold text-primary">{{ $pl }}</h3>
                                <div class="job-count-label text-wrap">Production log</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 py-2">
                            <div class="text-center">
                                <h3 class="fw-bold text-primary">{{ $total }}</h3>
                                <div class="job-count-label text-wrap">Total Jobs</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="card w-100 my-1 p-4">
                    <h5 class="mb-3">Job Calendar</h5>
                    <div class="row g-3" id="job-calendar"></div>
                </div>
            </div>
                
            <!-- Right Panel -->
            <div class="col-12 col-md-8">
                <!-- Statistics Panel -->
                <div class="card w-100 my-1" style="max-height: 90vh;">
                    <div class="row g-2">
                        <div class="col-12 col-lg-6">
                            <h5 class="mb-3">Job Statistics</h5>
                            <canvas id="FyJobsChart" height="338rem"></canvas>
                        </div>
                        <!-- Numeric monthly breakdown (visible on medium+) -->
                        <div class="col-12 col-lg-6 d-none d-lg-block">
                            <h5 class="mb-3">Monthly Breakdown</h5>
                            <div class="table-responsive">
                                <table class="table table-grid table-bordered mb-0" style="margin-top: 2rem; min-height: 25rem;">
                                    <thead class="position-sticky h-100" style="top: 0; background-color: #f8f9fa;">
                                        <tr>
                                            <th class="text-center col-md-2">Month</th>
                                            <th class="text-center col-md-2">{{ $previousFySummary['label'] }}</th>
                                            <th class="text-center col-md-2">{{ $currentFySummary['label'] }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chartLabels as $i => $m)
                                            <tr>
                                                <td class="text-center py-1"><p class='mb-0'>{{ $m }}</p></td>
                                                <td class="text-center py-1"><p class='mb-0'>{{ $previousFyCounts[$i] ?? 0 }}</p></td>
                                                <td class="text-center py-1"><p class='mb-0'>{{ $currentFyCounts[$i] ?? 0 }}</p></td>
                                            </tr>
                                        @endforeach
                                        <tr class="text-center fw-bold">
                                            <td>Total Jobs</td>
                                            <td><p class='mb-0'>{{ array_sum($previousFyCounts) }}</p></td>
                                            <td><p class='mb-0'>{{ array_sum($currentFyCounts) }}</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Details Table -->
                <div class="card w-100 my-1">
                    <div class="row g-2 mb-4">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h4 class="mb-0">Job Details - {{ $selectedMonth ? $selectedMonth->format('F Y') : 'All Jobs' }}</h4>
                        </div>
                        <div class="row g-2 gap-2">
                            <div class="col-12 col-sm-auto">
                                <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                                    <input type="text" name="month" class="form-control form-control-sm date-picker" value="{{ request('month') ?: '' }}" placeholder="Select month" autocomplete="off" />
                                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                                </form>
                            </div>
                            @can('create', App\Models\Jcr::class)
                                <div class="col-12 col-sm-auto">
                                    <a class="btn btn-primary btn-sm w-100 w-sm-auto" href="{{ route('jcr.create') }}">Add New Job</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    {{ $jcrs->links('pagination::bootstrap-5') }}
                    <div class="table-responsive mb-3">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Well Name</th>
                                    <th class="text-center d-none d-md-table-cell">Job Type</th>
                                    <th class="text-center d-none d-lg-table-cell">Depth (m)</th>
                                    <th class="text-center d-none d-lg-table-cell">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            @if($jcrs)
                                <tbody>
                                    @foreach ($jcrs as $index=>$jcr)
                                        <tr>
                                            <td class="text-center"><p class='mb-0'>{{ date('d-m-Y', strtotime($jcr['jobDate'])) }}</p></td>
                                            <td class="text-center"><p class='mb-0'>{{$jcr['wellNo']}}</p></td>
                                            <td class="text-center d-none d-md-table-cell">
                                                @switch($index % 4)
                                                    @case(1)
                                                        @foreach ($jcr['logs'] as $log)
                                                            <div class="text-center"><span class="job-type-badge" style="background-color: #e3f2fd; color: #0d47a1;">{{$log['logRecorded']}}</span></div>
                                                        @endforeach
                                                        @break
                                                    @case(2)
                                                        @foreach ($jcr['logs'] as $log)
                                                            <div class="text-center"><span class="job-type-badge" style="background-color: #fff8e1; color: #ff8f00;">{{$log['logRecorded']}}</span></div>
                                                        @endforeach
                                                        @break
                                                    @case(3)
                                                        @foreach ($jcr['logs'] as $log)
                                                            <div class="text-center"><span class="job-type-badge" style="background-color: #f3e5f5; color: #6a1b9a;">{{$log['logRecorded']}}</span></div>
                                                        @endforeach
                                                        @break
                                                    @default
                                                        @foreach ($jcr['logs'] as $log)
                                                            <div class="text-center"><span class="job-type-badge" style="background-color: #e8f5e9; color: #2e7d32;">{{$log['logRecorded']}}</span></div>
                                                        @endforeach
                                                @endswitch
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell"><p class='mb-0'>
                                                @foreach ($jcr['logs'] as $log)
                                                    @if ($log['topShotDepth'])
                                                        <div class="text-center">{{ $log['topShotDepth'] . '-' . $log['bottomShotDepth'] }}</div>
                                                    @else
                                                        <div class="text-center">{{ $log['topDepth'] . '-' . $log['bottomDepth'] }}</div>
                                                    @endif
                                                @endforeach
                                            </p></td>
                                            <td class="text-center d-none d-lg-table-cell"><span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucfirst(str_replace('_', ' ', $jcr->status)) }}</span></td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-outline-primary" type="submit" href="{{ route('jcr.show', $jcr->id) }}">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                    {{ $jcrs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('/static/js/chart.js') }}"></script>
    <script src="{{ asset('/static/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        // Chart data (from backend)
        var chartLabels = @json($chartLabels ?? []);
        var currentFyCounts = @json($currentFyCounts ?? []);
        var previousFyCounts = @json($previousFyCounts ?? []);
        var currentFyLabel = @json($currentFySummary['label'] ?? 'Current FY');
        var previousFyLabel = @json($previousFySummary['label'] ?? 'Previous FY');
        // var chartLabels = @json($chartLabels ?? []);
        var currentMonthCounts = @json($currentMonthCounts ?? []);
        var previousMonthCounts = @json($previousMonthCounts ?? []);
        var currentMonthLabel = @json($currentMonthSummary['label'] ?? []);
        var previousMonthLabel = @json($previousMonthSummary['label'] ?? []);

        if (document.getElementById('FyJobsChart')) {
            var ctx = document.getElementById('FyJobsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        { label: previousFyLabel, data: previousFyCounts, backgroundColor: 'rgba(255, 159, 64, 0.6)' },
                        { label: currentFyLabel, data: currentFyCounts, backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }
        if (document.getElementById('monthlyJobsChart')) {
            var ctx = document.getElementById('monthlyJobsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        { label: previousMonthLabel, data: previousMonthCounts, backgroundColor: 'rgba(255, 159, 64, 0.6)' },
                        { label: currentMonthLabel, data: currentMonthCounts, backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }

        // Initialize month picker using Bootstrap Datepicker
        $('.date-picker').datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });
        // If month param present, set the picker value
        @if(request('month'))
            $('.date-picker').datepicker('update', '{{ request('month') }}');
        @endif

        // Dates for jobs (provided by backend)
        var jobDates = @json($jobDates ?? []);
        var calendarDate = @if($selectedMonth) "{{ $selectedMonth->format('Y-m-d') }}" @else null @endif;
        // console.log(jobDates);
        // Initialize inline calendar using Bootstrap Datepicker
        $('#job-calendar').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: false,
            beforeShowDay: function(date) {
                // Use local date components to avoid timezone shifts
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                var day = String(date.getDate()).padStart(2, '0');
                var d = year + '-' + month + '-' + day;
                if (jobDates.includes(d)) {
                    return {classes: 'highlighted-date'};
                }
                return;
            }
        });

        if (calendarDate) {
            $('#job-calendar').datepicker('update', calendarDate);
        }
    </script>
@endpush