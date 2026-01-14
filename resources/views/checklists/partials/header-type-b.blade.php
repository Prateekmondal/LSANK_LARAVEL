@php
$unitNos = ["GJ-16-BS-4773", "GJ-16-BS-4995", "GJ-16-AF-9723", "GJ-16-BS-2279", "GJ-16-BS-4842", "GJ-16-AF-9702"];
@endphp

<div class="col-md-4">
    <label for="well_no" class="form-label">Well No <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control" id="well_no" name="well_no" value="{{ old('well_no', $checklist->well_no ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="rig" class="form-label">Rig <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control" id="rig" name="rig" value="{{ old('rig', $checklist->rig ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="logging_unit_no" class="form-label">Logging unit <span class='asteriskField'>*</span></label>
    <select name='logging_unit_no' class='select form-select' id='logging_unit_no'>
        <option value='' disabled {{ old('logging_unit_no') == '' ? 'selected' : '' }}>--- Select Unit ---
        </option>
        @foreach ($unitNos as $unitNo)
            <option value='{{ $unitNo }}' {{ old('logging_unit_no') == $unitNo || (isset($checklist) && $checklist->logging_unit_no == $unitNo) ? 'selected' : '' }}>
                {{ $unitNo }}
            </option>
        @endforeach
    </select>
</div>
<div class="col-md-4">
    <label for="job_type" class="form-label">Job Type <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control" id="job_type" name="job_type" value="{{ old('job_type', $checklist->job_type ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="perf_interval" class="form-label">Perforation Interval <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control" id="perf_interval" name="perf_interval" value="{{ old('perf_interval', $checklist->perf_interval ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="date" class="form-label">Date <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" id="date" name="date" value="{{ isset($checklist) ? date('Y-m-d', strtotime($checklist->date)) : old('date') }}" required>
</div>