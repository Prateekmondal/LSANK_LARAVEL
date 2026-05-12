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
    <label for="date" class="form-label">Preparation Date <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" id="date" name="date" value="{{ isset($checklist) ? date('Y-m-d', strtotime($checklist->date)) : old('date') }}" required>
</div>
<div class="col-md-4">
    <label for="well_no" class="form-label">Proceed to Well No. <span class='asteriskField'>*</span></label>
    <input type="text" class="form-control" id="well_no" name="well_no" value="{{ old('well_no', $checklist->well_no ?? '') }}" required>
</div>