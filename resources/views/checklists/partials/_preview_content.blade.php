
<div class="row mb-4">
    <div class="d-flex justify-content-between align-items-top">
        <img class="img-fluid" src="/static/images/ongc.png" style="max-height: 80px;"/>
        <div class="text-center">
            <h2 class="fw-bold">Oil and Natural Gas Corporation Limited</h2>
            <h4 class="fw-bold">Well Logging Services, Ankleshwar Asset, Ankleshwar-393010</h4>
        </div>
        <div class="text-end align-top"><p>Checklist-{{ strtoupper($checklist->type) }}</p></div>
    </div>
</div>
@include('checklists.partials.show-header-type-' . $checklist->type)

<div class="table-responsive mb-4">
<table class="table table-bordered">
<thead class="table-light">
<tr>
    <th>Item</th>
    <th>Status</th>
    <th>Comments</th>
</tr>
</thead>
<tbody>
@foreach($checklist->checklist_data as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td>
            <span class="badge bg-{{ $item['status'] ? 'success' : ($item['status'] == null ? 'warning' : 'danger') }}">
                {{ $item['status'] ? 'Yes' : ($item['status'] == null ? 'N/A' : 'No') }}
            </span>
        </td>
        <td>{{ $item['comments'] ?? 'N/A' }}</td>
    </tr>
@endforeach
</tbody>
</table>
</div>