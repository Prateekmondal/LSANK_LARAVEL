
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
            <span class="badge bg-{{ $item['status'] ? 'success' : 'danger' }}">
                {{ $item['status'] ? 'Yes' : 'No' }}
            </span>
        </td>
        <td>{{ $item['comments'] ?? 'N/A' }}</td>
    </tr>
@endforeach
</tbody>
</table>
</div>