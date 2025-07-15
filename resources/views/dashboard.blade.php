@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/static/css/flatpickr.min.css') }}">
@endpush

@push('pagecss')
    <link rel="stylesheet" href="{{ asset('/static/css/dashboard.css') }}">
@endpush

@section('content')
<body>
    <div class="main-content container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="dashboard">
        <!-- Left Panel -->
        <div class="left-panel mr-3">
            <!-- User Profile Section -->
            <div class="panel-section mr-3">
                <div class="profile-header">
                    <h4>User Profile</h4>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('profile.edit') }}">Edit Profile</a>
                </div>
                <div class="profile-info">
                    <img src="{{ asset("storage/images/profile_image/".$user->avatar) }}" alt="Profile Picture" class="profile-pic" style="width: 8rem; height: 8rem; border-radius: 50%;">
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
                <div class="calendar" id="job-calendar"></div>
            </div>
        </div>
            
        <!-- Right Panel -->
        <div class="right-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Job Details - June 2023</h4>
                <div>
                    <a class="btn btn-outline-secondary btn-sm me-2">Export</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('jcr.add') }}">Add New Job</a>
                </div>
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Well Name</th>
                            <th class="text-center">Job Type</th>
                            <th class="text-center">Depth (m)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    @if($jcrs)
                        <tbody>
                            @foreach ($jcrs as $index=>$jcr)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($jcr['jobDate'])) }}</td>
                                    <td>{{$jcr['wellNo']}}</td>
                                    <td>
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
                                    <td>
                                        @foreach ($jcr['logs'] as $log)
                                            @if ($log['topShotDepth'])
                                                <div class="text-center">{{ $log['topShotDepth'] . '-' . $log['bottomShotDepth'] }}</div>
                                            @else
                                                <div class="text-center">{{ $log['topDepth'] . '-' . $log['bottomDepth'] }}</div>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($jcr['final_submitted']==1)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" type="submit" href="{{ route('jcr.download', ['id' => $jcr->id]) }}">View</a>
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
@endsection

@push('js')
    <script src="/static/js/flatpickr.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        // Initialize month picker
        flatpickr(".date-picker", {
            plugins: [
                new monthSelectPlugin({
                    shorthandCurrentMonth: true,
                    dateFormat: "m Y",
                    altFormat: "F Y",
                    theme: "light"
                })
            ]
        });

        // Sample dates with jobs (in a real app, this would come from your backend)
        var jobDates = [];
        var jcrs = @json($jcrs);
        jcrs['data'].forEach(jcr => {
            jobDates.push(jcr.jobDate);
        });
        
        // Initialize calendar
        const calendar = flatpickr("#job-calendar", {
            inline: true,
            defaultDate: new Date(),
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (jobDates.includes(dateStr)) {
                    dayElem.classList.add("highlighted-date");
                }
            }
        });

        // In a real application, you would have:
        // 1. Dynamic loading of job data based on selected month
        // 2. Highlighting dates based on actual job data from your database
        // 3. Updating the right panel table when month changes
    </script>
@endpush