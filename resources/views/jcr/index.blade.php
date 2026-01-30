@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/static/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <h1 class="my-4">JCR List</h1>

        <div class="mb-3">
            <form method="GET" action="{{ route('jcr.index') }}" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label for="month" class="form-label">Month</label>
                    <input type="text" id="month" name="month" value="{{ request('month') }}" class="form-control date-picker" placeholder="Select month" autocomplete="off">
                </div>

                @if(auth()->user()->hasAnyRole(['Technical_Support_Group', 'party_chief','operation_incharge','super-admin','Head_Logging_Services','Location Manager']))
                    <div class="col-auto">
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($filterUsers as $fu)
                                <option value="{{ $fu->id }}" {{ (string)request('user_id') === (string)$fu->id ? 'selected' : '' }}>{{ ucwords(strtolower($fu->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('jcr.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Job Date</th>
                        <th class="text-center">Indent No.</th>
                        <th class="text-center">Well No</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Log Recorded</th>
                        <th class="text-center">Personnel</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jcrs as $index=>$jcr)
                        <tr>
                            <td class="text-center">{{ $jcr->jobDate->format('Y-m-d') }}<br>{{ $jcr->jobDate->format('l') }}</td>
                            <td class="text-center">{{ $jcr->indentNo }}</td>
                            <td class="text-center">{{ $jcr->wellNo }}</td>
                            <td class="text-center"><span>{{ $jcr->assembled_date->format('Y-m-d') }}</span> <span>{{ $jcr->assembled_time->format('H:i') }}</span> - <span>{{ $jcr->arrivalOffice_date->format('Y-m-d') }}</span> <span>{{ $jcr->arrivalOffice_time->format('H:i') }}</span></td>
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
                                @foreach ($jcr['users'] as $personnel)
                                    <div>{{ ucwords(strtolower($personnel['name'])) }}</div>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucfirst(str_replace('_', ' ', $jcr->status)) }}</span>
                                <br>
                                @php
                                    $partyChief =  App\Models\User::find($jcr->party_chief_id);
                                @endphp
                                @if($partyChief)
                                    <span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucwords(strtolower($partyChief['name'])) }}</span>
                                @endif
                                </td>
                            <td class="text-center">
                                @if($jcr->final_submit && $jcr->party_chief_id)
                                    <a href="{{ route('jcr.show', $jcr->id) }}" class="btn btn-info btn-sm">View</a>
                                    @can('update', $jcr)
                                        <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endcan
                                @elseif($jcr->status == 'draft')
                                    <a href="{{ route('jcr.show', $jcr->id) }}" class="btn btn-info btn-sm">View</a>
                                    @can('update', $jcr)
                                        <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endcan
                                @else
                                    <a href="{{ route('jcr.preview', $jcr->id) }}" class="btn btn-primary btn-sm">Preview</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $jcrs->links('pagination::bootstrap-5') }}
    </div>

@push('js')
    <script src="{{ asset('/static/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.date-picker').datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });
        @if(request('month'))
            $('.date-picker').datepicker('update', '{{ request('month') }}');
        @endif
    </script>
@endpush
@endsection