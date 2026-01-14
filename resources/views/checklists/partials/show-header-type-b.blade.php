<div class="row mb-3">
    <div class="col-md-4"><strong>Well Name:</strong> {{ $checklist->well_no }}</div>
    <div class="col-md-4"><strong>Rig:</strong> {{ $checklist->rig }}</div>
    <div class="col-md-4"><strong>Logging Unit:</strong> {{ $checklist->logging_unit_no }}</div>
</div>
<div class="row mb-3">
    <div class="col-md-4"><strong>Job Type:</strong> {{ $checklist->job_type }}</div>
    <div class="col-md-4"><strong>Perforation interval:</strong> {{ $checklist->perf_interval }}</div>
    <div class="col-md-4"><strong>Date:</strong> {{ $checklist->date->format('d/m/Y') }}</div>
</div>