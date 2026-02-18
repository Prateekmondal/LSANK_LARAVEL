@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/static/css/bootstrap-datepicker.min.css') }}">
@endpush

@push('pagecss')
    <link rel="stylesheet" href="{{ asset('/static/css/dashboard.css') }}">
@endpush

@section('content')
<body>
    <div class="main-content container-fluid">
        <div class="dashboard">
        <!-- Left Panel -->
        <div class="left-panel col-sm-12 mr-3">
            <!-- User Profile Section -->
            <div class="panel-section mr-3">
                <div class="profile-header">
                    <h4>User Profile</h4>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('profile.edit') }}">Edit Profile</a>
                </div>
                <div class="profile-info">
                    <img src="{{ Storage::url('images/profile_image/'.$user->avatar) }}" alt="Profile Picture" class="profile-pic" style="width: 8rem; height: 8rem; border-radius: 50%;">
                    <div>
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted mb-1">{{ $user->designation }}</p>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        <p class="text-muted mb-1">+91-{{ $user->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Job Counts Section -->
            <div class="panel-section">
                <h5 class="mb-3">Job Statistics</h5>
                <div class="job-counts">
                    
                    <div class="job-count-item" style="grid-column: span 1;">
                        <div class="job-count-number">{{ $oh }}</div>
                        <div class="job-count-label">Open hole log </div>
                    </div>
                    <div class="job-count-item" style="grid-column: span 1;">
                        <div class="job-count-number">{{ $ch }}</div>
                        <div class="job-count-label">Cased hole log</div>
                    </div>
                    <div class="job-count-item" style="grid-column: span 2;">
                        <div class="job-count-number">{{ $pl }}</div>
                        <div class="job-count-label">Production log</div>
                    </div>
                    <div class="job-count-item" style="grid-column: span 2;">
                        <div class="job-count-number">{{ $total }}</div>
                        <div class="job-count-label">Total Jobs</div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="panel-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Job Calendar</h5>
                </div>
                <div class="calendar d-flex justify-content-between align-items-center" id="job-calendar"></div>
            </div>
        </div>
            
        <!-- Right Panel -->
        <div class="right-panel">
            <!-- Statistics Panel -->
            <div class="panel-section mb-4">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h5 class="mb-3">Job Statistics</h5>
                        <canvas id="FyJobsChart" height="310 rem"></canvas>
                    </div>
                    <!-- Numeric monthly breakdown (visible on medium+) -->
                    <div class="col-md-6 d-none d-md-block">
                        <h5 class="mb-5">Monthly Breakdown</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Month</th>
                                    <th class="text-center">{{ $previousFySummary['label'] }}</th>
                                    <th class="text-center">{{ $currentFySummary['label'] }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chartLabels as $i => $m)
                                    <tr>
                                        <td class="text-center">{{ $m }}</td>
                                        <td class="text-center">{{ $previousFyCounts[$i] ?? 0 }}</td>
                                        <td class="text-center">{{ $currentFyCounts[$i] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                                <tr class="text-center">
                                    <td class="fw-bold">Total Jobs</td>
                                    <td class="fw-bold">{{ array_sum($previousFyCounts) }}</td>
                                    <td class="fw-bold">{{ array_sum($currentFyCounts) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Job Details Table -->
            <div class="panel-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Job Details - {{ $selectedMonth ? $selectedMonth->format('F Y') : 'All Jobs' }}</h4>
                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('dashboard') }}" class="me-2 d-flex align-items-center">
                            <input type="text" name="month" class="form-control form-control-sm date-picker" value="{{ request('month') ?: '' }}" placeholder="Select month" style="width: 140px;" autocomplete="off" />
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Filter</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                        </form>
                        @can('create', App\Models\Jcr::class)
                            <a class="btn btn-primary btn-sm" href="{{ route('jcr.create') }}">Add New Job</a>
                        @endcan
                    </div>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Well Name</th>
                                <th class="text-center">Job Type</th>
                                <th class="text-center">Depth (m)</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        @if($jcrs)
                            <tbody>
                                @foreach ($jcrs as $index=>$jcr)
                                    <tr>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($jcr['jobDate'])) }}</td>
                                        <td class="text-center">{{$jcr['wellNo']}}</td>
                                        <td class="text-center">
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
                                        <td class="text-center">
                                            @foreach ($jcr['logs'] as $log)
                                                @if ($log['topShotDepth'])
                                                    <div class="text-center">{{ $log['topShotDepth'] . '-' . $log['bottomShotDepth'] }}</div>
                                                @else
                                                    <div class="text-center">{{ $log['topDepth'] . '-' . $log['bottomDepth'] }}</div>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center"><span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucfirst(str_replace('_', ' ', $jcr->status)) }}</span></td>
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

        // Initialize inline calendar using Bootstrap Datepicker
        $('#job-calendar').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: false,
            beforeShowDay: function(date) {
                var d = date.toISOString().split('T')[0];
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