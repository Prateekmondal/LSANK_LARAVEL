@extends('layouts.app')
@push('css')
    <link rel='stylesheet' type='text/css' href='../static/css/addjcrstyle.css'>
@endpush
@section('content')
    <!-- Modal Trigger (auto-open with JS) -->
    <button type="button" id="openChecklistModal" style="display:none;" data-bs-toggle="modal"
        data-bs-target="#checklistModal"></button>

    <!-- Modal -->
    <div class="modal fade" id="checklistModal" tabindex="-1" aria-labelledby="checklistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checklistModalLabel">Select Checklists to Link</h5>
                </div>
                <div class="modal-body">
                    @if($unlinkedChecklists->count())
                        <form id="checklistSelectionForm">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Well No</th>
                                        <th>Job Date</th>
                                        <th>Logging Unit No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previous_checklist = null;
                                    @endphp
                                    @foreach($unlinkedChecklists as $checklist)
                                        @if ($previous_checklist != null)
                                            @if (
                                                    $previous_checklist->well_no != $checklist->well_no
                                                    && $previous_checklist->date != $checklist->date
                                                    && $previous_checklist->logging_unit_no != $checklist->logging_unit_no
                                                )
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="checklist-checkbox" value="{{ $checklist->id }}"
                                                            data-well="{{ $checklist->well_no }}" data-date="{{ $checklist->date }}">
                                                    </td>
                                                    <td>{{ $checklist->well_no }}</td>
                                                    <td>{{ $checklist->date }}</td>
                                                    <td>{{ $checklist->logging_unit_no ?? '-' }}</td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checklist-checkbox" value="{{ $checklist->id }}"
                                                        data-well="{{ $checklist->well_no }}" data-date="{{ $checklist->date }}">
                                                </td>
                                                <td>{{ $checklist->well_no }}</td>
                                                <td>{{ $checklist->date }}</td>
                                                <td>{{ $checklist->logging_unit_no ?? '-' }}</td>
                                        @endif
                                            @php
                                                $previous_checklist = $checklist;
                                            @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                id="saveChecklistSelection">Save Selection</button>
                        </form>
                    @else
                        <p>No unlinked checklists available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for missing checklists -->
    <div class="modal fade" id="missingChecklistModal" tabindex="-1" aria-labelledby="missingChecklistModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="missingChecklistModalLabel">Missing Checklists</h5>
                </div>
                <div class="modal-body" id="missingChecklistBody">
                    <!-- Filled by JS -->
                    <table>
                        <tbody id="missingChecklistList">
                            <!-- Missing checklist items will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $loggingTypes = ['WL', 'PCL', 'FISHING', 'TCP', 'TCP-DST', 'LWD'];
        $logTypes = ['OH', 'CH', 'PL'];
        $unitNos = ["GJ-16-BS-4773", "GJ-16-BS-4995", "GJ-16-AF-9723", "GJ-16-BS-2279", "GJ-16-BS-4842", "GJ-16-AF-9702"];
        $wellOwners = ['Asset', 'Basin', 'Cambay'];
        $wellTypes = ['Dev', 'Exp'];
        $rigTypes = ['DRILLING', 'WORKOVER', 'RIGLESS'];
        $cableSizes = ['5/16', '7/32', '15/32', 'Others'];
        $cableHeadSizes = ['1 7/16', '3 3/8', 'Others'];
    @endphp
    <div class='container-fluid px-0' id='grad1'>
        <div class='container px-0 pt-3 pb-0 mt-3 mb-3 text-center bg-white'>
            <h3><strong>Add New JCR Entry</strong></h3>
            <p class='h6'>Fill all <span class='asteriskField'>*</span> marked fields to go to next step</p>
            
        </div>
    </div>
    </div>
    </div>

@endsection
@push('js')
    <script src='../static/js/addjcr.js'></script>
    <script>
        window.onload = function () {
            document.getElementById('openChecklistModal').click();
        };

        // Save selected checklist IDs to hidden input
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('saveChecklistSelection').onclick = function () {
                let selected = [];
                document.querySelectorAll('.checklist-checkbox:checked').forEach(function (cb) {
                    selected.push(cb.value);
                });
                document.getElementById('selectedChecklistIds').value = selected.join(',');
            };

            // Ensure selection is saved before form submit
            document.getElementById('msform').onsubmit = function () {
                let selected = [];
                document.querySelectorAll('.checklist-checkbox:checked').forEach(function (cb) {
                    selected.push(cb.value);
                });
                document.getElementById('selectedChecklistIds').value = selected.join(',');
            };

            // Add change event to all checklist checkboxes
            document.querySelectorAll('.checklist-checkbox').forEach(function (cb) {
                cb.addEventListener('change', function () {
                    if (cb.checked) {
                        const wellName = cb.getAttribute('data-well');
                        const jobDate = cb.getAttribute('data-date');
                        checkChecklistGroup(wellName, jobDate, cb);
                    }
                });
            });
        });

        function checkChecklistGroup(wellName, jobDate, checkbox) {
            fetch('{{ route('checklists.checkGroup') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ well_no: wellName, job_date: jobDate })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.all_present) {
                        // Uncheck the box if group is incomplete
                        if (checkbox) checkbox.checked = false;
                        showMissingChecklistModal(data.missing, wellName, jobDate);
                    }
                });
        }

        function showMissingChecklistModal(missingTypes, wellName, jobDate) {
            let typeNames = { 'a': 'Pre-Departure', 'b': 'On-Site', 'c': 'Upon-Arrival' };
            let html = '<ul>';
            console.log(Object.values(missingTypes));

            Object.values(missingTypes).forEach(type => {
                html += `<tr>
                            <td class="pb-2">${typeNames[type]} Checklist missing.</td>
                            <td class="pb-2">
                                <a href="/checklists/create/${type}?well_no=${encodeURIComponent(wellName)}&date=${encodeURIComponent(jobDate)}" class="btn btn-sm btn-primary ms-2">Create Now</a>
                            </td>
                            </tr>`;
            });
            html += '</ul>';
            document.getElementById('missingChecklistList').innerHTML = html;
            var missingModal = new bootstrap.Modal(document.getElementById('missingChecklistModal'));
            missingModal.show();
        }
    </script>
@endpush