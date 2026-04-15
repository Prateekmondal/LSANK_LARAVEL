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
                    @php
                        $typeLabels = ['a' => 'Pre-Departure', 'b' => 'On-Site', 'c' => 'Upon-Arrival'];
                        $hasAny = !empty($groupedUnlinkedChecklists) && (
                            (isset($groupedUnlinkedChecklists['a']) && $groupedUnlinkedChecklists['a']->count()) ||
                            (isset($groupedUnlinkedChecklists['b']) && $groupedUnlinkedChecklists['b']->count()) ||
                            (isset($groupedUnlinkedChecklists['c']) && $groupedUnlinkedChecklists['c']->count())
                        );
                    @endphp

                    @if($hasAny)
                        <form id="checklistSelectionForm">
                            @foreach(['a','b','c'] as $type)
                                @if(isset($groupedUnlinkedChecklists[$type]) && $groupedUnlinkedChecklists[$type]->count())
                                    <h5 class="mt-2 mb-1">{{ $typeLabels[$type] }}</h5>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Well No</th>
                                                <th>Job Date</th>
                                                <th>Logging Unit No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $seen = []; @endphp
                                            @foreach($groupedUnlinkedChecklists[$type] as $checklist)
                                                @php
                                                    $key = ($checklist->well_no ?? '') . '|' . ($checklist->date ?? '') . '|' . ($checklist->logging_unit_no ?? '');
                                                @endphp
                                                @if(!in_array($key, $seen))
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="checklist-checkbox" value="{{ $checklist->id }}"
                                                                data-well="{{ $checklist->well_no }}" data-date="{{ $checklist->date }}" data-type="{{ $type }}">
                                                        </td>
                                                        <td>{{ $checklist->well_no }}</td>
                                                        <td>{{ isset($checklist->date) ? date('d/m/Y', strtotime($checklist->date)) : '-' }}</td>
                                                        <td>{{ $checklist->logging_unit_no ?? '-' }}</td>
                                                    </tr>
                                                    @php $seen[] = $key; @endphp
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endforeach
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="saveChecklistSelection">Save Selection</button>
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

    <!-- WellNo Match Selection Modal -->
    <div class="modal fade" id="wellNoMatchModal" tabindex="-1" aria-labelledby="wellNoMatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wellNoMatchModalLabel">Existing JCR Found for Well No</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>We found existing JCR entries whose well number is contained in your entry. Choose one to prefill the form fields (excludes Time Info, Logs Recorded, SWC, Explosive Info and HSE Info).</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="wellNoMatchTable">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Job Date</th>
                                    <th>Well No</th>
                                    <th>Unit No</th>
                                    <th>Field/Area</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyWellNoMatch">Use Selected</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Register Selection Modal -->
    <div class="modal fade" id="timeRegisterModal" tabindex="-1" role="dialog" aria-labelledby="timeRegisterModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="timeRegisterModalLabel">
                        <i class="fas fa-link"></i> Link Time Register to JCR
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Important:</strong> Every JCR must be linked to a Time Register. 
                        Please select an existing Time Register or create a new one.
                    </div>

                    <!-- Available Time Registers List -->
                    <div id="availableTimeRegisters" class="mb-4">
                        <h6>Available Time Registers:</h6>
                        @if($availableTimeRegisters->count() > 0)
                        <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                            @foreach($availableTimeRegisters as $timeRegister)
                            <div class="list-group-item list-group-item-action time-register-item" 
                                 data-time-register-id="{{ $timeRegister->id }}"
                                 onclick="selectTimeRegister(this, {{ $timeRegister->id }})">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $timeRegister->logging_unit_no }}</h6>
                                    <small class="text-success">
                                        @if($timeRegister->is_final_submitted)
                                            <div class="d-flex">
                                                <div>
                                                    @foreach($timeRegister->jcrs as $timeRegisterJCR)
                                                        <div class="d-flex">
                                                        Linked JCR Date: {{ date('d/m/Y', strtotime($timeRegisterJCR->wellTaken_date)) }} |
                                                        Linked JCR Well: {{ $timeRegisterJCR->wellNo ?? 'N/A' }} |
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>Final Submitted</div>
                                            </div>
                                        @else
                                            <div class="d-flex">
                                                <div>
                                                    @foreach($timeRegister->jcrs as $timeRegisterJCR)
                                                        <div class="d-flex">
                                                        Linked JCR Date: {{ date('d/m/Y', strtotime($timeRegisterJCR->wellTaken_date)) }} |
                                                        Linked JCR Well: {{ $timeRegisterJCR->wellNo ?? 'N/A' }} |
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>{{ ucfirst($timeRegister->status) }}</div>
                                            </div>
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1">
                                    <strong>Well:</strong> {{ $timeRegister->well_no }} | 
                                    <strong>Rig:</strong> {{ $timeRegister->rig_no }} |
                                    <strong>Well Taken:</strong> {{ date('d/m/Y', strtotime($timeRegister->well_taken_up_date)) }}
                                </p>
                                <small class="text-muted">
                                    Job: {{ Str::limit($timeRegister->job_carried_out, 80) }}
                                </small>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewTimeRegisterDetails({{ $timeRegister->id }}, event)">
                                        View Details
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning">
                            No available Time Registers found. You need to create a new one.
                        </div>
                        @endif
                    </div>

                    <!-- Selected Time Register Details -->
                    <div id="selectedTimeRegister" class="border p-3 mb-3" style="display: none;">
                        <h6>Selected Time Register:</h6>
                        <div id="timeRegisterDetails"></div>
                    </div>

                    <!-- Create New Time Register Option -->
                    <div class="text-center">
                        <hr>
                        <p class="mb-3">Can't find a suitable Time Register?</p>
                        <a href="{{ route('time-registers.create') }}?from_jcr=true" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Time Register
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cancelJcrCreation()">Cancel JCR Creation</button>
                    <button type="button" class="btn btn-success" id="confirmSelectionBtn" onclick="confirmTimeRegisterSelection()" disabled>
                        <i class="fas fa-check"></i> Use Selected Time Register
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class='container-fluid px-0' id='grad1'>
        <div class='container px-0 pt-3 pb-0 mt-3 mb-3 text-center bg-white'>
            <h3><strong>Add New JCR Entry</strong></h3>
            <p class='h6'>Fill all <span class='asteriskField'>*</span> marked fields to go to next step</p>
            <form id='msform' action="{{ route('jcr.store') }}" method='POST'>
                @csrf
                @include('jcr._form')
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src='../static/js/addjcr.js'></script>
    <script>
        // Auto-open checklist modal only if no checklists are selected
        document.addEventListener('DOMContentLoaded', function () {
            var selectedInput = document.getElementById('selectedChecklistIds');
            var hasSelection = selectedInput && selectedInput.value.trim() !== '';
            if (!hasSelection) {
                var openBtn = document.getElementById('openChecklistModal');
                if (openBtn) openBtn.click();
            }

            // Save selected checklist IDs to hidden input and update preview
            var saveBtn = document.getElementById('saveChecklistSelection');
            if (saveBtn) saveBtn.onclick = function () {
                let selected = [];
                document.querySelectorAll('.checklist-checkbox:checked').forEach(function (cb) {
                    selected.push(cb.value);
                });
                var input = document.getElementById('selectedChecklistIds');
                if (input) input.value = selected.join(',');
                if (typeof updateSelectedChecklistPreview === 'function') updateSelectedChecklistPreview();
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
                    // Update preview on any change
                    updateSelectedChecklistPreview();
                });
            });

            // Initial preview (if any checkboxes pre-checked)
            updateSelectedChecklistPreview();
        });

        function updateSelectedChecklistPreview() {
            var preview = document.getElementById('linkedChecklistPreview');
            var countEl = document.getElementById('linkedChecklistCount');
            if (!preview || !countEl) return;

            var selected = Array.from(document.querySelectorAll('.checklist-checkbox:checked'));
            countEl.textContent = selected.length;

            if (selected.length === 0) {
                preview.innerHTML = '<div class="text-muted">No checklists selected.</div>';
                return;
            }

            var html = '<ul class="list-inline">';
            selected.forEach(function (cb) {
                var well = cb.getAttribute('data-well') || '-';
                var date = formatDateForPreview(cb.getAttribute('data-date'));
                var type = cb.getAttribute('data-type') || '';
                var typeLabel = type ? (' (' + type.toUpperCase() + ')') : '';
                html += '<li class="list-inline-item badge bg-secondary me-1">' + well + ' - ' + date + typeLabel + '</li>';
            });
            html += '</ul>';
            preview.innerHTML = html;
        }

        function formatDateForPreview(d) {
            if (!d) return '-';
            // Expecting YYYY-MM-DD
            var m = d.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (m) return m[3] + '/' + m[2] + '/' + m[1];
            return d;
        }

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
                        // Update preview to reflect uncheck
                        if (typeof updateSelectedChecklistPreview === 'function') updateSelectedChecklistPreview();
                        showMissingChecklistModal(data.missing, wellName, jobDate);
                    }
                });
        }

        function showMissingChecklistModal(missingTypes, wellName, jobDate) {
            let typeNames = { 'a': 'Pre-Departure', 'b': 'On-Site', 'c': 'Upon-Arrival' };
            let html = '<ul>';

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

        // WellNo matching and auto-prefill
        document.addEventListener('DOMContentLoaded', function () {
            var wellNoInput = document.getElementById('id_wellNo');
            if (!wellNoInput) return;

            var searchTimeout = null;
            wellNoInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    var value = wellNoInput.value.trim();
                    if (value.length < 2) {
                        return;
                    }
                    fetch(`{{ route('jcr.ajax.wellNoMatch') }}?wellNo=${encodeURIComponent(value)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.data || data.data.length === 0) {
                                return;
                            }

                            var tableBody = document.querySelector('#wellNoMatchTable tbody');
                            tableBody.innerHTML = '';
                            window.__matchedJcrsForWellNo = data.data || [];
                            window.__matchedJcrsForWellNo.forEach(function (item, index) {
                                var row = document.createElement('tr');
                                row.innerHTML = `
                                    <td><input type="radio" name="selectedWellNoJcr" value="${index}" data-id="${item.id}"></td>
                                    <td>${item.arrivalOffice_date ? item.arrivalOffice_date : item.jobDate || ''}</td>
                                    <td>${item.wellNo}</td>
                                    <td>${item.unitNo || ''}</td>
                                    <td>${item.fieldName || ''}</td>
                                `;
                                tableBody.appendChild(row);
                            });

                            var wellNoModal = new bootstrap.Modal(document.getElementById('wellNoMatchModal'));
                            wellNoModal.show();
                        })
                        .catch(err => {
                            console.error('WellNo search failed', err);
                        });
                }, 600);
            });

            document.getElementById('applyWellNoMatch').addEventListener('click', function () {
                var selected = document.querySelector('input[name="selectedWellNoJcr"]:checked');
                if (!selected) {
                    alert('Please select a row first.');
                    return;
                }
                var selectedIndex = parseInt(selected.value, 10);
                var jcrData = (window.__matchedJcrsForWellNo || [])[selectedIndex] || null;
                if (!jcrData) {
                    alert('Unable to load selected JCR data.');
                    return;
                }

                // Override jobDate with arrivalOffice_date as per request
                if (jcrData.arrivalOffice_date) {
                    jcrData.jobDate = jcrData.arrivalOffice_date;
                }

                fillFormFromJcr(jcrData);
                var wellNoModalElement = document.getElementById('wellNoMatchModal');
                bootstrap.Modal.getInstance(wellNoModalElement).hide();
            });

            function fillFormFromJcr(jcrData) {
                var excludedFieldsets = ['timeinfo', 'logsrecorded', 'swc', 'explosiveinfo', 'hseinfo'];
                var excludedNames = ['time_register_id', 'selected_time_register_id', 'final_submit', 'creator_id', 'creator_signature', 'creator_signed_at', 'party_chief_id', 'party_chief_signature', 'party_chief_signed_at', 'operation_incharge_id', 'operation_incharge_signature', 'operation_incharge_signed_at', 'status', 'sap_document_number', 'sap_pushed_at', 'sap_status'];
                var excludedIds = ['id_jobDate', 'id_jobNo', 'id_workOrderDate', 'id_indentNo']; // add IDs here to explicitly avoid prefill

                document.querySelectorAll('#msform [name]').forEach(function (el) {
                    var name = el.name;
                    if (!name || !(name in jcrData)) return;

                    
                    var insideExcluded = excludedFieldsets.some(function (fieldsetId) {
                        return el.closest('#' + fieldsetId) !== null;
                    });
                    if (insideExcluded) return;
                    
                    if (excludedNames.includes(name)) return;
                    if (el.id && excludedIds.includes(el.id)) return;

                    // set input, select or textarea
                    if (el.tagName.toLowerCase() === 'select' || el.tagName.toLowerCase() === 'textarea' || el.type === 'text' || el.type === 'number' || el.type === 'date' || el.type === 'time') {
                        el.value = jcrData[name] !== null && jcrData[name] !== undefined ? jcrData[name] : '';
                    }
                    if (el.type === 'checkbox') {
                        el.checked = !!jcrData[name];
                    }
                });

                // Prefill personnel fieldset from matched JCR users (if returned)
                if (Array.isArray(jcrData.users)) {
                    var wrapper = document.getElementById('wrapper');
                    if (wrapper) {
                        var userSelectTemplate = wrapper.querySelector('.element');
                        var optionsHtml = '';
                        if (userSelectTemplate) {
                            var firstSelect = userSelectTemplate.querySelector('select');
                            if (firstSelect) optionsHtml = firstSelect.innerHTML;
                        }

                        wrapper.innerHTML = '';

                        jcrData.users.forEach(function (userItem, idx) {
                            var newIndex = idx + 1;
                            var element = document.createElement('div');
                            element.className = 'mb-3 element';
                            element.id = 'userinlinemodel_' + newIndex;
                            element.innerHTML = `
                                <label for='select_${newIndex}' class='form-label requiredField' id='personnellabel_${newIndex}'>Personnel<span class='asteriskField' id='asterisk_${newIndex}'>*</span></label>
                                <select data-live-search='true' class='select form-select personnelselect' id='select_${newIndex}' name='personnel[${idx}][user_id]'>
                                    ${optionsHtml || "<option value=''>--- Select Personnel ---</option>"}
                                </select>
                                <button class='btn btn-danger btn-sm remove my-3' id='remove_personnel_${newIndex}' type='button'><i class='fa fa-minus-circle'></i></button>
                            `;
                            wrapper.appendChild(element);

                            var selectEl = element.querySelector('select');
                            if (selectEl) {
                                selectEl.value = userItem.id || userItem.user_id || '';
                            }
                        });
                    }
                }
            }
        });
        
        
        let selectedTimeRegisterId = null;

        // Show modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            $('#timeRegisterModal').modal('show');
        });

        function selectTimeRegister(element, timeRegisterId) {
            // Remove active class from all items
            document.querySelectorAll('.time-register-item').forEach(item => {
                item.classList.remove('active', 'bg-primary', 'text-white');
            });
            
            // Add active class to selected item
            element.classList.add('active', 'bg-primary', 'text-white');
            
            // Enable confirm button
            document.getElementById('confirmSelectionBtn').disabled = false;
            selectedTimeRegisterId = timeRegisterId;
            
            // Load time register details
            loadTimeRegisterDetails(timeRegisterId);
        }

        function loadTimeRegisterDetails(timeRegisterId) {
            fetch(`/ajax/time-register/${timeRegisterId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    // Helper to pad numbers
                    function pad(n) { return String(n).padStart(2, '0'); }

                    // Format time as HH:MM
                    function formatTime(t) {
                        if (!t && t !== 0) return '-';
                        if (typeof t === 'string') {
                            if (/^\d{1,2}:\d{2}(:\d{2})?$/.test(t)) {
                                const parts = t.split(':');
                                return pad(parts[0]) + ':' + pad(parts[1]);
                            }
                            // ISO datetime
                            if (t.includes('T')) {
                                const dt = new Date(t);
                                if (!isNaN(dt)) return pad(dt.getHours()) + ':' + pad(dt.getMinutes());
                            }
                        }
                        const dt = new Date(t);
                        if (!isNaN(dt)) return pad(dt.getHours()) + ':' + pad(dt.getMinutes());
                        return String(t);
                    }

                    // Format date as YYYY-MM-DD
                    function formatDate(d) {
                        if (!d) return '-';
                        if (typeof d === 'string') {
                            if (/^\d{4}-\d{2}-\d{2}/.test(d)) return d.slice(0, 10);
                            if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(d)) {
                                const parts = d.split('/'); // dd/mm/yyyy
                                return parts[2] + '-' + pad(parts[1]) + '-' + pad(parts[0]);
                            }
                        }
                        const dt = new Date(d);
                        if (!isNaN(dt)) return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate());
                        return String(d);
                    }

                    const formattedTime = formatTime(data.well_indented_time);
                    const formattedDate = formatDate(data.well_indented_date);

                    document.getElementById('selectedTimeRegister').style.display = 'block';
                    document.getElementById('timeRegisterDetails').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Logging Unit:</strong> ${data.logging_unit_no}</p>
                                <p><strong>Well No:</strong> ${data.well_no}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Rig No:</strong> ${data.rig_no}</p>
                                <p><strong>Well Indented:</strong> ${formattedTime} on ${formattedDate}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Indent No.:</strong> ${data.indent_no}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Job Carried Out:</strong> ${data.job_carried_out}</p>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading time register details:', error);
                    alert('Error loading time register details. Please try again.');
                });
        }

        function viewTimeRegisterDetails(timeRegisterId, event) {
            event.stopPropagation();
            window.open(`/time-registers/${timeRegisterId}`, '_blank');
        }

        function confirmTimeRegisterSelection() {
            if (!selectedTimeRegisterId) {
                alert('Please select a Time Register first.');
                return;
            }

            // Set the hidden input value
            document.getElementById('selected_time_register_id').value = selectedTimeRegisterId;
            
            // Update preview
            document.getElementById('linkedTimeRegisterPreview').innerHTML = 
                document.getElementById('timeRegisterDetails').innerHTML;
            
            // Hide modal and show form
            $('#timeRegisterModal').modal('hide');
        }

        function showTimeRegisterModal() {
            $('#timeRegisterModal').modal('show');
        }

        function cancelJcrCreation() {
            if (confirm('Are you sure you want to cancel JCR creation?')) {
                window.location.href = "{{ route('jcr.index') }}";
            }
        }

        // Handle when user returns from creating new time register
        @if(request()->has('from_jcr') && request()->has('time_register_id'))
        selectedTimeRegisterId = {{ request()->time_register_id }};
        confirmTimeRegisterSelection();
        @endif
    </script>
@endpush