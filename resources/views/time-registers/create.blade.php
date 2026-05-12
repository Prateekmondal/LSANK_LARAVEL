@extends('layouts.app')

@section('content')
<div class="container mb-4">
    @if ($errors->any())
        <div class="container text-center">
            @foreach ($errors->all() as $index => $error)
                <small>{{ " " . ($index + 1) . ". " . $error }}</small>
                <br>
            @endforeach
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(isset($jcr) && $jcr->requiresTimeRegister())
    <div class="alert alert-warning">
        <h4><i class="fas fa-exclamation-triangle"></i> Time Register Required</h4>
        <p class="mb-0">You must complete this Time Register to continue working with JCR: <strong>{{ $jcr->jcr_number }}</strong></p>
    </div>
    @endif

    <h1>
        {{ isset($timeRegister) ? 'Edit' : 'Create' }} Time Register
        @if(isset($jcr))
        <small class="text-muted">for JCR: {{ $jcr->jcr_number }}</small>
        @endif
    </h1>
    
    <div class="card w-100">
        <form action="{{ isset($timeRegister) ? route('time-registers.update', $timeRegister) : route('time-registers.store') }}" method="POST">
            @csrf
            @if(isset($timeRegister))
                @method('PUT')
            @endif

            <!-- Hidden fields for modal context -->
            @if(isset($jcr))
            <input type="hidden" name="jcr_id" value="{{ $jcr->id }}">
            <input type="hidden" name="from_modal" value="1">
            @endif

            <div class="row">
                <h3>Basic Information</h3>
                
                <div class="form-group">
                    <label for="logging_unit_no" class="form-label">Logging unit No. <small>*</small></label>
                    <select name='logging_unit_no' class='select form-select' id='logging_unit_no'>
                        <option value="" disabled {{ old('logging_unit_no') == '' ? 'selected' : '' }}>--- Select Unit ---</option>
                        @foreach ($unitNos as $unitNo)
                            <option value="{{ $unitNo }}" {{ old("logging_unit_no") == $unitNo || (isset($timeRegister) && $timeRegister->logging_unit_no == $unitNo) ? 'selected' : '' }}>
                                {{ $unitNo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class='form-label requiredField'>Indent No:<small>*</small></label>
                    <input type="text" name="indent_no" class="form-control" value="{{ old('indent_no', $timeRegister->indent_no ?? '') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class='form-label requiredField'>Well No:<small>*</small></label>
                    <input type="text" name="well_no" class="form-control" value="{{ old('well_no', $timeRegister->well_no ?? '') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class='form-label requiredField'>Rig No:<small>*</small></label>
                    <input type="text" name="rig_no" class="form-control" value="{{ old('rig_no', $timeRegister->rig_no ?? '') }}" required>
                </div>
            </div>
            <div class="row">
                <!-- Well Indented Time - Single Line with Time left, Date right -->
                <div class="form-group">
                    <label class='form-label requiredField'>Well Indented:<small>*</small></label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="well_indented_time" class="datetimeinput timepicker form-control" id="well_indented_time" placeholder='HH:MM' data-mask='00:00'
                                    value="{{ old('well_indented_time', $timeRegister->well_indented_time ?? '') }}" required autocomplete="on" maxlength="5">
                            <small class="form-text text-muted">Time</small>
                        </div>
                        <div class="col-6">
                            <input type="text" name="well_indented_date" class="datetimeinput datepicker form-control" placeholder='YYYY-MM-DD' data-mask='0000-00-00' id="well_indented_date"
                                    value="{{ old('well_indented_date', isset($timeRegister) ? $timeRegister->well_indented_date?->format('Y-m-d') : '') }}" required>
                            <small class="form-text text-muted">Date</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Well Taken Up Time - Single Line with Time left, Date right -->
                <div class="form-group">
                    <label class='form-label requiredField'>Well Taken Up:<small>*</small></label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="well_taken_up_time" class="datetimeinput timepicker form-control" placeholder='HH:MM' data-mask='00:00' id="well_taken_up_time"
                                    value="{{ old('well_taken_up_time', $timeRegister->well_taken_up_time ?? '') }}">
                            <small class="form-text text-muted">Time</small>
                        </div>
                        <div class="col-6">
                            <input type="text" name="well_taken_up_date" class="datetimeinput datepicker form-control" placeholder='YYYY-MM-DD' data-mask='0000-00-00' id="well_taken_up_date"
                                    value="{{ old('well_taken_up_date', isset($timeRegister) ? $timeRegister->well_taken_up_date?->format('Y-m-d') : '') }}">
                            <small class="form-text text-muted">Date</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Well Handed Over Time - Single Line with Time left, Date right -->
                <div class="form-group">
                    <label class='form-label requiredField'>Well Handed Over:<small>*</small></label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="well_handed_over_time" class="datetimeinput timepicker form-control" placeholder='HH:MM' data-mask='00:00' id="well_handed_over_time"
                                    value="{{ old('well_handed_over_time', $timeRegister->well_handed_over_time ?? '') }}">
                            <small class="form-text text-muted">Time</small>
                        </div>
                        <div class="col-6">
                            <input type="text" name="well_handed_over_date" class="datetimeinput datepicker form-control" placeholder='YYYY-MM-DD' data-mask='0000-00-00' id="well_handed_over_date"
                                    value="{{ old('well_handed_over_date', isset($timeRegister) ? $timeRegister->well_handed_over_date?->format('Y-m-d') : '') }}">
                            <small class="form-text text-muted">Date</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class='form-label requiredField'>Job Carried out:<small>*</small></label>
                    <textarea name="job_carried_out" class="form-control" rows="4" required>{{ old('job_carried_out', $timeRegister->job_carried_out ?? '') }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label class='form-label requiredField'>Observations by Logging Party Chief:<small>*</small></label>
                    <textarea name="observations_by_logging_chief" class="form-control" rows="4" required>{{ old('observations_by_logging_chief', $timeRegister->observations_by_logging_chief ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-group mt-4">
                @if(isset($timeRegister) && $timeRegister->is_final_submitted)
                <button type="button" class="btn btn-success" disabled>Final Submitted</button>
                @else
                <button type="submit" name="save_draft" value="1" class="btn btn-secondary">
                    {{ isset($jcr) ? 'Save Draft & Continue' : 'Save as Draft' }}
                </button>
                <button type="submit" class="btn btn-primary">
                    Preview
                </button>
                @endif
                
                @if(isset($jcr))
                <a href="{{ session('intended_url', route('jcr.index')) }}" class="btn btn-light">
                    {{ $timeRegister->exists ? 'Back to JCR' : 'Cancel' }}
                </a>
                @else
                <a href="{{ route('time-registers.index') }}" class="btn btn-light">Cancel</a>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection
@push('js')
<script>
    // Generic initializers: use classes so any date/time input can be turned into pickers
    $('.datepicker').each(function () {
        $(this).datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            showTodayButton: true,
            showClear: true,
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                today: 'fa fa-calendar-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        });
    });

    $('.timepicker').each(function () {
        $(this).datetimepicker({
            format: 'HH:mm',
            useCurrent: false,
            showTodayButton: true,
            showClear: true,
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                today: 'fa fa-calendar-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        });
    });

    // Auto-open picker when input receives focus or is clicked
    $('.datepicker, .timepicker').on('focus click', function (e) {
        // Use the plugin API to show the widget
        try {
            $(this).datetimepicker('show');
        } catch (err) {
            // Fallback for Tempus Dominus or different API
            var dp = $(this).data('DateTimePicker') || $(this).data('datetimepicker');
            if (dp) {
                if (typeof dp.show === 'function') dp.show();
            }
        }
    });
</script>
@endpush