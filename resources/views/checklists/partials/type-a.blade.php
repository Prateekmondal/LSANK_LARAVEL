<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 60%">Pre-Departure Checklist Items</th>
                <th style="width: 10%">Status</th>
                <th style="width: 25%">Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'Safety tube',
                'Detonator carrying box with proper lock and key',
                'Explosive remanent box with proper lock and key',
                'Casing to rig voltage monitor',
                'Safety meter for testing detonators',
                'Safety grounding device of truck/unit with spool and c-clamps',
                'Has the explosive field checklist been taken?',
                'Has the following sign boards in Hindi, English and regional languages taken?',
                'Danger Zone, Explosive in use, turn off all radios.',
                'Danger Zone, Explosive in use, turn off all generators.',
                'Danger Zone, Explosive in use, turn off all welding machines.',
            ] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item }}</td>
                    <td>
                        <select name="checklist_data[{{ $index }}][status]" class="form-select form-select-sm">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                        <input type="hidden" name="checklist_data[{{ $index }}][name]" value="{{ $item }}">
                    </td>
                    <td>
                        <input type="text" name="checklist_data[{{ $index }}][comments]" class="form-control form-control-sm">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>