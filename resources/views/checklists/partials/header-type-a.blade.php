<div class="col-md-4">
    <label for="logging_unit_no" class="form-label">Logging unit *</label>
    <input type="text" class="form-control" id="logging_unit_no" name="logging_unit_no" value="{{ old('logging_unit_no', $checklist->logging_unit_no ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="date" class="form-label">Preparation Date *</label>
    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $checklist->date ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="well_no" class="form-label">Proceed to Well No. *</label>
    <input type="text" class="form-control" id="well_no" name="well_no" value="{{ old('well_no', $checklist->well_no ?? '') }}" required>
</div>