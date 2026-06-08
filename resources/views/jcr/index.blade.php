@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ global_asset('/static/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-5">
        <div class="d-flex flex-wrap gap-5 my-3">
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
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Job Date</th>
                        <th class="text-center d-none d-md-table-cell">Indent No.</th>
                        <th class="text-center">Well No</th>
                        <th class="text-center d-none d-md-table-cell">Logging Unit</th>
                        <th class="text-center d-none d-md-table-cell">Time</th>
                        <th class="text-center d-none d-md-table-cell">Log Recorded</th>
                        <th class="text-center d-none d-md-table-cell">Personnel</th>
                        <th class="text-center d-none d-md-table-cell">Status</th>
                        <th class="text-center d-none d-md-table-cell">SAP Doc. No.</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jcrs as $index=>$jcr)
                        <tr>
                            <td class="text-center align-middle">{{ $jcr->jobDate->format('Y-m-d') }}<br>{{ $jcr->jobDate->format('l') }}</td>
                            <td class="text-center align-middle d-none d-md-table-cell">{{ $jcr->indentNo }}</td>
                            <td class="text-center align-middle">{{ $jcr->wellNo }}</td>
                            <td class="text-center align-middle d-none d-md-table-cell"><span class="job-type-badge" @if($jcr->logging_unit_type === 'contractual') style="background-color: #faf388; color: #ff6200;" @endif>{{ ucfirst($jcr->logging_unit_type) }}<br>{{ $jcr->unitNo }}</span></td>
                            <td class="text-center align-middle d-none d-md-table-cell"><span>{{ $jcr->assembled_date->format('Y-m-d') }}</span> <span>{{ date('H:i', strtotime($jcr->assembled_time)) }}</span> - <span>{{ $jcr->arrivalOffice_date->format('Y-m-d') }}</span> <span>{{ date('H:i', strtotime($jcr->arrivalOffice_time)) }}</span></td>
                            <td class="text-center align-middle d-none d-md-table-cell">
                                @switch($index % 4)
                                            @case(1)
                                                @foreach ($jcr['logs'] as $log)
                                                    <div class="text-center"><span class="job-type-badge" style="background-color: #e3f2fd; color: #0d47a1;">{{ str_contains($log['logRecorded'], 'Other') ? $log['otherLogDescription'] : $log['logRecorded'] }}</span></div>
                                                @endforeach
                                                @break
                                            @case(2)
                                                @foreach ($jcr['logs'] as $log)
                                                    <div class="text-center"><span class="job-type-badge" style="background-color: #fff8e1; color: #ff8f00;">{{ str_contains($log['logRecorded'], 'Other') ? $log['otherLogDescription'] : $log['logRecorded'] }}</span></div>
                                                @endforeach
                                                @break
                                            @case(3)
                                                @foreach ($jcr['logs'] as $log)
                                                    <div class="text-center"><span class="job-type-badge" style="background-color: #f3e5f5; color: #6a1b9a;">{{ str_contains($log['logRecorded'], 'Other') ? $log['otherLogDescription'] : $log['logRecorded'] }}</span></div>
                                                @endforeach
                                                @break
                                            @default
                                                @foreach ($jcr['logs'] as $log)
                                                    <div class="text-center"><span class="job-type-badge" style="background-color: #e8f5e9; color: #2e7d32;">{{ str_contains($log['logRecorded'], 'Other') ? $log['otherLogDescription'] : $log['logRecorded'] }}</span></div>
                                                @endforeach
                                        @endswitch
                                    </td>
                            <td class="text-center align-middle d-none d-md-table-cell">
                                @foreach ($jcr['users'] as $personnel)
                                    <div>{{ ucwords(strtolower($personnel['name'])) }}</div>
                                @endforeach
                            </td>
                            <td class="text-center align-middle d-none d-md-table-cell">
                                <span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucfirst(str_replace('_', ' ', $jcr->status)) }}</span>
                                <br>
                                @php
                                    $partyChief =  App\Models\User::find($jcr->party_chief_id);
                                @endphp
                                @if($partyChief)
                                    <span class="badge bg-{{ $jcr->status_badge_color }}">{{ ucwords(strtolower($partyChief['name'])) }}</span>
                                @endif
                            </td>
                            <td class="text-center align-middle d-none d-md-table-cell {{ $jcr->sap_document_number ? 'text-success' : 'text-danger' }}">{{ $jcr->sap_document_number ?? 'N/A' }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-2 justify-content-center align-items-center">
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
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $jcrs->links('pagination::bootstrap-5') }}
    </div>

@push('js')
    <script src="{{ global_asset('/static/js/bootstrap-datepicker.min.js') }}"></script>
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