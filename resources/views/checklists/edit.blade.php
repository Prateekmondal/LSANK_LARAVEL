@extends('layouts.app')

@section('title', 'Edit ' . $title)

@section('content')
<div class="container">
    <div class="card w-100">
        <div class="card-header">
            <h2>Edit {{ $title }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.update', $checklist->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="mb-1">Please fix the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-3">
                    @include('checklists.partials.header-type-' . $checklist->type)
                </div>

                <div class="checklist-items mb-4">
                    <h4 class="mb-3">Checklist Items</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 60%">Item</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 25%">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checklist->checklist_data as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>
                                            <select name="items[{{ $index }}][status]" class="form-select form-select-sm">
                                                <option value="1" {{ $item['status'] ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$item['status'] ? 'selected' : '' }}>No</option>
                                                @if($item['status'] == NULL)
                                                    <option value="" selected>N/A</option>
                                                @endif
                                            </select>
                                            <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] }}">
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][comments]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $item['comments'] ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Checklist
                    </button>
                </div>
            </form>
        </div>
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