@extends('layouts.app')

@push('pagecss')
    <link rel="stylesheet" href="{{ asset('/static/css/viewjcr.css') }}">
@endpush

@section('content')
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
    <div class="container-fluid">
        <div class="table-responsive mx-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td class="py-4 ms-5">Date</td>
                        <td class="py-4">Time</td>
                        <td class="py-4">Well ID</td>
                        <td class="py-4">Unit</td>
                        <td class="py-4">Logs recorded</td>
                        <td class="py-4">Interval</td>
                        <td class="py-4">Personnel</td>
                        <td class="py-4 me-3">Action</td>
                    </tr>
                </thead>
                @if($jcrs)
                    <tbody>
                        @foreach ($jcrs as $index=>$jcr)
                                <tr>
                                    <td class="align-middle w-auto ms-5" name="jobDate">{{ date('d/m/Y', strtotime($jcr['jobDate'])) }}</td>
                                    <td class="align-middle w-auto text-center" name="jobTime">{{ date('d/m/Y', strtotime($jcr['assembled_date'])) }}
                                        {{ date('H:i', strtotime($jcr['assembled_time'])) }}<br>|<br>{{ date('d/m/Y', strtotime($jcr['arrivalOffice_date'])) }} {{ date('H:i', strtotime($jcr['arrivalOffice_time'])) }}
                                    </td>
                                    <td class="align-middle w-auto" name="wellNo">{{$jcr['wellNo']}}</td>
                                    <td class="align-middle w-auto">{{$jcr['unitNo']}}</td>
                                    <td class="align-middle w-auto">
                                        @switch($index % 4)
                                                @case(1)
                                                    @foreach ($jcr['logs'] as $log)
                                                        <div class="align-middle text-center w-auto"><span class="job-type-badge" style="background-color: #e3f2fd; color: #0d47a1;">{{$log['logRecorded']}}</span></div>
                                                    @endforeach
                                                    @break
                                                @case(2)
                                                    @foreach ($jcr['logs'] as $log)
                                                        <div class="align-middle text-center w-auto"><span class="job-type-badge" style="background-color: #fff8e1; color: #ff8f00;">{{$log['logRecorded']}}</span></div>
                                                    @endforeach
                                                    @break
                                                @case(3)
                                                    @foreach ($jcr['logs'] as $log)
                                                        <div class="align-middle text-center w-auto"><span class="job-type-badge" style="background-color: #f3e5f5; color: #6a1b9a;">{{$log['logRecorded']}}</span></div>
                                                    @endforeach
                                                    @break
                                                @default
                                                    @foreach ($jcr['logs'] as $log)
                                                        <div class="align-middle text-center w-auto"><span class="job-type-badge" style="background-color: #e8f5e9; color: #2e7d32;">{{$log['logRecorded']}}</span></div>
                                                    @endforeach
                                            @endswitch
                                    </td>

                                    <td class="align-middle w-auto">
                                        @foreach ($jcr['logs'] as $log)
                                                @if ($log['topShotDepth'])
                                                    <div class="align-middle text-center w-auto">{{ $log['topShotDepth'] . '-' . $log['bottomShotDepth'] }}</div>
                                                @else
                                                    <div class="align-middle text-center w-auto">{{ $log['topDepth'] . '-' . $log['bottomDepth'] }}</div>
                                                @endif
                                            @endforeach
                                    </td>
                                    <td class="align-middle text-center text-wrap" style="max-width: 15rem;">
                                            @foreach ($jcr['users']->sortBy('seniority') as $personnel)
                                                <div class="text-center personnel">
                                                    {{ Str::title($personnel['name'])}}
                                                </div>
                                            @endforeach
                                            <div class="text-center personnel" style="word-wrap: break-word;">
                                                    <hr>{{ $jcr['contingents'] }}
                                            </div>
                                    </td>
                                    <td class="align-middle w-auto me-3">
                                        @if($jcr->final_submitted==0 and auth()->user()->can('edit JCR'))
                                            <div><a class="btn btn-outline-warning m-1 w-100" type="submit" title="Edit" name="jcredit"
                                                href="{{ route('jcr.show', ['id' => $jcr->id]) }}"><i class="fa fa-edit mx-1"
                                                    aria-hidden="true"></i>Edit</a></div>
                                        @endif
                                        <div><a class="btn btn-outline-info m-1 w-100" type="submit" title="Download" name="jcrdownload"
                                            href="{{ route('jcr.download', ['id' => $jcr->id]) }}"><i class="fa fa-download mx-1"
                                                aria-hidden="true"></i>Download</a></div>
                                    </td>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        <div class="container-fluid py-3">
        {{ $jcrs->links('pagination::bootstrap-5') }}
        </div>
        </div>
        </div>
@endsection