@extends('layouts.app')

@section('title', 'Create ' . $title)

@section('content')
<div class="container">
    <div class="card w-100">
        <div class="card-header">
            <h2>Create {{ $title }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.store', $type) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    @include('checklists.partials.header-type-' . $type)
                </div>

                <div class="checklist-items mb-4">
                    <h4 class="mb-3">Checklist Items</h4>
                    @include('checklists.partials.type-' . $type)
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Submit Checklist
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