<div class="col-md-4">
    <label for="well_no" class="form-label">Well No *</label>
    <input type="text" class="form-control" id="well_no" name="well_no" value="{{ old('well_no', $checklist->well_no ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="rig" class="form-label">Rig *</label>
    <input type="text" class="form-control" id="rig" name="rig" value="{{ old('rig', $checklist->rig ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="logging_unit_no" class="form-label">Logging unit *</label>
    <input type="text" class="form-control" id="logging_unit_no" name="logging_unit_no" value="{{ old('logging_unit_no', $checklist->logging_unit_no ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="job_type" class="form-label">Job Type *</label>
    <input type="text" class="form-control" id="job_type" name="job_type" value="{{ old('job_type', $checklist->job_type ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="perf_interval" class="form-label">Perforation Interval *</label>
    <input type="text" class="form-control" id="perf_interval" name="perf_interval" value="{{ old('perf_interval', $checklist->perf_interval ?? '') }}" required>
</div>
<div class="col-md-4">
    <label for="date" class="form-label">Date *</label>
    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $checklist->date ?? '') }}" required>
</div>