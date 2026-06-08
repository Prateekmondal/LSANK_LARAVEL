@php
    $loggingTypes = ['WL', 'PCL', 'FISHING', 'TCP', 'TCP-DST', 'LWD'];
    $logTypes = ['OH', 'CH', 'PL'];
    $wellOwners = ['Asset', 'Basin', 'Cambay'];
    $wellTypes = ['Dev', 'Exp'];
    $rigTypes = ['DRILLING', 'WORKOVER', 'RIGLESS'];
    $cableSizes = ['5/16', '7/32', '15/32', 'Others'];
    $cableHeadSizes = ['1 7/16', '3 3/8', 'Others'];
    $explosivelists = ['23gm TAG', '23gm TAG(BH)', '25gm TAG', '39gm TAG', '8gm TTP', 'BP Power Charge', 'Casing Cutter Charge', 'Tubing Cutter Charge', 'RTG Charge', 'SWC Charge', 'SPLIT Shot Charge'];
    $primachordlists = ['T-150', 'T-190', 'PT-185', 'PT-150', 'BP SECONDARY IGNITOR'];
    $detonatorlists = ['15FDE', '26FDE', '1015E', 'BP PRIMARY IGNITOR', 'CUTTER DETO 432'];
@endphp

@isset($jcr)
    <!-- Selected Time Register Preview -->
    <div class="card my-4 w-75">
        <div class="card-header bg-light">
            <h6 class="mb-0">Linked Time Register</h6>
        </div>
        <div class="card-body">
            <div id="linkedTimeRegisterPreview">
                @if($jcr->timeRegister)
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Logging Unit:</strong> {{ $jcr->timeRegister->logging_unit_no }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Well No:</strong> {{ $jcr->timeRegister->well_no }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Rig No:</strong> {{ $jcr->timeRegister->rig_no }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Logging Chief:</strong> {{ $jcr->timeRegister->logging_chief_name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Indent No.:</strong> {{ $jcr->timeRegister->indent_no }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Job Carried Out:</strong> {{ $jcr->timeRegister->job_carried_out }}</p>
                        </div>
                    </div>
                    
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-warning" onclick="showTimeRegisterModal()">
                Change Time Register
            </button>
        </div>
    </div>
    <!-- Button to open checklist modal and summary of currently linked checklists -->
    <div class="card my-4 w-75">
        <div class="card-header bg-light">
            <h6 class="mb-0">Linked Checklists</h6>
        </div>
        <div class="card-body mb-2">
            <div>
                <small class="text-muted">Linked Checklists:</small>
                <strong id="linkedChecklistCount">{{ $jcr->checklists->count() }}</strong>
            </div>

            @if($jcr->checklists->isNotEmpty())
            <div id="linkedChecklistPreview" class="mb-3">
                <ul class="list-inline">
                    @foreach($jcr->checklists as $cl)
                        <li class="list-inline-item badge bg-secondary">
                            {{ $cl->well_no ?? '-' }} - {{ isset($cl->date) ? date('d/m/Y', strtotime($cl->date)) : '-' }}
                            ({{ strtoupper($cl->type ?? '') }})
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div id="linkedChecklistPreview" class="mb-3">
                <div class="text-muted">No checklists linked.</div>
            </div>
        @endif
        
            <button type="button" class="btn btn-sm btn-warning"
                        data-bs-toggle="modal" data-bs-target="#checklistModal">{{ $jcr->checklists->count() > 0 ? 'Change Checklists' : 'Select Checklists' }}</button>
        </div>
    </div>

    <ul class='d-flex justify-content-between' id='progressbar'>
        <li class='progress-step active' id='firststep'><strong>First Step</strong></li>
        <li class='progress-step' id='secondstep'><strong>Second Step</strong></li>
        <li class='progress-step' id='thirdstep'><strong>Third Step</strong></li>
        <li class='progress-step' id='finalstep'><strong>Final Step</strong></li>
    </ul>
    <div class='container-fluid pb-3 form-step'>
        <!-- Hidden input for selected checklist ids (pre-filled on edit) -->
        <input type="hidden" name="checklist_ids" id="selectedChecklistIds"
               value="{{ old('checklist_ids', $jcr->checklists->pluck('id')->join(',')) }}">
        <input name="time_register_id" id="selected_time_register_id" type="hidden"
               value="{{ old('time_register_id', $jcr->timeRegister ? $jcr->timeRegister->id : '') }}">

        <div class='d-flex'>
            <div class='card h-100 w-25'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='basicinfo'>
                        <div class='form-card step w-20'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Basic Info</h2>
                            <div id='div_id_fieldName' class='mb-3'>
                                <input type="number" hidden name="id" value="{{ old('id', $jcr->id) }}">
                                <label for='id_fieldName' class='form-label requiredField'>
                                    Field/Area<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='fieldName' placeholder='Field/Area'
                                    class='textinput textInput form-control' id='id_fieldName'
                                    value="{{ old('fieldName', $jcr->fieldName) }}">
                                @error('fieldName')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellNo' class='mb-3'>
                                <label for='id_wellNo' class='form-label requiredField'>
                                    Well No./Code<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='wellNo' placeholder='Well No./Code'
                                    class='textinput textInput form-control' id='id_wellNo'
                                    value="{{ old('wellNo', $jcr->wellNo) }}">
                                @error('wellNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_loggingType' class='mb-3'>
                                <label for='id_loggingType' class='form-label requiredField'>
                                    Logging Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='loggingType' placeholder='Logging Type' class='select form-select'
                                    id='id_loggingType'>
                                    <option value="" disabled {{ old('loggingType') == '' ? 'selected' : ''}}>--- Select
                                        Logging Type ---</option>
                                    @foreach ($loggingTypes as $loggingType)
                                        <option value="{{ $loggingType }}" {{ old('loggingType', $jcr->loggingType) == $loggingType ? 'selected' : ''}}>{{ $loggingType }}</option>
                                    @endforeach
                                </select>
                                @error('loggingType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_logType' class='mb-3'>
                                <label for='id_logType' class='form-label requiredField'>
                                    Job Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='logType' placeholder='Job Type' class='select form-select' id='id_logType'>
                                    <option value='' disabled {{ old('logType') == '' ? 'selected' : '' }}>--- Select Job Type
                                        ---</option>
                                    @foreach ($logTypes as $logType)
                                        <option value='{{ $logType }}' {{ old('logType', $jcr->logType) == $logType ? 'selected' : '' }}>
                                            {{ $logType }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('logType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_logging_unit_type' class='mb-3'>
                                <label for='id_logging_unit_type' class='form-label requiredField'>
                                    Logging Unit Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='logging_unit_type' class='select form-select' id='id_logging_unit_type'>
                                    <option value='departmental' {{ old('logging_unit_type', $jcr->logging_unit_type ?? 'departmental') == 'departmental' ? 'selected' : '' }}>
                                        Departmental
                                    </option>
                                    <option value='contractual' {{ old('logging_unit_type', $jcr->logging_unit_type ?? 'departmental') == 'contractual' ? 'selected' : '' }}>
                                        Contractual
                                    </option>
                                </select>
                                @error('logging_unit_type')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_unitNo' class='mb-3'>
                                <label for='id_unitNo' class='form-label requiredField'>
                                    Logging Unit Number<span class='asteriskField'>*</span>
                                </label>
                                <select name='unitNo' class='select form-select' id='id_unitNo'>
                                    <option value='' disabled {{ old('unitNo') == '' ? 'selected' : '' }}>--- Select Unit ---
                                    </option>
                                    @foreach ($unitNos as $unitNo)
                                        <option value='{{ $unitNo }}' {{ old('unitNo', $jcr->unitNo) == $unitNo ? 'selected' : '' }}>
                                            {{ $unitNo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unitNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_jobDate' class='mb-3'>
                                <label for='id_jobDate' class='form-label requiredField'>
                                    Job Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='jobDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_jobDate' autocomplete='off' maxlength='10'
                                    value="{{ old('jobDate', date('Y-m-d', strtotime($jcr->jobDate))) }}">
                                @error('jobDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_jobNo' class='mb-3'>
                                <label for='id_jobNo' class='form-label requiredField'>
                                    Job No.<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='jobNo' placeholder='Job No.'
                                    class='textinput textInput form-control' id='id_jobNo'
                                    value="{{ old('jobNo', $jcr->jobNo) }}">
                                @error('jobNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_workOrderDate' class='mb-3'>
                                <label for='id_workOrderDate' class='form-label requiredField'>
                                    Work Order Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='workOrderDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_workOrderDate' autocomplete='off' maxlength='10'
                                    value='{{old("workOrderDate", date("Y-m-d", strtotime($jcr->workOrderDate))) }}'>
                                @error('workOrderDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_indentNo' class='mb-3'>
                                <label for='id_indentNo' class='form-label requiredField'>
                                    Indent Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='indentNo' placeholder='LGOOO123456'
                                    class='textinput textInput form-control' id='id_indentNo'
                                    value='{{ old("indentNo", $jcr->indentNo) }}'>
                                @error('indentNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_rigNo' class='mb-3'>
                                <label for='id_rigNo' class='form-label requiredField'>
                                    Rig Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='rigNo' placeholder='Rig Number'
                                    class='textinput textInput form-control' id='id_rigNo'
                                    value='{{ old("rigNo", $jcr->rigNo) }}'>
                                @error('rigNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_kb' class='mb-3'>
                                <label for='id_kb' class='form-label'>
                                    KB(m)
                                </label>
                                <input type='text' name='kb' placeholder='KB' class='textinput textInput form-control'
                                    id='id_kb' value='{{ old("kb", $jcr->kb) }}'>
                                @error('kb')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_gl' class='mb-3'>
                                <label for='id_gl' class='form-label'>
                                    Ground Level(m)
                                </label>
                                <input type='text' name='gl' placeholder='GL' class='textinput textInput form-control'
                                    id='id_gl' value='{{ old("gl", $jcr->gl) }}'>
                                @error('gl')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellOwner' class='mb-3'>
                                <label for='id_wellOwner' class='form-label requiredField'>
                                    Well Owner<span class='asteriskField'>*</span>
                                </label>
                                <select name='wellOwner' placeholder='Well Owner' class='select form-select'
                                    id='id_wellOwner'>
                                    <option value='' disabled {{ old('wellOwner') == '' ? 'selected' : '' }}>---
                                        Select Well Owner ---</option>
                                    @foreach ($wellOwners as $wellOwner)
                                        <option value='{{ $wellOwner }}' {{ old('wellOwner', $jcr->wellOwner) == $wellOwner ? 'selected' : '' }}>{{ $wellOwner }}</option>
                                    @endforeach
                                </select>
                                @error('wellOwner')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_mastVanNo' class='mb-3'>
                                <label for='id_mastVanNo' class='form-label'>Mast/Van Number</label>
                                <input type='text' name='mastVanNo' placeholder='Mast/Van Number'
                                    class='textinput textInput form-control' id='id_mastVanNo'
                                    value='{{ old("mastVanNo") ? old("mastVanNo") : $jcr->mastVanNo}}'>
                                @error('mastVanNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_lvNo' class='mb-3'>
                                <label for='id_lvNo' class='form-label requiredField'>
                                    Light Vehicle Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='lvNo' placeholder='Light Vehicle Number'
                                    class='textinput textInput form-control' id='id_lvNo'
                                    value='{{ old("lvNo") ? old("lvNo") : $jcr->lvNo }}'>
                                @error('lvNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellType' class='mb-3'>
                                <label for='id_wellType' class='form-label requiredField'>
                                    Well Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='wellType' placeholder='Well Type' class='select form-select' id='id_wellType'>
                                    <option value='' disabled {{ old('wellType') == '' ? 'selected' : ''}}>--- Select Well
                                        Type ---</option>
                                    @foreach ($wellTypes as $wellType)
                                        <option value='{{$wellType}}' {{ old('wellType', $jcr->wellType) == $wellType ? 'selected' : ''}}>
                                            {{$wellType}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wellType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_rigType' class='mb-3'>
                                <label for='id_rigType' class='form-label requiredField'>
                                    Rig Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='rigType' placeholder='Rig Type' class='select form-select' id='id_rigType'>
                                    <option value='' disabled {{ old('rigType') == '' ? 'selected' : '' }}>--- Select Rig Type
                                        ---</option>
                                    @foreach($rigTypes as $rigType)
                                        <option value='{{ $rigType }}' {{ old('rigType', $jcr->rigType) == $rigType ? 'selected' : '' }}>
                                            {{ $rigType }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rigType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-50'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='timeinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Time info</h2>
                            <div id='div_id_assembled' class='mb-3'>
                                <label class='form-label requiredField'>Assembled<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='assembled_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_assembled_date'
                                            autocomplete='on' maxlength='16'
                                            value="{{ old('assembled_date', date('Y-m-d', strtotime($jcr->assembled_date))) }}">
                                        @error('assembled_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='assembled_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_assembled_time' autocomplete='on'
                                            maxlength='16'
                                            value='{{ old("assembled_time", date("H:i", strtotime($jcr->assembled_time))) }}'>
                                        @error('assembled_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_depOffice' class='mb-3'>
                                <label class='form-label requiredField'>Departure Office<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depOffice_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_depOffice_date'
                                            autocomplete='off' maxlength='16'
                                            value="{{ old('depOffice_date') ? old('depOffice_date') : date('Y-m-d', strtotime($jcr->depOffice_date)) }}">
                                        @error('depOffice_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depOffice_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_depOffice_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('depOffice_time') ? old('depOffice_time') : date('H:i', strtotime($jcr->depOffice_time)) }}">
                                        @error('depOffice_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_arrivalSite' class='mb-3'>
                                <label class='form-label requiredField'>Arrival Site<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalSite_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_arrivalSite_date' autocomplete='off' maxlength='16'
                                            value="{{ old('arrivalSite_date') ? old('arrivalSite_date') : date('Y-m-d', strtotime($jcr->arrivalSite_date)) }}">
                                        @error('arrivalSite_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalSite_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_arrivalSite_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('arrivalSite_time') ? old('arrivalSite_time') : date('H:i', strtotime($jcr->arrivalSite_time)) }}">
                                        @error('arrivalSite_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_indented' class='mb-3'>
                                <label class='form-label requiredField'>Indented<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='indented_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_indented_date'
                                            autocomplete='off' maxlength='16'
                                            value="{{ old('indented_date', date('Y-m-d', strtotime($jcr->indented_date))) }}">
                                        @error('indented_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='indented_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_indented_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('indented_time', date('H:i', strtotime($jcr->indented_time))) }}">
                                        @error('indented_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellReadiness' class='mb-3'>
                                <label class='form-label requiredField'>Well Readiness<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellReadiness_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_wellReadiness_date' autocomplete='off' maxlength='16'
                                            value="{{ old('wellReadiness_date') ? old('wellReadiness_date') : date('Y-m-d', strtotime($jcr->wellReadiness_date)) }}">
                                        @error('wellReadiness_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellReadiness_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellReadiness_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('wellReadiness_time') ? old('wellReadiness_time') : date('H:i', strtotime($jcr->wellReadiness_time)) }}">
                                        @error('wellReadiness_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellTaken' class='mb-3'>
                                <label class='form-label requiredField'>Well Taken<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellTaken_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_wellTaken_date'
                                            autocomplete='off' maxlength='16'
                                            value="{{ old('wellTaken_date') ? old('wellTaken_date') : date('Y-m-d', strtotime($jcr->wellTaken_date)) }}">
                                        @error('wellTaken_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellTaken_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellTaken_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('wellTaken_time') ? old('wellTaken_time') : date('H:i', strtotime($jcr->wellTaken_time)) }}">
                                        @error('wellTaken_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_rigUP' class='mb-3'>
                                <label class='form-label requiredField'>Rig Up<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='rigUP_date' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                            class='datetimeinput form-control' id='id_rigUP_date' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('rigUP_date') ? old('rigUP_date') : date('Y-m-d', strtotime($jcr->rigUP_date)) }}">
                                        @error('rigUP_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='rigUP_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_rigUP_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('rigUP_time') ? old('rigUP_time') : date('H:i', strtotime($jcr->rigUP_time)) }}">
                                        @error('rigUP_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellHandOver' class='mb-3'>
                                <label class='form-label requiredField'>Well Hand Over<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellHandOver_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_wellHandOver_date' autocomplete='off' maxlength='16'
                                            value="{{ old('wellHandOver_date') ? old('wellHandOver_date') : date('Y-m-d', strtotime($jcr->wellHandOver_date)) }}">
                                        @error('wellHandOver_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellHandOver_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellHandOver_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('wellHandOver_time') ? old('wellHandOver_time') : date('H:i', strtotime($jcr->wellHandOver_time)) }}">
                                        @error('wellHandOver_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_depSite' class='mb-3'>
                                <label class='form-label requiredField'>Departure Site<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depSite_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_depSite_date'
                                            autocomplete='off' maxlength='16'
                                            value="{{ old('depSite_date') ? old('depSite_date') : date('Y-m-d', strtotime($jcr->depSite_date)) }}">
                                        @error('depSite_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depSite_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_depSite_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('depSite_time') ? old('depSite_time') : date('H:i', strtotime($jcr->depSite_time)) }}">
                                        @error('depSite_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_arrivalOffice' class='mb-3'>
                                <label class='form-label requiredField'>Arrival Office<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalOffice_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_arrivalOffice_date' autocomplete='off' maxlength='16'
                                            value="{{ old('arrivalOffice_date') ? old('arrivalOffice_date') : date('Y-m-d', strtotime($jcr->arrivalOffice_date)) }}">
                                        @error('arrivalOffice_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalOffice_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_arrivalOffice_time' autocomplete='off'
                                            maxlength='16'
                                            value="{{ old('arrivalOffice_time') ? old('arrivalOffice_time') : date('H:i', strtotime($jcr->arrivalOffice_time)) }}">
                                        @error('arrivalOffice_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_preparationTime' class='mb-3'>
                                <label for='id_preparationTime' class='form-label requiredField'>
                                    Preparation Time(Hrs.)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='preparationTime' placeholder='Enter Preparation time in hours.'
                                    class='textinput textInput form-control' id='id_preparationTime'
                                    value="{{ old('preparationTime') ? old('preparationTime') : $jcr->preparationTime }}">
                                @error('preparationTime')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_postProceTime' class='mb-3'>
                                <label for='id_postProceTime' class='form-label requiredField'>
                                    Post Processing Time(Hrs.)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='postProceTime' placeholder='Enter Post Processing time in hours'
                                    class='textinput textInput form-control' id='id_postProceTime'
                                    value="{{ old('postProceTime') ? old('postProceTime') : $jcr->postProceTime }}">
                                @error('postProceTime')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-25'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='wellinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Well info</h2>
                            <div id='div_id_depthDriller' class='mb-3'>
                                <label for='id_depthDriller' class='form-label'>
                                    Depth Driller (m)
                                </label>
                                <input type='text' name='depthDriller' placeholder='Depth Driller'
                                    class='textinput textInput form-control' id='id_depthDriller'
                                    value="{{ old('depthDriller') ? old('depthDriller') : $jcr->depthDriller }}">
                            </div>
                            <div id='div_id_depthLogger' class='mb-3'>
                                <label for='id_depthLogger' class='form-label'>
                                    Depth Logger (m)
                                </label>
                                <input type='text' name='depthLogger' placeholder='Depth Logger'
                                    class='textinput textInput form-control' id='id_depthLogger'
                                    value="{{ old('depthLogger') ? old('depthLogger') : $jcr->depthLogger  }}">
                            </div>
                            <div id='div_id_casingSize' class='mb-3'>
                                <label for='id_casingSize' class='form-label'>
                                    Casing Size(inch)
                                </label>
                                <input type='text' name='casingSize' placeholder='Casing Size(inch)'
                                    class='textinput textInput form-control' id='id_casingSize'
                                    value="{{ old('casingSize') ? old('casingSize') : $jcr->casingSize }}">
                            </div>
                            <div id='div_id_casingShoeDriller' class='mb-3'>
                                <label for='id_casingShoeDriller' class='form-label'>
                                    Casing Shoe Driller (m)
                                </label>
                                <input type='text' name='casingShoeDriller' placeholder='Casing Shoe Driller'
                                    class='textinput textInput form-control' id='id_casingShoeDriller'
                                    value="{{ old('casingShoeDriller') ? old('casingShoeDriller') : $jcr->casingShoeDriller }}">
                            </div>
                            <div id='div_id_casingShoeLogger' class='mb-3'>
                                <label for='id_casingShoeLogger' class='form-label'>
                                    Casing Shoe Logger (m)
                                </label>
                                <input type='text' name='casingShoeLogger' placeholder='Casing Shoe Logger'
                                    class='textinput textInput form-control' id='id_casingShoeLogger'
                                    value="{{ old('casingShoeLogger') ? old('casingShoeLogger') : $jcr->casingShoeLogger }}">
                            </div>
                            <div id='div_id_floatCollar' class='mb-3'>
                                <label for='id_floatCollar' class='form-label'>
                                    Float Collar (m)
                                </label>
                                <input type='text' name='floatCollar' placeholder='Float Collar'
                                    class='textinput textInput form-control' id='id_floatCollar'
                                    value="{{ old('floatCollar') ? old('floatCollar') : $jcr->floatCollar }}">
                            </div>
                            <div id='div_id_bitSize' class='mb-3'>
                                <label for='id_bitSize' class='form-label'>
                                    Bit Size(inch)
                                </label>
                                <input type='text' name='bitSize' placeholder='Bit Size(inch)'
                                    class='textinput textInput form-control' id='id_bitSize'
                                    value="{{ old('bitSize') ? old('bitSize') : $jcr->bitSize }}">
                            </div>
                            <div id='div_id_tubingSize' class='mb-3'>
                                <label for='id_tubingSize' class='form-label'>
                                    Tubing Size(inch)
                                </label>
                                <input type='text' name='tubingSize' placeholder='Tubing Size(inch)'
                                    class='textinput textInput form-control' id='id_tubingSize'
                                    value="{{ old('tubingSize') ? old('tubingSize') : $jcr->tubingSize }}">
                            </div>
                            <div id='div_id_t_shoe_Packer' class='mb-3'>
                                <label for='id_t_shoe_Packer' class='form-label'>
                                    T/shoe/Packer (m)
                                </label>
                                <input type='text' name='t_shoe_Packer' placeholder='T/shoe/Packer'
                                    class='textinput textInput form-control' id='id_t_shoe_Packer'
                                    value="{{ old('t_shoe_Packer') ? old('t_shoe_Packer') : $jcr->t_shoe_Packer }}">
                            </div>
                            <div id='div_id_s_nippletopexp' class='mb-3'>
                                <label for='id_s_nippletopexp' class='form-label'>
                                    S/nipple top exp. (m)
                                </label>
                                <input type='text' name='s_nippletopexp' placeholder='S/nipple top exp.'
                                    class='textinput textInput form-control' id='id_s_nippletopexp'
                                    value="{{ old('s_nippletopexp') ? old('s_nippletopexp') : $jcr->s_nippletopexp }}">
                            </div>
                            <div id='div_id_THP' class='mb-3'>
                                <label for='id_THP' class='form-label'>
                                    THP
                                </label>
                                <input type='text' name='THP' placeholder='THP' class='textinput textInput form-control'
                                    id='id_THP' value="{{ old('THP') ? old('THP') : $jcr->THP }}">
                            </div>
                            <div id='div_id_maxDevAt' class='mb-3'>
                                <label for='id_maxDevAt' class='form-label'>
                                    Max Dev at
                                </label>
                                <input type='text' name='maxDevAt' placeholder='Max Dev at'
                                    class='textinput textInput form-control' id='id_maxDevAt'
                                    value="{{ old('maxDevAt') ? old('maxDevAt') : $jcr->maxDevAt }}">
                            </div>
                            <div id='div_id_distTo_FroKms' class='mb-3'>
                                <label for='id_distTo_FroKms' class='form-label'>
                                    Dist (To &amp; Fro) Kms.
                                </label>
                                <input type='text' name='distTo_FroKms' placeholder='Dist (To &amp; Fro) Kms.'
                                    class='textinput textInput form-control' id='id_distTo_FroKms'
                                    value="{{ old('distTo_FroKms') ? old('distTo_FroKms') : $jcr->distTo_FroKms }}">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <button type='button' id='first-step' name='next' class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100 w-50'>
                <fieldset id='mudinfo'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Mud info</h2>
                        <div id='div_id_mudType' class='mb-3'>
                            <label for='id_mudType' class='form-label'>
                                MUD Type
                            </label>
                            <input type='text' name='mudType' placeholder='Mud Type' maxlength='20'
                                class='textinput textInput form-control' id='id_mudType'
                                value="{{ old('mudType', $jcr->mudType) }}">
                        </div>
                        <div id='div_id_rm' class='mb-3'>
                            <label for='id_rm' class='form-label'>
                                Rm
                            </label>
                            <input type='text' name='rm' placeholder='Rm' class='textinput textInput form-control'
                                id='id_rm' value="{{ old('rm', $jcr->rm) }}">
                        </div>
                        <div id='div_id_rmtemp' class='mb-3'>
                            <label for='id_rmtemp' class='form-label'>
                                Rm Temperature
                            </label>
                            <input type='text' name='rmtemp' placeholder='Rm Temp' class='textinput textInput form-control'
                                id='id_rmtemp' value="{{ old('rmtemp', $jcr->rmtemp) }}">
                        </div>
                        <div id='div_id_rmf' class='mb-3'>
                            <label for='id_rmf' class='form-label'>
                                Rmf
                            </label>
                            <input type='text' name='rmf' placeholder='Rmf' class='textinput textInput form-control'
                                id='id_rmf' value="{{ old('rmf', $jcr->rmf) }}">
                        </div>
                        <div id='div_id_rmftemp' class='mb-3'>
                            <label for='id_rmftemp' class='form-label'>
                                Rmf Temperature
                            </label>
                            <input type='text' name='rmftemp' placeholder='Rmf Temp'
                                class='textinput textInput form-control' id='id_rmftemp'
                                value="{{ old('rmftemp', $jcr->rmftemp) }}">
                        </div>
                        <div id='div_id_rmc' class='mb-3'>
                            <label for='id_rmc' class='form-label'>
                                Rmc
                            </label>
                            <input type='text' name='rmc' placeholder='Rmc' class='textinput textInput form-control'
                                id='id_rmc' value="{{ old('rmc', $jcr->rmc) }}">
                        </div>
                        <div id='div_id_rmctemp' class='mb-3'>
                            <label for='id_rmctemp' class='form-label'>
                                Rmc Temperature
                            </label>
                            <input type='text' name='rmctemp' placeholder='Rmc Temp'
                                class='textinput textInput form-control' id='id_rmctemp'
                                value="{{ old('rmctemp', $jcr->rmctemp) }}">
                        </div>
                        <div id='div_id_bht' class='mb-3'>
                            <label for='id_bht' class='form-label'>
                                Bottom Hole Temp.
                            </label>
                            <input type='text' name='bht' placeholder='Bottom Hole Temp'
                                class='textinput textInput form-control' id='id_bht'
                                value="{{ old('bht', $jcr->bht) }}">
                        </div>
                        <div id='div_id_bhtdepth' class='mb-3'>
                            <label for='id_bhtdepth' class='form-label'>
                                BHT Depth
                            </label>
                            <input type='text' name='bhtdepth' placeholder='BHT Depth'
                                class='textinput textInput form-control' id='id_bhtdepth'
                                value="{{ old('bhtdepth', $jcr->bhtdepth) }}">
                        </div>
                        <div id='div_id_spgr' class='mb-3'>
                            <label for='id_spgr' class='form-label'>
                                Sp. Gr. (gm/cc)
                            </label>
                            <input type='text' name='spgr' placeholder='Sp. Gr.' class='textinput textInput form-control'
                                id='id_spgr' value="{{ old('spgr', $jcr->spgr) }}">
                        </div>
                        <div id='div_id_viscosity' class='mb-3'>
                            <label for='id_viscosity' class='form-label'>
                                Viscosity
                            </label>
                            <input type='text' name='viscosity' placeholder='Viscosity'
                                class='textinput textInput form-control' id='id_viscosity'
                                value="{{ old('viscosity', $jcr->viscosity) }}">
                        </div>
                        <div id='div_id_waterloss' class='mb-3'>
                            <label for='id_waterloss' class='form-label'>
                                Water Loss
                            </label>
                            <input type='text' name='waterloss' placeholder='Water Loss'
                                class='textinput textInput form-control' id='id_waterloss'
                                value="{{ old('waterloss', $jcr->waterloss) }}">
                        </div>
                        <div id='div_id_ph' class='mb-3'>
                            <label for='id_ph' class='form-label'>
                                PH
                            </label>
                            <input type='text' name='ph' placeholder='PH' class='textinput textInput form-control'
                                id='id_ph' value="{{ old('ph', $jcr->ph) }}">
                        </div>
                        <div id='div_id_oilpercnt' class='mb-3'>
                            <label for='id_oilpercnt' class='form-label'>
                                Oil%
                            </label>
                            <input type='text' name='oilpercnt' placeholder='Oil%' class='textinput textInput form-control'
                                id='id_oilpercnt' value="{{ old('oilpercnt', $jcr->oilpercnt) }}">
                        </div>
                        <div id='div_id_kcl_barytes' class='mb-3'>
                            <label for='id_kcl_barytes' class='form-label'>
                                KCl/Barytes
                            </label>
                            <input type='text' name='kcl_barytes' placeholder='KCl/Barytes'
                                class='textinput textInput form-control' id='id_kcl_barytes'
                                value="{{ old('kcl_barytes', $jcr->kcl_barytes) }}">
                        </div>
                        <div id='div_id_salinity' class='mb-3'>
                            <label for='id_salinity' class='form-label'>
                                Salinity (GPL)
                            </label>
                            <input type='text' name='salinity' placeholder='Salinity'
                                class='textinput textInput form-control' id='id_salinity'
                                value="{{ old('salinity', $jcr->salinity) }}">
                        </div>
                        <div id='div_id_lastcirc' class='mb-3'>
                            <div class='d-flex'>
                                <div class='container'>
                                    <label for='id_lastcirc_from' class='form-label'>
                                        Last Circulation from
                                    </label>
                                    <input type='text' name='lastcirc_from' placeholder='YYYY-MM-DD HH:MM'
                                        data-mask='0000-00-00 00:00' class='datetimeinput form-control'
                                        id='id_lastcirc_from' autocomplete='off' style='z-index: 100;'
                                        value="{{ old('lastcirc_from', $jcr->lastcirc_from) }}">
                                </div>
                                <div class='container'>
                                    <label for='id_lastcirc_to' class='form-label'>
                                        Last Circulation to
                                    </label>
                                    <input type='text' name='lastcirc_to' placeholder='YYYY-MM-DD HH:MM'
                                        data-mask='0000-00-00 00:00' class='datetimeinput form-control' id='id_lastcirc_to'
                                        autocomplete='off' style='z-index: 100;' value="{{ old('lastcirc_to', $jcr->lastcirc_to) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100 w-25'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='cableinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Cable info</h2>
                            <div id='div_id_cableSize' class='mb-3'>
                                <label for='id_cableSize' class='form-label requiredField'>
                                    Cable Size (inch)<span class='asteriskField'>*</span>
                                </label>
                                <select name='cableSize' placeholder='Cable Size' class='select form-select'
                                    id='id_cableSize'>
                                    <option value='' disabled selected>--- Select Cable Size ---</option>
                                    @foreach ($cableSizes as $cableSize)
                                        <option value="{{ $cableSize }}" {{ old('cableSize') == $cableSize || $jcr->cableSize == $cableSize ? 'selected' : '' }}>
                                            {{$cableSize}}</option>
                                    @endforeach
                                </select>
                                @error('cableSize')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_insulation' class='mb-3'>
                                <label for='id_insulation' class='form-label requiredField'>
                                    Insulation<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='insulation' placeholder='Insulation'
                                    class='textinput textInput form-control' id='id_insulation'
                                    value="{{ old('insulation', $jcr->insulation) }}">
                                @error('insulation')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_shoeDate' class='mb-3'>
                                <label for='id_shoeDate' class='form-label requiredField'>
                                    Shoe Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='shoeDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_shoeDate' autocomplete='off' maxlength='10'
                                    value="{{ old('shoeDate', date('Y-m-d', strtotime($jcr->shoeDate))) }}">
                                @error('shoeDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_weakPoint' class='mb-3'>
                                <label for='id_weakPoint' class='form-label requiredField'>
                                    Weak Point<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='weakPoint' placeholder='[e.g. (OL+IL)]'
                                    class='textinput textInput form-control' id='id_weakPoint'
                                    value="{{ old('weakPoint', $jcr->weakPoint) }}">
                                @error('weakPoint')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_cableHeadSize' class='mb-3'>
                                <label for='id_cableHeadSize' class='form-label requiredField'>
                                    Cable Head Size (inch)<span class='asteriskField'>*</span>
                                </label>
                                <select name='cableHeadSize' placeholder='Cable Head Size'
                                    class='textinput textInput form-control' id='id_cableHeadSize'>
                                    <option value='' disabled selected>--- Select Cable Head Size ---</option>
                                    @foreach($cableHeadSizes as $cableHeadSize)
                                        <option value='{{ $cableHeadSize }}' {{ old("cableHeadSize", $jcr->cableHeadSize) == $cableHeadSize ? 'selected' : '' }}>{{ $cableHeadSize }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cableHeadSize')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_cableLength' class='mb-3'>
                                <label for='id_cableLength' class='form-label requiredField'>
                                    Cable Length(m)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='cableLength' placeholder='Cable Length(m)'
                                    class='textinput textInput form-control' id='id_cableLength'
                                    value="{{ old('cableLength', $jcr->cableLength) }}">
                                @error('cableLength')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_initialLength' class='mb-3'>
                                <label for='id_initialLength' class='form-label requiredField'>
                                    Initial Cable Length(m)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='initialLength' placeholder='Initial Cable Length(m)'
                                    class='textinput textInput form-control' id='id_initialLength'
                                    value="{{ old('initialLength', $jcr->initialLength) }}">
                                @error('initialLength')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class='card-body px-0'>
                    <fieldset id='surfaceequipment'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Surface Equipment</h2>
                            <div id='div_id_surfaceEquipment' class='mb-3'>
                                <label for='id_surfaceEquipment' class='form-label requiredField'>Surface Equipment<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='surfaceEquipment' placeholder='Surface Equipment'
                                    class='textinput textInput form-control' id='id_surfaceEquipment'
                                    value="{{ old('surfaceEquipment', $jcr->surfaceEquipment) }}">
                                @error('surfaceEquipment')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_automobile' class='mb-3'>
                                <label for='id_automobile' class='form-label requiredField'>Automobile<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='automobile' placeholder='Automobile'
                                    class='textinput textInput form-control' id='id_automobile'
                                    value="{{ old('automobile', $jcr->automobile) }}">
                                @error('automobile')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellCondition' class='mb-3'>
                                <label for='id_wellCondition' class='form-label requiredField'>Well Condition<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='wellCondition' placeholder='Well Condition'
                                    class='textinput textInput form-control' id='id_wellCondition'
                                    value="{{ old('wellCondition', $jcr->wellCondition) }}">
                                @error('wellCondition')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_timeLoss' class='mb-3'>
                                <label for='id_timeLoss' class='form-label requiredField'>Time Loss(Hr)<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='timeLoss' placeholder='Time Loss(Hr)'
                                    class='textinput textInput form-control' id='id_timeLoss'
                                    value="{{ old('timeLoss', $jcr->timeLoss) }}">
                                @error('timeLoss')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-25'>
                <fieldset id='personnel'>
                    <div class='form-card step' id='personnel-form'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Personnel</h2>
                        <div id='wrapper'>
                            @php
                                $personnelData = old('personnel') ?: $jcr->users->map(function ($user) {
                                    return ['user_id' => $user->id];
                                })->toArray();
                            @endphp
                            @foreach ($personnelData as $index => $personnel)
                                @php
                                    $selectedUserId = $personnel['user_id'];
                                @endphp
                                <div class='mb-3 element' id='userinlinemodel_{{ $index + 1 }}'>
                                    <label for='select_{{ $index + 1 }}' class='form-label requiredField'
                                        id='personnellabel_{{ $index + 1 }}'>Personnel<span class='asteriskField'
                                            id='asterisk_{{ $index + 1 }}'>*</span></label>
                                    <select data-live-search='true' class='select form-select mb-3 personnelselect'
                                        id='select_{{ $index + 1 }}' name='personnel[{{ $index }}][user_id]'>
                                        <option value=''>--- Select Personnel ---</option>
                                        @foreach ($users->sortBy('seniority') as $user)
                                            <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class='btn btn-danger btn-sm remove my-3' id='remove_personnel_{{ $index + 1 }}' type='button'><i class='fa fa-minus-circle'></i></button>
                                    @error('personnel')
                                        <small class='error'>{{ $message }}</small>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        <button class='btn btn-success btn-lg' id='add_more_personnel' type='button'><i
                                class='fa fa-plus-circle'></i></button>
                        <div class='contingents'>
                            <div id='div_id_contingents' class='mb-3'>
                                <label for='id_contingents' class='form-label'>
                                    Contingents<span class='asteriskField'>*</span>
                                </label>
                                <textarea name='contingents' cols='40' rows='10' type='text'
                                    placeholder='Contingents involved in this job' class='textarea form-control'
                                    id='id_contingents'>{{ old('contingents', $jcr->contingents) }}</textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
        <button type='button' name='next' id="second-step" class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100'>
                <fieldset id='logsrecorded'>
                    <div class='form-card step container'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Logs Recorded</h2>
                        <div class="logs-wrapper">
                            @if (old('logrecorded'))
                                @foreach (old('logrecorded') as $index => $old_logrecorded)
                                    <div class='log-form' id='id-log-form_{{ $index + 1 }}'>
                                        @if ($index > 0)
                                            <hr class='my-3' style='color:#000000; border-top:5px solid; opacity:0.5;'>
                                        @endif
                                        <h2 class='my-2'>Run - {{ $index + 1 }}</h2>
                                        <input type="number" name="logrecorded[{{ $index }}][id]" hidden
                                            value="{{ $old_logrecorded['id'] }}">
                                        <div id='div_logmodel_set-{{ $index + 1 }}-runNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-runNo' class='form-label requiredField'>Run
                                                No.<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][runNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-runNo'
                                                value="{{ $old_logrecorded['runNo'] }}">
                                            @error('logrecorded.*.runNo')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logRecorded' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logRecorded'
                                                class='form-label requiredField'>
                                                Type of Logs Recorded<span class='asteriskField'>*</span> </label>
                                            <select name='logrecorded[{{ $index }}][logRecorded]'
                                                class='textinput textInput form-control mb-3 logsRecorded'
                                                id='id_logmodel_set-{{ $index + 1 }}-logRecorded' required>
                                                <option value="" disabled>--- Select Recorded Log ---</option>
                                                <optgroup label="Cased Hole logs" id="CH">
                                                <optgroup label="Explosive logs" id="explosive-logs">
                                                    <option value="Perforation(CCL)" {{ $old_logrecorded['logRecorded'] == 'Perforation(CCL)' ? 'selected' : '' }}>Perforation(CCL)</option>
                                                    <option value="TTP(CCL)" {{ $old_logrecorded['logRecorded'] == 'TTP(CCL)' ? 'selected' : '' }}>TTP(CCL)</option>
                                                    <option value="Bridge Plug(CCL)" {{ $old_logrecorded['logRecorded'] == 'Bridge Plug(CCL)' ? 'selected' : '' }}>Bridge Plug(CCL)</option>
                                                    <option value="Tubing Puncture" {{ $old_logrecorded['logRecorded'] == 'Tubing Puncture' ? 'selected' : '' }}>Tubing Puncture</option>
                                                    <option value="Casing Cutter" {{ $old_logrecorded['logRecorded'] == 'Casing Cutter' ? 'selected' : '' }}>Casing Cutter</option>
                                                    <option value="Tubing Cutter" {{ $old_logrecorded['logRecorded'] == 'Tubing Cutter' ? 'selected' : '' }}>Tubing Cutter</option>
                                                    <option value="Packer Setting" {{ $old_logrecorded['logRecorded'] == 'Packer Setting' ? 'selected' : '' }}>Packer Setting</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="non-explosive-logs">
                                                    <option value="Junk Basket(CCL)" {{ $old_logrecorded['logRecorded'] == 'Junk Basket(CCL)' ? 'selected' : '' }}>Junk Basket(CCL)</option>
                                                    <option value="GR-CCL" {{ $old_logrecorded['logRecorded'] == 'GR-CCL' ? 'selected' : '' }}>GR-CCL</option>
                                                    <option value="GR-TCL" {{ $old_logrecorded['logRecorded'] == 'GR-TCL' ? 'selected' : '' }}>GR-TCL</option>
                                                    <option value="SBT-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'SBT-GR-CCL' ? 'selected' : '' }}>SBT-GR-CCL</option>
                                                    <option value="RBT-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'RBT-GR-CCL' ? 'selected' : '' }}>RBT-GR-CCL</option>
                                                    <option value="ULTEX-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'ULTEX-GR-CCL' ? 'selected' : '' }}>ULTEX-GR-CCL</option>
                                                </optgroup>
                                                </optgroup>
                                                <optgroup label="Production Logs" id="PL">
                                                    <option value="Production Log" {{ $old_logrecorded['logRecorded'] == 'Production Log' ? 'selected' : '' }}>Production Log</option>
                                                    <option value="Temperature Log" {{ $old_logrecorded['logRecorded'] == 'Temperature Log' ? 'selected' : '' }}>Temperature Log</option>
                                                </optgroup>
                                                <optgroup label="Open Hole Logs" id="OHL">
                                                <optgroup label="Explosive logs" id="oh-non-explosive-logs"></optgroup>
                                                <option value="HDIL-ORIT-SP-GR" {{ $old_logrecorded['logRecorded'] == 'HDIL-ORIT-SP-GR' ? 'selected' : '' }}>HDIL-ORIT-SP-GR</option>
                                                <option value="RTEX-ORIT-SP-GR" {{ $old_logrecorded['logRecorded'] == 'RTEX-ORIT-SP-GR' ? 'selected' : '' }}>RTEX-ORIT-SP-GR</option>
                                                <option value="ZDEN-CN-GR" {{ $old_logrecorded['logRecorded'] == 'ZDEN-CN-GR' ? 'selected' : '' }}>ZDEN-CN-GR</option>
                                                <option value="STAR-ORIT-GR" {{ $old_logrecorded['logRecorded'] == 'STAR-ORIT-GR' ? 'selected' : '' }}>STAR-ORIT-GR</option>
                                                <option value="XMAC-ORIT-GR" {{ $old_logrecorded['logRecorded'] == 'XMAC-ORIT-GR' ? 'selected' : '' }}>XMAC-ORIT-GR</option>
                                                <optgroup label="Explosive logs" id="oh-explosive-logs"></optgroup>
                                                </optgroup>
                                                <optgroup label="Explosive logs" id="other-explosive-logs">
                                                    <option value="Other Explosive Logs">Other Explosive Logs {{ $old_logrecorded['logRecorded'] == 'Other Explosive Logs' ? 'selected' : '' }}</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="other-non-explosive-logs">
                                                    <option value="Other Non-Explosive Logs" {{ $old_logrecorded['logRecorded'] == 'Other Non-Explosive Logs' ? 'selected' : '' }}>Other Non-Explosive Logs</option>
                                                </optgroup>
                                            </select>
                                            @error('logrecorded.*.logRecorded')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                class='form-label requiredField'>
                                                Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number'
                                                name='logrecorded[{{ $index }}][bottomDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                value="{{ $old_logrecorded['bottomDepth'] }}">
                                            @error('logrecorded.*.bottomDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topDepth' class='form-label requiredField'>
                                                Top Depth(m)<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][topDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topDepth'
                                                value="{{ $old_logrecorded['topDepth'] }}">
                                            @error('logrecorded.*.topDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-toolNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-toolNo' class='form-label'>Tool No.</label>
                                            <input type='text' name='logrecorded[{{ $index }}][toolNo]' maxlength='255'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-toolNo'
                                                value="{{ $old_logrecorded['toolNo'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logQuality' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                class='form-label requiredField'>
                                                Log Quality<span class='asteriskField'>*</span> </label>
                                            <input type='text' name='logrecorded[{{ $index }}][logQuality]' maxlength='20'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                value="{{ $old_logrecorded['logQuality'] }}">
                                            @error('logrecorded.*.logQuality')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomShotDepth' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth' class='form-label'>
                                                Bottom Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][bottomShotDepth]'
                                                step='any' class='numberinput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth'
                                                value="{{ $old_logrecorded['bottomShotDepth'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topShotDepth' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topShotDepth' class='form-label'>
                                                Top Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][topShotDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topShotDepth'
                                                value="{{ $old_logrecorded['topShotDepth'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-charge' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-charge' class='form-label'>Charge
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][charge]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-charge'
                                                value="{{ $old_logrecorded['charge'] }}">
                                                <option value='' {{ $old_logrecorded['charge'] == '' ? 'selected' : '' }}>--- Select
                                                    Charge type ---</option>
                                                @foreach ($explosivelists as $explosives)
                                                    <option value='{{ $explosives }}' {{ $old_logrecorded['charge'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['charge'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-chargeNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-chargeNo' class='form-label'>
                                                Charge Qty.(Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][chargeNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-chargeNo'
                                                value="{{ $old_logrecorded['chargeNo'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChord' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChord' class='form-label'>Prima Chord
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][primaChord]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-primaChord'>
                                                <option value='' {{ $old_logrecorded['primaChord'] == '' ? 'selected' : '' }}>---
                                                    Select Prima Chord type ---</option>
                                                @foreach ($primachordlists as $primachord)
                                                    <option value='{{ $primachord }}' {{ $old_logrecorded['primaChord'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['primaChord'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChordQty' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChordQty' class='form-label'>
                                                P/C Length (m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][primaChordQty]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-primaChordQty'
                                                value='{{ $old_logrecorded['primaChordQty'] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuse' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuse' class='form-label'>Fuse Type</label>
                                            <select name='logrecorded[{{ $index }}][fuse]' maxlength='20'
                                                class='textinput textInput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuse'>
                                                <option value='' {{ $old_logrecorded['fuse'] == '' ? 'selected' : '' }}>--- Select
                                                    Detonator type ---</option>
                                                @foreach ($detonatorlists as $detonator)
                                                    <option value='{{ $detonator }}' {{ $old_logrecorded['fuse'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['fuse'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuseNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuseNo' class='form-label'>
                                                Fuse Qty. (Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][fuseNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuseNo'
                                                value="{{ $old_logrecorded['fuseNo'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fMf' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fMf' class='form-label'>F/MF</label>
                                            <select name='logrecorded[{{ $index }}][fMf]' class='select form-select'
                                                id='id_logmodel_set-{{ $index + 1 }}-fMf'>
                                                <option value='' {{ $old_logrecorded['fMf'] == '' ? 'selected' : ''}}>---------
                                                </option>
                                                <option value='F' {{ $old_logrecorded['fMf'] == 'F' ? 'selected' : ''}}>F</option>
                                                <option value='MF' {{ $old_logrecorded['fMf'] == 'MF' ? 'selected' : ''}}>MF</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif(!$jcr->logs->isEmpty())
                                @foreach($jcr['logs'] as $index => $logsrecorded)
                                    @php
                                        $predefinedOptions = ['Perforation(CCL)', 'TTP(CCL)', 'Bridge Plug(CCL)', 'Tubing Puncture', 'Casing Cutter', 'Tubing Cutter', 'Junk Basket(CCL)', 'GR-CCL', 'GR-TCL', 'SBT-GR-CCL', 'RBT-GR-CCL', 'ULTEX-GR-CCL', 'Production Log', 'Temperature Log', 'HDIL-ORIT-SP-GR', 'RTEX-ORIT-SP-GR', 'ZDEN-CN-GR', 'STAR-ORIT-GR', 'XMAC-ORIT-GR', 'Other'];
                                        $isCustomValue = !in_array($logsrecorded['logRecorded'], $predefinedOptions) && $logsrecorded['logRecorded'] !== '';
                                        $isOtherSelected = $logsrecorded['logRecorded'] === 'Other' || $isCustomValue;
                                    @endphp
                                    <div class='log-form' id='id-log-form_{{$index + 1}}'>
                                        @if ($index > 0)
                                            <hr class='my-3' style='color:#000000; border-top:5px solid; opacity:0.5;'>
                                        @endif
                                        <h2 class='my-2'>Run - {{ $index + 1 }}</h2>
                                        <input type="number" name="logrecorded[{{ $index }}][id]" hidden
                                            value="{{ $logsrecorded['id'] }}">
                                        <div id='div_logmodel_set-{{$index + 1}}-runNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{$index + 1}}-runNo' class='form-label requiredField'>Run
                                                No.<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][runNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{$index + 1}}-runNo'
                                                value="{{ $logsrecorded->runNo }}">
                                            @error('logrecorded.*.runNo')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logRecorded' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logRecorded'
                                                class='form-label requiredField'>
                                                Type of Logs Recorded<span class='asteriskField'>*</span> </label>
                                            <select name='logrecorded[{{ $index }}][logRecorded]'
                                                class='textinput textInput form-control mb-3 logsRecorded'
                                                id='id_logmodel_set-{{ $index + 1 }}-logRecorded' required>
                                                <option value="" disabled>--- Select Recorded Log ---</option>
                                                <optgroup label="Cased Hole logs" id="CH">
                                                <optgroup label="Explosive logs" id="explosive-logs">
                                                    <option value="Perforation(CCL)" {{ $logsrecorded['logRecorded'] == 'Perforation(CCL)' ? 'selected' : '' }}>Perforation(CCL)</option>
                                                    <option value="TTP(CCL)" {{ $logsrecorded['logRecorded'] == 'TTP(CCL)' ? 'selected' : '' }}>TTP(CCL)</option>
                                                    <option value="Bridge Plug(CCL)" {{ $logsrecorded['logRecorded'] == 'Bridge Plug(CCL)' ? 'selected' : '' }}>Bridge Plug(CCL)</option>
                                                    <option value="Tubing Puncture" {{ $logsrecorded['logRecorded'] == 'Tubing Puncture' ? 'selected' : '' }}>Tubing Puncture</option>
                                                    <option value="Casing Cutter" {{ $logsrecorded['logRecorded'] == 'Casing Cutter' ? 'selected' : '' }}>Casing Cutter</option>
                                                    <option value="Tubing Cutter" {{ $logsrecorded['logRecorded'] == 'Tubing Cutter' ? 'selected' : '' }}>Tubing Cutter</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="non-explosive-logs">
                                                    <option value="Junk Basket(CCL)" {{ $logsrecorded['logRecorded'] == 'Junk Basket(CCL)' ? 'selected' : '' }}>Junk Basket(CCL)</option>
                                                    <option value="GR-CCL" {{ $logsrecorded['logRecorded'] == 'GR-CCL' ? 'selected' : '' }}>GR-CCL</option>
                                                    <option value="GR-TCL" {{ $logsrecorded['logRecorded'] == 'GR-TCL' ? 'selected' : '' }}>GR-TCL</option>
                                                    <option value="SBT-GR-CCL" {{ $logsrecorded['logRecorded'] == 'SBT-GR-CCL' ? 'selected' : '' }}>SBT-GR-CCL</option>
                                                    <option value="RBT-GR-CCL" {{ $logsrecorded['logRecorded'] == 'RBT-GR-CCL' ? 'selected' : '' }}>RBT-GR-CCL</option>
                                                    <option value="ULTEX-GR-CCL" {{ $logsrecorded['logRecorded'] == 'ULTEX-GR-CCL' ? 'selected' : '' }}>ULTEX-GR-CCL</option>
                                                </optgroup>
                                                </optgroup>
                                                <optgroup label="Production Logs" id="PL">
                                                    <option value="Production Log" {{ $logsrecorded['logRecorded'] == 'Production Log' ? 'selected' : '' }}>Production Log</option>
                                                    <option value="Temperature Log" {{ $logsrecorded['logRecorded'] == 'Temperature Log' ? 'selected' : '' }}>Temperature Log</option>
                                                </optgroup>
                                                <optgroup label="Open Hole Logs" id="OHL">
                                                    <option value="HDIL-ORIT-SP-GR" {{ $logsrecorded['logRecorded'] == 'HDIL-ORIT-SP-GR' ? 'selected' : '' }}>HDIL-ORIT-SP-GR</option>
                                                    <option value="RTEX-ORIT-SP-GR" {{ $logsrecorded['logRecorded'] == 'RTEX-ORIT-SP-GR' ? 'selected' : '' }}>RTEX-ORIT-SP-GR</option>
                                                    <option value="ZDEN-CN-GR" {{ $logsrecorded['logRecorded'] == 'ZDEN-CN-GR' ? 'selected' : '' }}>ZDEN-CN-GR</option>
                                                    <option value="STAR-ORIT-GR" {{ $logsrecorded['logRecorded'] == 'STAR-ORIT-GR' ? 'selected' : '' }}>STAR-ORIT-GR</option>
                                                    <option value="XMAC-ORIT-GR" {{ $logsrecorded['logRecorded'] == 'XMAC-ORIT-GR' ? 'selected' : '' }}>XMAC-ORIT-GR</option>
                                                </optgroup>
                                                </optgroup>
                                                <optgroup label="Explosive logs" id="other-explosive-logs">
                                                    <option value="Other Explosive Logs" {{ $logsrecorded['logRecorded'] == 'Other Explosive Logs' ? 'selected' : '' }}>Other Explosive Logs</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="other-non-explosive-logs">
                                                    <option value="Other Non-Explosive Logs" {{ $logsrecorded['logRecorded'] == 'Other Non-Explosive Logs' ? 'selected' : '' }}>Other Non-Explosive Logs</option>
                                                </optgroup>
                                            </select>
                                            @if($isOtherSelected)
                                                <div id='div_logmodel_set-{{ $index + 1 }}-otherLogDescription' class='mb-3'>
                                                    <label for='id_logmodel_set-{{ $index + 1 }}-otherLogDescription' class='form-label requiredField'>
                                                        Please specify<span class='asteriskField'>*</span>
                                                    </label>
                                                    <input type='text' name='logrecorded[{{ $index }}][otherLogDescription]' maxlength='255'
                                                    class='textinput textInput form-control' id='id_logmodel_set-{{ $index + 1 }}-otherLogDescription'
                                                    value='{{ $isCustomValue ? $logsrecorded->otherLogDescription : "" }}'>
                                                </div>
                                            @endif
                                            @error('logrecorded.*.logRecorded')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                class='form-label requiredField'>
                                                Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number'
                                                name='logrecorded[{{ $index }}][bottomDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                value='{{ $logsrecorded->bottomDepth }}'>
                                            @error('logrecorded.*.bottomDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topDepth' class='form-label requiredField'>
                                                Top Depth(m)<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][topDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topDepth'
                                                value='{{ $logsrecorded->topDepth }}'>
                                            @error('logrecorded.*.topDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-toolNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-toolNo' class='form-label'>Tool No.</label>
                                            <input type='text' name='logrecorded[{{ $index }}][toolNo]' maxlength='255'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-toolNo' value='{{ $logsrecorded->toolNo }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logQuality' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                class='form-label requiredField'>
                                                Log Quality<span class='asteriskField'>*</span> </label>
                                            <input type='text' name='logrecorded[{{ $index }}][logQuality]' maxlength='20'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                value='{{ $logsrecorded->logQuality }}'>
                                            @error('logrecorded.*.logQuality')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomShotDepth' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth' class='form-label'>
                                                Bottom Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][bottomShotDepth]'
                                                step='any' class='numberinput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth'
                                                value='{{ $logsrecorded->bottomShotDepth }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topShotDepth' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topShotDepth' class='form-label'>
                                                Top Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][topShotDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topShotDepth'
                                                value='{{ $logsrecorded->topShotDepth }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-charge' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-charge' class='form-label'>Charge
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][charge]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-charge'>
                                                <option value='' {{ $logsrecorded['charge'] == '' ? 'selected' : '' }}>--- Select
                                                    Charge type ---</option>
                                                @foreach ($explosivelists as $explosives)
                                                    <option value='{{ $explosives }}' {{ $logsrecorded['charge'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                @endforeach
                                                <option value='NA' {{ $logsrecorded['charge'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-chargeNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-chargeNo' class='form-label'>
                                                Charge Qty.(Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][chargeNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-chargeNo'
                                                value='{{ $logsrecorded['chargeNo'] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChord' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChord' class='form-label'>Prima Chord
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][primaChord]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-primaChord'>
                                                <option value='' {{ $logsrecorded['primaChord'] == '' ? 'selected' : '' }}>--- Select Prima Chord type ---</option>
                                                @foreach ($primachordlists as $primachord)
                                                    <option value='{{ $primachord }}' {{ $logsrecorded['primaChord'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                @endforeach
                                                <option value='NA' {{ $logsrecorded['primaChord'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChordQty' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChordQty' class='form-label'>
                                                P/C Length (m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][primaChordQty]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-primaChordQty'
                                                value="{{ $logsrecorded['primaChordQty'] }}">
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuse' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuse' class='form-label'>Fuse Type</label>
                                            <select name='logrecorded[{{ $index }}][fuse]' maxlength='20'
                                                class='textinput textInput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuse'>
                                                <option value='' {{ $logsrecorded['fuse'] == '' ? 'selected' : '' }}>--- Select Detonator type ---</option>
                                                @foreach ($detonatorlists as $detonator)
                                                    <option value='{{ $detonator }}' {{ $logsrecorded['fuse'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                @endforeach
                                                <option value='NA' {{ $logsrecorded['fuse'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuseNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuseNo' class='form-label'>
                                                Fuse Qty. (Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][fuseNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuseNo'
                                                value='{{ $logsrecorded->fuseNo }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fMf' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fMf' class='form-label'>F/MF</label>
                                            <select name='logrecorded[{{ $index }}][fMf]' class='select form-select'
                                                id='id_logmodel_set-{{ $index + 1 }}-fMf'>
                                                <option value='' {{ $logsrecorded->fMf == '' ? 'selected' : ''}}>---------</option>
                                                <option value='F' {{ $logsrecorded->fMf == 'F' ? 'selected' : ''}}>F</option>
                                                <option value='MF' {{ $logsrecorded->fMf == 'MF' ? 'selected' : ''}}>MF</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class='log-form' id='id-log-form_1'>
                                    <div id='div_logmodel_set-1-runNo' class='mb-3'>
                                        <label for='id_logmodel_set-1-runNo' class='form-label requiredField'>Run No.<span
                                                class='asteriskField'>*</span> </label>
                                        <input type='number' name='logrecorded[0][runNo]' class='numberinput form-control'
                                            id='id_logmodel_set-1-runNo' value="">
                                        @error('logrecorded.*.runNo')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-logRecorded' class='mb-3'>
                                        <label for='id_logmodel_set-1-logRecorded' class='form-label requiredField'>
                                            Type of Logs Recorded<span class='asteriskField'>*</span> </label>
                                        <input type='text' name='logrecorded[0][logRecorded]' maxlength='255'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-logRecorded'
                                            value=''>
                                        @error('logrecorded.*.logRecorded')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-bottomDepth' class='mb-3'>
                                        <label for='id_logmodel_set-1-bottomDepth' class='form-label requiredField'>
                                            Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number'
                                            name='logrecorded[0][bottomDepth]' step='any' class='numberinput form-control'
                                            id='id_logmodel_set-1-bottomDepth' value=''>
                                        @error('logrecorded.*.bottomDepth')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-topDepth' class='mb-3'>
                                        <label for='id_logmodel_set-1-topDepth' class='form-label requiredField'>
                                            Top Depth(m)<span class='asteriskField'>*</span> </label>
                                        <input type='number' name='logrecorded[0][topDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-topDepth' value=''>
                                        @error('logrecorded.*.topDepth')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-toolNo' class='mb-3'>
                                        <label for='id_logmodel_set-1-toolNo' class='form-label'>Tool No.</label>
                                        <input type='text' name='logrecorded[0][toolNo]' maxlength='255'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-toolNo' value=''>
                                    </div>
                                    <div id='div_logmodel_set-1-logQuality' class='mb-3'>
                                        <label for='id_logmodel_set-1-logQuality' class='form-label requiredField'>
                                            Log Quality<span class='asteriskField'>*</span> </label>
                                        <input type='text' name='logrecorded[0][logQuality]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-logQuality' value=''>
                                        @error('logrecorded.*.logQuality')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-bottomShotDepth' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-bottomShotDepth' class='form-label'>
                                            Bottom Shot Depth(m)
                                        </label> <input type='number' name='logrecorded[0][bottomShotDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-bottomShotDepth' value=''>
                                    </div>
                                    <div id='div_logmodel_set-1-topShotDepth' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-topShotDepth' class='form-label'>
                                            Top Shot Depth(m)
                                        </label> <input type='number' name='logrecorded[0][topShotDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-topShotDepth' value=''>
                                    </div>
                                    <div id='div_logmodel_set-1-charge' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-charge' class='form-label'>Charge Type</label>
                                        <select name='logrecorded[0][charge]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-charge'>
                                            <option value=''>--- Select Charge type ---</option>
                                            @foreach ($explosivelists as $explosives)
                                                <option value='{{ $explosives }}'>{{ $explosives }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-chargeNo' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-chargeNo' class='form-label'>
                                            Charge Qty.(Nos.)
                                        </label> <input type='number' name='logrecorded[0][chargeNo]'
                                            class='numberinput form-control' id='id_logmodel_set-1-chargeNo'
                                            value="{{ old('chargeNo') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-primaChord' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-primaChord' class='form-label'>Prima Chord Type</label>
                                        <select name='logrecorded[0][primaChord]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-primaChord'>
                                            <option value=''>--- Select Prima Chord type ---</option>
                                            @foreach ($primachordlists as $primachord)
                                                <option value='{{ $primachord }}'>{{ $primachord }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-primaChordQty' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-primaChordQty' class='form-label'>
                                            P/C Length (m)
                                        </label> <input type='number' name='logrecorded[0][primaChordQty]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-primaChordQty'
                                            value="{{ old('primaChordQty') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-fuse' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fuse' class='form-label'>Fuse Type</label>
                                        <select name='logrecorded[0][fuse]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-fuse'>
                                            <option value=''>--- Select Detonator type ---</option>
                                            @foreach ($detonatorlists as $detonator)
                                                <option value='{{ $detonator }}'>{{ $detonator }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-fuseNo' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fuseNo' class='form-label'>
                                            Fuse Qty. (Nos.)
                                        </label> <input type='number' name='logrecorded[0][fuseNo]'
                                            class='numberinput form-control' id='id_logmodel_set-1-fuseNo' value=''>
                                    </div>
                                    <div id='div_logmodel_set-1-fMf' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fMf' class='form-label'>F/MF</label>
                                        <select name='logrecorded[0][fMf]' class='select form-select'
                                            id='id_logmodel_set-1-fMf'>
                                            <option value='' selected>---------</option>
                                            <option value='F'>F</option>
                                            <option value='MF'>MF</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button class='btn btn-success btn-lg' id='add_more_logs' type='button'><i
                                class='fa fa-plus-circle'></i></button>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100'>
                <div class='card-body px-0 pt-0' id="div-swc">
                    <fieldset id='swc'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center' id='swctitle'>Side Wall Core</h2>
                            <div id='div_id_attempted' class='mb-3'>
                                <label for='id_attempted' class='form-label'>
                                    Attempted
                                </label>
                                <input type='text' name='attempted' placeholder='Attempted'
                                    class='textinput textInput form-control' id='id_attempted'
                                    value='{{ $jcr->attempted }}'>
                            </div>
                            <div id='div_id_recovered' class='mb-3'>
                                <label for='id_recovered' class='form-label'>
                                    Recovered
                                </label>
                                <input type='text' name='recovered' placeholder='Recovered'
                                    class='textinput textInput form-control' id='id_recovered'
                                    value='{{ $jcr->recovered }}'>
                            </div>
                            <div id='div_id_missFire' class='mb-3'>
                                <label for='id_missFire' class='form-label'>
                                    Miss Fire
                                </label>
                                <input type='text' name='missFire' placeholder='Miss Fire'
                                    class='textinput textInput form-control' id='id_missFire' value='{{ $jcr->missFire }}'>
                            </div>
                            <div id='div_id_barrelLost' class='mb-3'>
                                <label for='id_barrelLost' class='form-label'>
                                    Barrel Lost
                                </label>
                                <input type='text' name='barrelLost' placeholder='Barrel Lost'
                                    class='textinput textInput form-control' id='id_barrelLost'
                                    value='{{ $jcr->barrelLost }}'>
                            </div>
                            <div id='div_id_emptyBarrel' class='mb-3'>
                                <label for='id_emptyBarrel' class='form-label'>
                                    Empty Barrel
                                </label>
                                <input type='text' name='emptyBarrel' placeholder='Empty Barrel'
                                    class='textinput textInput form-control' id='id_emptyBarrel'
                                    value='{{ $jcr->emptyBarrel }}'>
                            </div>
                            <div id='div_id_chargeUsed' class='mb-3'>
                                <label for='id_chargeUsed' class='form-label'>
                                    Charge Used
                                </label>
                                <input type='text' name='chargeUsed' placeholder='Charge Used'
                                    class='textinput textInput form-control' id='id_chargeUsed'
                                    value='{{ $jcr->chargeUsed }}'>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class='card-body px-0' id="div-explosive">
                    <fieldset id='explosiveinfo'>
                        <div class='form-card step container'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Explosive Details</h2>
                            <div class="explosive-wrapper">
                                @if (old('explosive'))
                                    @foreach (old('explosive') as $index => $old_explosive)
                                        <div class='explosive-form' id='id-explosive-form_{{ $index + 1 }}'>
                                            <h2 class='my-3'>Explosive - {{ $index + 1 }}</h2>
                                            @if (isset($old_explosive['id']))
                                                <input type="number" name="explosive[{{ $index }}][id]" hidden
                                                    value="{{ $old_explosive['id'] }}">
                                            @endif
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-explosives' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-explosives'
                                                    class='form-label'>Charges</label>
                                                <select name='explosive[{{ $index }}][explosive]'
                                                    placeholder='--- Select Charge type ---' class='select form-select'
                                                    id='id_explosive_set-{{ $index + 1 }}-explosives'>
                                                    <option value='' {{ $old_explosive['explosive'] == '' ? 'selected' : '' }}>---
                                                        Select Charge type ---</option>
                                                    @foreach ($explosivelists as $explosives)
                                                        <option value='{{ $explosives }}' {{ $old_explosive['explosive'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                    @endforeach
                                                    @foreach ($primachordlists as $primachord)
                                                        <option value='{{ $primachord }}' {{ $old_explosive['explosive'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                    @endforeach
                                                    @foreach ($detonatorlists as $detonator)
                                                        <option value='{{ $detonator }}' {{ $old_explosive['explosive'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                    @endforeach
                                                    <option value='NA' {{ $old_explosive['explosive'] == 'NA' ? 'selected' : '' }}>NA</option>
                                                </select>
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-issued' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-issued' class='form-label'> Charge
                                                    Issued</label>
                                                <input type='text' name='explosive[{{ $index }}][issued]' placeholder='Issued'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-issued'
                                                    value="{{ $old_explosive['issued'] }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-used' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-used' class='form-label'>Charge
                                                    Used</label>
                                                <input type='text' name='explosive[{{ $index }}][used]' placeholder='Used'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-used'
                                                    value="{{ $old_explosive['used'] }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-returned' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-returned' class='form-label'>Charge
                                                    Returned</label>
                                                <input type='text' name='explosive[{{ $index }}][returned]' placeholder='Returned'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-returned'
                                                    value="{{ $old_explosive['returned'] }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(!$jcr->explosives->isEmpty())
                                    @foreach ($jcr->explosives as $index => $jcrExplosive)
                                        <div class='explosive-form' id='id-explosive-form_{{ $index + 1 }}'>
                                            <h2 class='my-3'>Explosive - {{ $index + 1 }}</h2>
                                            <input type="number" name="explosive[{{ $index }}][id]" hidden
                                                value="{{ $jcrExplosive['id'] }}">
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-explosives' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-explosives'
                                                    class='form-label'>Charges</label>
                                                <select name='explosive[{{ $index }}][explosive]'
                                                    placeholder='--- Select Charge type ---' class='select form-select'
                                                    id='id_explosive_set-{{ $index + 1 }}-explosives'>
                                                    <option value='' {{ $jcrExplosive['explosive'] == '' ? 'selected' : '' }}>---
                                                        Select Charge type ---</option>
                                                    @foreach ($explosivelists as $explosives)
                                                        <option value='{{ $explosives }}' {{ $jcrExplosive['explosive'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                    @endforeach
                                                    @foreach ($primachordlists as $primachord)
                                                        <option value='{{ $primachord }}' {{ $jcrExplosive['explosive'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                    @endforeach
                                                    @foreach ($detonatorlists as $detonator)
                                                        <option value='{{ $detonator }}' {{ $jcrExplosive['explosive'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                    @endforeach
                                                    <option value='NA' {{ $jcrExplosive['explosive'] == 'NA' ? 'selected' : '' }}>NA</option>
                                                </select>
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-issued' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-issued' class='form-label'> Charge
                                                    Issued</label>
                                                <input type='text' name='explosive[{{ $index }}][issued]' placeholder='Issued'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-issued'
                                                    value="{{ $jcrExplosive->issued }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-used' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-used' class='form-label'>Charge
                                                    Used</label>
                                                <input type='text' name='explosive[{{ $index }}][used]' placeholder='Used'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-used' value="{{ $jcrExplosive->used }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-returned' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-returned' class='form-label'>Charge
                                                    Returned</label>
                                                <input type='text' name='explosive[{{ $index }}][returned]' placeholder='Returned'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-returned'
                                                    value="{{ $jcrExplosive->returned }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class='explosive-form' id='id-explosive-form_1'>
                                        <h2 class='my-3'>Explosive - 1</h2>
                                        <div id='div_id_explosive_set-1-explosives' class='mb-3'>
                                            <label for='id_explosive_set-1-explosives' class='form-label'>Charges</label>
                                            <select name='explosive[0][explosive]' placeholder='--- Select Charge type ---'
                                                class='select form-select' id='id_explosive_set-1-explosives'>
                                                <option value=''>--- Select Charge type ---</option>
                                                @foreach ($explosivelists as $explosives)
                                                    <option value='{{ $explosives }}'>{{ $explosives }}</option>
                                                @endforeach
                                                @foreach ($primachordlists as $primachord)
                                                    <option value='{{ $primachord }}'>{{ $primachord }}</option>
                                                @endforeach
                                                @foreach ($detonatorlists as $detonator)
                                                    <option value='{{ $detonator }}'>{{ $detonator }}</option>
                                                @endforeach
                                                <option value='NA'>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_id_explosive_set-1-issued' class='mb-3'>
                                            <label for='id_explosive_set-1-issued' class='form-label'> Charge Issued</label>
                                            <input type='text' name='explosive[0][issued]' placeholder='Issued'
                                                class='textinput textInput form-control' id='id_explosive_set-1-issued'>
                                        </div>
                                        <div id='div_id_explosive_set-1-used' class='mb-3'>
                                            <label for='id_explosive_set-1-used' class='form-label'>Charge Used</label>
                                            <input type='text' name='explosive[0][used]' placeholder='Used'
                                                class='textinput textInput form-control' id='id_explosive_set-1-used'>
                                        </div>
                                        <div id='div_id_explosive_set-1-returned' class='mb-3'>
                                            <label for='id_explosive_set-1-returned' class='form-label'>Charge Returned</label>
                                            <input type='text' name='explosive[0][returned]' placeholder='Returned'
                                                class='textinput textInput form-control' id='id_explosive_set-1-returned'>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button class='btn btn-success btn-lg' id='add_more_explosive' type='button'><i
                                    class='fa fa-plus-circle'></i></button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
        <button type='button' name='next' id="third-step" class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100'>
                <fieldset id='hseinfo'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>HSE</h2>
                        <div id='div_id_permitType' class='mb-3'>
                            <label for='id_permitType' class='form-label requiredField'>
                                Permit Type<span class='asteriskField'>*</span>
                            </label><select name='permitType' placeholder='--- Select Permit Type ---'
                                class='select form-select' id='id_permitType'>
                                <option value='' {{ old('permitType') == '' || $jcr->permitType == '' ? 'selected' : ''}}>---
                                    Select Permit Type ---</option>
                                <option value='Cold Work Permit' {{ old('permitType') == 'Cold Work Permit' || $jcr->permitType == 'Cold Work Permit' ? 'selected' : ''}}>Cold Work Permit
                                </option>
                                <option value='Hot Work Permit' {{ old('permitType') == 'Hot Work Permit' || $jcr->permitType == 'Hot Work Permit' ? 'selected' : ''}}>Hot Work Permit</option>
                                <option value='NA' {{ old('permitType') == 'NA' || $jcr->permitType == 'NA' ? 'selected' : ''}}>
                                    Not Applicable</option>
                            </select>
                            @error('permitType')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_permitNo' class='mb-3'>
                            <label for='id_permitNo' class='form-label'>
                                Permit No.
                            </label>
                            <input type='text' name='permitNo' placeholder='Work Permit No.'
                                class='textinput textInput form-control' id='id_permitNo' value={{ old('permitNo') ? old('permitNo') : $jcr->permitNo }}>
                        </div>
                        <div id='div_id_permitWork' class='mb-3'>
                            <label for='id_permitWork' class='form-label requiredField'>
                                Form of permit to work<span class='asteriskField'>*</span>
                            </label><select name='permitWork' placeholder='Form of permit to work'
                                class='select form-select' id='id_permitWork'>
                                <option value='' {{ old('permitWork') == '' || $jcr->permitWork == '' ? 'selected' : ''}}>---
                                    Select Form of permit to work ---</option>
                                <option value='1' {{ old('permitWork') == '1' || $jcr->permitWork == '1' ? 'selected' : ''}}>Yes</option>
                                <option value='0' {{ old('permitWork') == '0' || $jcr->permitWork == '0' ? 'selected' : ''}}>No
                                </option>
                            </select>
                            @error('permitWork')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_elecLockout' class='mb-3' style='display: none;'>
                            <label for='id_elecLockout' class='form-label'>
                                Electrical Lock out
                            </label><select name='elecLockout' placeholder='Electrical Lock out' class='select form-select'
                                id='id_elecLockout'>
                                <option value='' {{ old('elecLockout') == '' || $jcr->elecLockout == '' ? 'selected' : ''}}>---
                                    Select Electrical Lock out ---</option>
                                <option value='1' {{ old('elecLockout') == '1' || $jcr->elecLockout == '1' ? 'selected' : ''}}>
                                    Yes</option>
                                <option value='0' {{ old('elecLockout') == '0' || $jcr->elecLockout == '0' ? 'selected' : ''}}>
                                    No</option>
                            </select>
                        </div>
                        <div id='div_id_elecLockoutNo' class='mb-3' style='display: none;'>
                            <label for='id_elecLockoutNo' class='form-label'>
                                Electrical Lock out No.
                            </label>
                            <input type='text' name='elecLockoutNo' placeholder='Electrical Lock out No.'
                                class='textinput textInput form-control' id='id_elecLockoutNo'
                                value="{{ old('elecLockoutNo') ? old('elecLockoutNo') : $jcr->elecLockoutNo }}">
                        </div>
                        <div id='div_id_safetyMeeting' class='mb-3'>
                            <label for='id_safetyMeeting' class='form-label requiredField'>
                                Safety Meeting conducted<span class='asteriskField'>*</span>
                            </label><select name='safetyMeeting' placeholder='Safety Meeting conducted'
                                class='select form-select' id='id_safetyMeeting'>
                                <option value='' {{ old('safetyMeeting') == '' || $jcr->safetyMeeting == '' ? 'selected' : ''}}>
                                    --- Select Safety Meeting conducted ---</option>
                                <option value='1' {{ old('safetyMeeting') == '1' || $jcr->safetyMeeting == '1' ? 'selected' : ''}}>Yes</option>
                                <option value='0' {{ old('safetyMeeting') == '0' || $jcr->safetyMeeting == '0' ? 'selected' : ''}}>No</option>
                            </select>
                            @error('safetyMeeting')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_jobCloseMeeting' class='mb-3'>
                            <label for='id_jobCloseMeeting' class='form-label requiredField'>
                                Job Close up Meeting<span class='asteriskField'>*</span>
                            </label><select name='jobCloseMeeting' placeholder='Job Close up Meeting'
                                class='select form-select' id='id_jobCloseMeeting'>
                                <option value='' {{ old('jobCloseMeeting') == '' || $jcr->jobCloseMeeting == '' ? 'selected' : '' }}>--- Select Job Close up Meeting ---</option>
                                <option value='1' {{ old('jobCloseMeeting') == '1' || $jcr->jobCloseMeeting == '1' ? 'selected' : '' }}>Yes</option>
                                <option value='0' {{ old('jobCloseMeeting') == '0' || $jcr->jobCloseMeeting == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('jobCloseMeeting')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_nearMiss' class='mb-3'>
                            <label for='id_nearMiss' class='form-label requiredField'>
                                Near Miss<span class='asteriskField'>*</span>
                            </label><select name='nearMiss' placeholder='Near Miss' class='select form-select'
                                id='id_nearMiss'>
                                <option value='' {{ old('nearMiss') == '' || $jcr->nearMiss == '' ? 'selected' : '' }}>---
                                    Select Near Miss ---</option>
                                <option value='1' {{ old('nearMiss') == '1' || $jcr->nearMiss == '1' ? 'selected' : '' }}>Yes
                                </option>
                                <option value='0' {{ old('nearMiss') == '0' || $jcr->nearMiss == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('nearMiss')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_nearMissDesc' class='mb-3' style='display: none;'>
                            <label for='id_nearMissDesc' class='form-label'>
                                Nearmiss Description
                            </label>
                            <textarea name='nearMissDesc' cols='40' rows='10' type='text'
                                placeholder='Nearmiss Description(if any)' class='textarea form-control'
                                id='id_nearMissDesc'>{{ old('nearMissDesc') ? old('nearMissDesc') : $jcr->nearMissDesc }}</textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100'>
                <fieldset id='jobstatus'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Job Status</h2>
                        <div id='div_id_jobStatus' class='mb-3'>
                            <label for='id_jobStatus' class='form-label requiredField'>
                                Job Status<span class='asteriskField'>*</span>
                            </label><select name='jobStatus' placeholder='Job Status' class='select form-select'
                                id='id_jobStatus'>
                                <option value='' {{ old('jobStatus') == '' || $jcr->jobStatus == '' ? 'selected' : '' }}>---
                                    Select Job Status ---</option>
                                <option value='Complete' {{ old('jobStatus') == 'Complete' || $jcr->jobStatus == 'Complete' ? 'selected' : 'selected' }}>Complete</option>
                                <option value='Incomplete' {{ old('jobStatus') == 'Incomplete' || $jcr->jobStatus == 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                                <option value='Continued' {{ old('jobStatus') == 'Continued' || $jcr->jobStatus == 'Continued' ? 'selected' : '' }}>Continued</option>
                                <option value='Well Problem' {{ old('jobStatus') == 'Well Problem' || $jcr->jobStatus == 'Well Problem' ? 'selected' : '' }}>Well Problem</option>
                                <option value='Not Feasible' {{ old('jobStatus') == 'Not Feasible' || $jcr->jobStatus == 'Not Feasible' ? 'selected' : '' }}>Not Feasible</option>
                            </select>
                            @error('jobStatus')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_remarks' class='mb-3'>
                            <label for='id_remarks' class='form-label requiredField'>
                                Remarks<span class='asteriskField'>*</span>
                            </label>
                            <textarea name='remarks' cols='40' rows='10' type='text' placeholder='Remarks'
                                class='textarea form-control'
                                id='id_remarks'>{{ old('remarks', $jcr->remarks ?? '') }}</textarea>
                            @error('remarks')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_objective' class='mb-3' style='display: none;'>
                            <label for='id_objective' class='form-label'>
                                Objective
                            </label>
                            <textarea name='objective' cols='40' rows='10' type='text'
                                placeholder='Write the objective of PL' class='textarea form-control' id='id_objective'
                                value=''>{{ old('objective') ? old('objective') : $jcr->objective}}</textarea>
                        </div>
                        <div id='div_id_observations' class='mb-3' style='display: none;'>
                            <label for='id_observations' class='form-label'>
                                Observations
                            </label>
                            <textarea name='observations' cols='40' rows='10' type='text'
                                placeholder='Write the Observations/Findings from this PL' class='textarea form-control'
                                id='id_observations'
                                value=''>{{ old('observations') ? old('observations') : $jcr->observations}}</textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
        <button type="submit" name="action" value="save_draft" class="btn btn-secondary">
            <i class="fas fa-save"></i>Update JCR as Draft
        </button>

        <button type="submit" name="action" value="submit" class="btn btn-primary ml-2">
            <i class="fas fa-check-circle"></i> Submit for Review
        </button>
        <a href="{{ route('jcr.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
@else
    <!-- Selected Time Register Preview -->
    <div class="card my-4 w-75">
        <div class="card-header bg-light">
            <h6 class="mb-0">Linked Time Register</h6>
        </div>
        <div class="card-body">
            <div id="linkedTimeRegisterPreview"></div>
            <button type="button" class="btn btn-sm btn-warning" onclick="showTimeRegisterModal()">
                Select/Change Time Register
            </button>
        </div>
    </div>

    <!-- Button to open checklist modal and summary of currently linked checklists -->
    <div class="card my-4 w-75">
        <div class="card-header bg-light">
            <h6 class="mb-0">Linked Checklists</h6>
        </div>
        <div class="card-body mb-2">
            <div class="d-flex align-items-center mb-2">
                <button type="button" class="btn btn-sm btn-warning me-3"
                            data-bs-toggle="modal" data-bs-target="#checklistModal">Select/Change Checklists</button>
                <div>
                    <small class="text-muted">Selected Checklists:</small>
                    <strong id="linkedChecklistCount">0</strong>
                </div>
            </div>

            <div id="linkedChecklistPreview">
                <div class="text-muted">No checklists selected.</div>
            </div>
        </div>
    </div>
    <ul class='d-flex justify-content-between' id='progressbar'>
        <li class='progress-step active' id='firststep'><strong>First Step</strong></li>
        <li class='progress-step' id='secondstep'><strong>Second Step</strong></li>
        <li class='progress-step' id='thirdstep'><strong>Third Step</strong></li>
        <li class='progress-step' id='finalstep'><strong>Final Step</strong></li>
    </ul>
    <div class='container-fluid pb-3 form-step'>
        <div class='d-flex'>
            <div class='card h-100 w-25'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='basicinfo'>
                        <div class='form-card step w-20'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Basic Info</h2>
                            <!-- Inside your main JCR form -->
                            <input type="hidden" name="checklist_ids" id="selectedChecklistIds">
                            <input type="hidden" name="time_register_id" id="selected_time_register_id">
                            
                            <div id='div_id_fieldName' class='mb-3'>
                                <label for='id_fieldName' class='form-label requiredField'>
                                    Field/Area<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='fieldName' placeholder='Field/Area'
                                    class='textinput textInput form-control' id='id_fieldName'
                                    value="{{ old('fieldName') }}" required>
                                @error('fieldName')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellNo' class='mb-3'>
                                <label for='id_wellNo' class='form-label requiredField'>
                                    Well No./Code<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='wellNo' placeholder='Well No./Code'
                                    class='textinput textInput form-control' id='id_wellNo' value="{{ old('wellNo') }}"
                                    required>
                                @error('wellNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_loggingType' class='mb-3'>
                                <label for='id_loggingType' class='form-label requiredField'>
                                    Logging Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='loggingType' placeholder='Logging Type' class='select form-select'
                                    id='id_loggingType' required>
                                    <option value='' selected disabled {{ old('loggingType') == '' ? 'selected' : ''}}>---
                                        Select Logging Type ---</option>';
                                    @foreach ($loggingTypes as $loggingType)
                                        <option value="{{ $loggingType }}" {{ old('loggingType') == $loggingType ? 'selected' : ''}}>{{ $loggingType }}</option>
                                    @endforeach
                                </select>
                                @error('loggingType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_logType' class='mb-3'>
                                <label for='id_logType' class='form-label requiredField'>
                                    Job Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='logType' placeholder='Job Type' class='select form-select' id='id_logType'
                                    required>
                                    <option value='' disabled {{ old('logType') == '' ? 'selected' : '' }}>---
                                        Select Job Type ---</option>
                                    @foreach ($logTypes as $logType)
                                        <option value='{{ $logType }}' {{ old('logType') == $logType ? 'selected' : '' }}>
                                            {{ $logType }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('logType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_logging_unit_type' class='mb-3'>
                                <label for='id_logging_unit_type' class='form-label requiredField'>
                                    Logging Unit Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='logging_unit_type' class='select form-select' id='id_logging_unit_type' required>
                                    <option value='departmental' {{ old('logging_unit_type', 'departmental') == 'departmental' ? 'selected' : '' }}>
                                        Departmental
                                    </option>
                                    <option value='contractual' {{ old('logging_unit_type', 'departmental') == 'contractual' ? 'selected' : '' }}>
                                        Contractual
                                    </option>
                                </select>
                                @error('logging_unit_type')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_unitNo' class='mb-3'>
                                <label for='id_unitNo' class='form-label requiredField'>
                                    Logging Unit Number<span class='asteriskField'>*</span>
                                </label>
                                <select name='unitNo' class='select form-select' id='id_unitNo' required>
                                    <option value='' disabled {{ old('unitNo') == '' ? 'selected' : '' }}>---
                                        Select Unit ---</option>
                                    @foreach ($unitNos as $unitNo)
                                        <option value='{{ $unitNo }}' {{ old('unitNo') == $unitNo ? 'selected' : '' }}>
                                            {{ $unitNo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unitNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_jobDate' class='mb-3'>
                                <label for='id_jobDate' class='form-label requiredField'>
                                    Job Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='jobDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_jobDate' autocomplete='off' maxlength='10'
                                    value="{{ old('jobDate') }}" required>
                                @error('jobDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_jobNo' class='mb-3'>
                                <label for='id_jobNo' class='form-label requiredField'>
                                    Job No.<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='jobNo' placeholder='Job No.'
                                    class='textinput textInput form-control' id='id_jobNo' value="{{ old('jobNo') }}"
                                    required>
                                @error('jobNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_workOrderDate' class='mb-3'>
                                <label for='id_workOrderDate' class='form-label requiredField'>
                                    Work Order Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='workOrderDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_workOrderDate' autocomplete='off' maxlength='10'
                                    value="{{old('workOrderDate') }}" required>
                                @error('workOrderDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_indentNo' class='mb-3'>
                                <label for='id_indentNo' class='form-label requiredField'>
                                    Indent Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='indentNo' placeholder='LGOOO123456'
                                    class='textinput textInput form-control' id='id_indentNo' value="{{ old('indentNo') }}"
                                    required>
                                @error('indentNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_rigNo' class='mb-3'>
                                <label for='id_rigNo' class='form-label requiredField'>
                                    Rig Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='rigNo' placeholder='Rig Number'
                                    class='textinput textInput form-control' id='id_rigNo' value="{{ old('rigNo') }}"
                                    required>
                                @error('rigNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_kb' class='mb-3'>
                                <label for='id_kb' class='form-label'>
                                    KB(m)
                                </label>
                                <input type='text' name='kb' placeholder='KB' class='textinput textInput form-control'
                                    id='id_kb' value="{{ old('kb') }}">
                                @error('kb')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_gl' class='mb-3'>
                                <label for='id_gl' class='form-label'>
                                    Ground Level(m)
                                </label>
                                <input type='text' name='gl' placeholder='GL' class='textinput textInput form-control'
                                    id='id_gl' value="{{ old('gl') }}">
                                @error('gl')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellOwner' class='mb-3'>
                                <label for='id_wellOwner' class='form-label requiredField'>
                                    Well Owner<span class='asteriskField'>*</span>
                                </label>
                                <select name='wellOwner' placeholder='Well Owner' class='select form-select'
                                    id='id_wellOwner' required>
                                    <option value='' disabled {{ old('wellOwner') == '' ? 'selected' : '' }}>---
                                        Select Well Owner ---</option>
                                    @foreach ($wellOwners as $wellOwner)
                                        <option value='{{ $wellOwner }}' {{ old('wellOwner') == $wellOwner ? 'selected' : '' }}>
                                            {{ $wellOwner }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wellOwner')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_mastVanNo' class='mb-3'>
                                <label for='id_mastVanNo' class='form-label'>
                                    Mast/Van Number
                                </label>
                                <input type='text' name='mastVanNo' placeholder='Mast/Van Number'
                                    class='textinput textInput form-control' id='id_mastVanNo'
                                    value="{{ old('mastVanNo') }}">
                                @error('mastVanNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_lvNo' class='mb-3'>
                                <label for='id_lvNo' class='form-label requiredField'>
                                    Light Vehicle Number<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='lvNo' placeholder='Light Vehicle Number'
                                    class='textinput textInput form-control' id='id_lvNo' value="{{ old('lvNo') }}"
                                    required>
                                @error('lvNo')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellType' class='mb-3'>
                                <label for='id_wellType' class='form-label requiredField'>
                                    Well Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='wellType' placeholder='Well Type' class='select form-select' id='id_wellType'
                                    required>
                                    <option value='' disabled {{ old('wellType') == '' ? 'selected' : ''}}>---
                                        Select
                                        Well Type ---</option>
                                    @foreach ($wellTypes as $wellType)
                                        <option value='{{$wellType}}' {{ old('wellType') == $wellType ? 'selected' : ''}}>
                                            {{$wellType}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wellType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_rigType' class='mb-3'>
                                <label for='id_rigType' class='form-label requiredField'>
                                    Rig Type<span class='asteriskField'>*</span>
                                </label>
                                <select name='rigType' placeholder='Rig Type' class='select form-select' id='id_rigType'
                                    required>
                                    <option value='' disabled {{ old('rigType') == '' ? 'selected' : '' }}>---
                                        Select
                                        Rig Type ---</option>
                                    @foreach($rigTypes as $rigType)
                                        <option value='{{ $rigType }}' {{ old('rigType') == $rigType ? 'selected' : '' }}>
                                            {{ $rigType }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rigType')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-50'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='timeinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Time info</h2>
                            <div id='div_id_assembled' class='mb-3'>
                                <label class='form-label requiredField'>Assembled<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='assembled_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_assembled_date'
                                            autocomplete='on' maxlength='16' value="{{ old('assembled_date') }}" required>
                                        @error('assembled_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='assembled_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_assembled_time' autocomplete='on'
                                            maxlength='16' value="{{ old('assembled_time') }}" required>
                                        @error('assembled_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_depOffice' class='mb-3'>
                                <label class='form-label requiredField'>Departure Office<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depOffice_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_depOffice_date'
                                            autocomplete='off' maxlength='16' value="{{ old('depOffice_date') }}" required>
                                        @error('depOffice_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depOffice_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_depOffice_time' autocomplete='off'
                                            maxlength='16' value="{{ old('depOffice_time') }}" required>
                                        @error('depOffice_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_arrivalSite' class='mb-3'>
                                <label class='form-label requiredField'>Arrival Site<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalSite_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_arrivalSite_date' autocomplete='off' maxlength='16'
                                            value="{{ old('arrivalSite_date') }}" required>
                                        @error('arrivalSite_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalSite_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_arrivalSite_time' autocomplete='off'
                                            maxlength='16' value="{{ old('arrivalSite_time') }}" required>
                                        @error('arrivalSite_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_indented' class='mb-3'>
                                <label class='form-label requiredField'>Indented<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='indented_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_indented_date'
                                            autocomplete='off' maxlength='16' value="{{ old('indented_date') }}" required>
                                        @error('indented_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='indented_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_indented_time' autocomplete='off'
                                            maxlength='16' value="{{ old('indented_time') }}" required>
                                        @error('indented_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellReadiness' class='mb-3'>
                                <label class='form-label requiredField'>Well Readiness<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellReadiness_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_wellReadiness_date' autocomplete='off' maxlength='16'
                                            value="{{ old('wellReadiness_date') }}" required>
                                        @error('wellReadiness_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellReadiness_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellReadiness_time' autocomplete='off'
                                            maxlength='16' value="{{ old('wellReadiness_time') }}" required>
                                        @error('wellReadiness_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellTaken' class='mb-3'>
                                <label class='form-label requiredField'>Well Taken<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellTaken_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_wellTaken_date'
                                            autocomplete='off' maxlength='16' value="{{ old('wellTaken_date') }}" required>
                                        @error('wellTaken_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellTaken_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellTaken_time' autocomplete='off'
                                            maxlength='16' value="{{ old('wellTaken_time') }}" required>
                                        @error('wellTaken_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_rigUP' class='mb-3'>
                                <label class='form-label requiredField'>Rig Up<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='rigUP_date' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                            class='datetimeinput form-control' id='id_rigUP_date' autocomplete='off'
                                            maxlength='16' value="{{ old('rigUP_date') }}" required>
                                        @error('rigUP_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='rigUP_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_rigUP_time' autocomplete='off'
                                            maxlength='16' value="{{ old('rigUP_time') }}" required>
                                        @error('rigUP_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_wellHandOver' class='mb-3'>
                                <label class='form-label requiredField'>Well Hand Over<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellHandOver_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_wellHandOver_date' autocomplete='off' maxlength='16'
                                            value="{{ old('wellHandOver_date') }}" required>
                                        @error('wellHandOver_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='wellHandOver_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_wellHandOver_time' autocomplete='off'
                                            maxlength='16' value="{{ old('wellHandOver_time') }}" required>
                                        @error('wellHandOver_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_depSite' class='mb-3'>
                                <label class='form-label requiredField'>Departure Site<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depSite_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control' id='id_depSite_date'
                                            autocomplete='off' maxlength='16' value="{{ old('depSite_date') }}" required>
                                        @error('depSite_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='depSite_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_depSite_time' autocomplete='off'
                                            maxlength='16' value="{{ old('depSite_time') }}" required>
                                        @error('depSite_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_arrivalOffice' class='mb-3'>
                                <label class='form-label requiredField'>Arrival Office<span class='asteriskField'>*</span></label>
                                <div class='d-flex'>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalOffice_date' placeholder='YYYY-MM-DD'
                                            data-mask='0000-00-00' class='datetimeinput form-control'
                                            id='id_arrivalOffice_date' autocomplete='off' maxlength='16'
                                            value="{{ old('arrivalOffice_date') }}" required>
                                        @error('arrivalOffice_date')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class='container-fluid mx-1 p-0'>
                                        <input type='text' name='arrivalOffice_time' placeholder='HH:MM' data-mask='00:00'
                                            class='datetimeinput form-control' id='id_arrivalOffice_time' autocomplete='off'
                                            maxlength='16' value="{{ old('arrivalOffice_time') }}" required>
                                        @error('arrivalOffice_time')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id='div_id_preparationTime' class='mb-3'>
                                <label for='id_preparationTime' class='form-label requiredField'>
                                    Preparation Time(Hrs.)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='preparationTime' placeholder='Enter Preparation time in hours.'
                                    class='textinput textInput form-control' id='id_preparationTime'
                                    value="{{ old('preparationTime') ? old('preparationTime') : '1.0' }}" required>
                                @error('preparationTime')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_postProceTime' class='mb-3'>
                                <label for='id_postProceTime' class='form-label requiredField'>
                                    Post Processing Time(Hrs.)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='postProceTime' placeholder='Enter Post Processing time in hours'
                                    class='textinput textInput form-control' id='id_postProceTime'
                                    value="{{ old('postProceTime') ? old('postProceTime') : '1.0' }}" required>
                                @error('postProceTime')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-25'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='wellinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Well info</h2>
                            <div id='div_id_depthDriller' class='mb-3'>
                                <label for='id_depthDriller' class='form-label'>
                                    Depth Driller (m)
                                </label>
                                <input type='text' name='depthDriller' placeholder='Depth Driller'
                                    class='textinput textInput form-control' id='id_depthDriller'
                                    value="{{ old('depthDriller')  }}">
                            </div>
                            <div id='div_id_depthLogger' class='mb-3'>
                                <label for='id_depthLogger' class='form-label'>
                                    Depth Logger (m)
                                </label>
                                <input type='text' name='depthLogger' placeholder='Depth Logger'
                                    class='textinput textInput form-control' id='id_depthLogger'
                                    value="{{ old('depthLogger')  }}">
                            </div>
                            <div id='div_id_casingSize' class='mb-3'>
                                <label for='id_casingSize' class='form-label'>
                                    Casing Size(inch)
                                </label>
                                <input type='text' name='casingSize' placeholder='Casing Size(inch)'
                                    class='textinput textInput form-control' id='id_casingSize'
                                    value="{{ old('casingSize')  }}">
                            </div>
                            <div id='div_id_casingShoeDriller' class='mb-3'>
                                <label for='id_casingShoeDriller' class='form-label'>
                                    Casing Shoe Driller (m)
                                </label>
                                <input type='text' name='casingShoeDriller' placeholder='Casing Shoe Driller'
                                    class='textinput textInput form-control' id='id_casingShoeDriller'
                                    value="{{ old('casingShoeDriller')  }}">
                            </div>
                            <div id='div_id_casingShoeLogger' class='mb-3'>
                                <label for='id_casingShoeLogger' class='form-label'>
                                    Casing Shoe Logger (m)
                                </label>
                                <input type='text' name='casingShoeLogger' placeholder='Casing Shoe Logger'
                                    class='textinput textInput form-control' id='id_casingShoeLogger'
                                    value="{{ old('casingShoeLogger')  }}">
                            </div>
                            <div id='div_id_floatCollar' class='mb-3'>
                                <label for='id_floatCollar' class='form-label'>
                                    Float Collar (m)
                                </label>
                                <input type='text' name='floatCollar' placeholder='Float Collar'
                                    class='textinput textInput form-control' id='id_floatCollar'
                                    value="{{ old('floatCollar')  }}">
                            </div>
                            <div id='div_id_bitSize' class='mb-3'>
                                <label for='id_bitSize' class='form-label'>
                                    Bit Size(inch)
                                </label>
                                <input type='text' name='bitSize' placeholder='Bit Size(inch)'
                                    class='textinput textInput form-control' id='id_bitSize' value="{{ old('bitSize')  }}">
                            </div>
                            <div id='div_id_tubingSize' class='mb-3'>
                                <label for='id_tubingSize' class='form-label'>
                                    Tubing Size(inch)
                                </label>
                                <input type='text' name='tubingSize' placeholder='Tubing Size(inch)'
                                    class='textinput textInput form-control' id='id_tubingSize'
                                    value="{{ old('tubingSize')  }}">
                            </div>
                            <div id='div_id_t_shoe_Packer' class='mb-3'>
                                <label for='id_t_shoe_Packer' class='form-label'>
                                    T/shoe/Packer (m)
                                </label>
                                <input type='text' name='t_shoe_Packer' placeholder='T/shoe/Packer'
                                    class='textinput textInput form-control' id='id_t_shoe_Packer'
                                    value="{{ old('t_shoe_Packer')  }}">
                            </div>
                            <div id='div_id_s_nippletopexp' class='mb-3'>
                                <label for='id_s_nippletopexp' class='form-label'>
                                    S/nipple top exp. (m)
                                </label>
                                <input type='text' name='s_nippletopexp' placeholder='S/nipple top exp.'
                                    class='textinput textInput form-control' id='id_s_nippletopexp'
                                    value="{{ old('s_nippletopexp')  }}">
                            </div>
                            <div id='div_id_THP' class='mb-3'>
                                <label for='id_THP' class='form-label'>
                                    THP
                                </label>
                                <input type='text' name='THP' placeholder='THP' class='textinput textInput form-control'
                                    id='id_THP' value="{{ old('THP')  }}">
                            </div>
                            <div id='div_id_maxDevAt' class='mb-3'>
                                <label for='id_maxDevAt' class='form-label'>
                                    Max Dev at
                                </label>
                                <input type='text' name='maxDevAt' placeholder='Max Dev at'
                                    class='textinput textInput form-control' id='id_maxDevAt'
                                    value="{{ old('maxDevAt')  }}">
                            </div>
                            <div id='div_id_distTo_FroKms' class='mb-3'>
                                <label for='id_distTo_FroKms' class='form-label'>
                                    Dist (To &amp; Fro) Kms.
                                </label>
                                <input type='text' name='distTo_FroKms' placeholder='Dist (To &amp; Fro) Kms.'
                                    class='textinput textInput form-control' id='id_distTo_FroKms'
                                    value="{{ old('distTo_FroKms')  }}">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <button type='button' id='first-step' name='next' class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100 w-40'>
                <fieldset id='mudinfo'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Mud info</h2>
                        <div id='div_id_mudType' class='mb-3'>
                            <label for='id_mudType' class='form-label'>
                                MUD Type
                            </label>
                            <input type='text' name='mudType' placeholder='Mud Type' maxlength='20'
                                class='textinput textInput form-control' id='id_mudType' value="{{ old('mudType')  }}">
                        </div>
                        <div id='div_id_rm' class='mb-3'>
                            <label for='id_rm' class='form-label'>
                                Rm
                            </label>
                            <input type='text' name='rm' placeholder='Rm' class='textinput textInput form-control'
                                id='id_rm' value="{{ old('rm')  }}">
                        </div>
                        <div id='div_id_rmtemp' class='mb-3'>
                            <label for='id_rmtemp' class='form-label'>
                                Rm Temperature
                            </label>
                            <input type='text' name='rmtemp' placeholder='Rm Temp' class='textinput textInput form-control'
                                id='id_rmtemp' value="{{ old('rmtemp')  }}">
                        </div>
                        <div id='div_id_rmf' class='mb-3'>
                            <label for='id_rmf' class='form-label'>
                                Rmf
                            </label>
                            <input type='text' name='rmf' placeholder='Rmf' class='textinput textInput form-control'
                                id='id_rmf' value="{{ old('rmf')  }}">
                        </div>
                        <div id='div_id_rmftemp' class='mb-3'>
                            <label for='id_rmftemp' class='form-label'>
                                Rmf Temperature
                            </label>
                            <input type='text' name='rmftemp' placeholder='Rmf Temp'
                                class='textinput textInput form-control' id='id_rmftemp' value="{{ old('rmftemp')  }}">
                        </div>
                        <div id='div_id_rmc' class='mb-3'>
                            <label for='id_rmc' class='form-label'>
                                Rmc
                            </label>
                            <input type='text' name='rmc' placeholder='Rmc' class='textinput textInput form-control'
                                id='id_rmc' value="{{ old('rmc')  }}">
                        </div>
                        <div id='div_id_rmctemp' class='mb-3'>
                            <label for='id_rmctemp' class='form-label'>
                                Rmc Temperature
                            </label>
                            <input type='text' name='rmctemp' placeholder='Rmc Temp'
                                class='textinput textInput form-control' id='id_rmctemp' value="{{ old('rmctemp')  }}">
                        </div>
                        <div id='div_id_bht' class='mb-3'>
                            <label for='id_bht' class='form-label'>
                                Bottom Hole Temp.
                            </label>
                            <input type='text' name='bht' placeholder='Bottom Hole Temp'
                                class='textinput textInput form-control' id='id_bht' value="{{ old('bht')  }}">
                        </div>
                        <div id='div_id_bhtdepth' class='mb-3'>
                            <label for='id_bhtdepth' class='form-label'>
                                BHT Depth
                            </label>
                            <input type='text' name='bhtdepth' placeholder='BHT Depth'
                                class='textinput textInput form-control' id='id_bhtdepth' value="{{ old('bhtdepth')  }}">
                        </div>
                        <div id='div_id_spgr' class='mb-3'>
                            <label for='id_spgr' class='form-label'>
                                Sp. Gr. (gm/cc)
                            </label>
                            <input type='text' name='spgr' placeholder='Sp. Gr.' class='textinput textInput form-control'
                                id='id_spgr' value="{{ old('spgr')  }}">
                        </div>
                        <div id='div_id_viscosity' class='mb-3'>
                            <label for='id_viscosity' class='form-label'>
                                Viscosity
                            </label>
                            <input type='text' name='viscosity' placeholder='Viscosity'
                                class='textinput textInput form-control' id='id_viscosity' value="{{ old('viscosity')  }}">
                        </div>
                        <div id='div_id_waterloss' class='mb-3'>
                            <label for='id_waterloss' class='form-label'>
                                Water Loss
                            </label>
                            <input type='text' name='waterloss' placeholder='Water Loss'
                                class='textinput textInput form-control' id='id_waterloss' value="{{ old('waterloss')  }}">
                        </div>
                        <div id='div_id_ph' class='mb-3'>
                            <label for='id_ph' class='form-label'>
                                PH
                            </label>
                            <input type='text' name='ph' placeholder='PH' class='textinput textInput form-control'
                                id='id_ph' value="{{ old('ph')  }}">
                        </div>
                        <div id='div_id_oilpercnt' class='mb-3'>
                            <label for='id_oilpercnt' class='form-label'>
                                Oil%
                            </label>
                            <input type='text' name='oilpercnt' placeholder='Oil%' class='textinput textInput form-control'
                                id='id_oilpercnt' value="{{ old('oilpercnt')  }}">
                        </div>
                        <div id='div_id_kcl_barytes' class='mb-3'>
                            <label for='id_kcl_barytes' class='form-label'>
                                KCl/Barytes
                            </label>
                            <input type='text' name='kcl_barytes' placeholder='KCl/Barytes'
                                class='textinput textInput form-control' id='id_kcl_barytes'
                                value="{{ old('kcl_barytes')  }}">
                        </div>
                        <div id='div_id_salinity' class='mb-3'>
                            <label for='id_salinity' class='form-label'>
                                Salinity (GPL)
                            </label>
                            <input type='text' name='salinity' placeholder='Salinity'
                                class='textinput textInput form-control' id='id_salinity' value="{{ old('salinity')  }}">
                        </div>
                        <div id='div_id_lastcirc_from' class='mb-3'>
                            <label for='id_lastcirc_from' class='form-label'>
                                Last Circulation from
                            </label>
                            <input type='text' name='lastcirc_from' placeholder='YYYY-MM-DD HH:MM'
                                data-mask='0000-00-00 00:00' class='datetimeinput form-control' id='id_lastcirc_from'
                                autocomplete='off' style='z-index: 100;' value='{{ old("lastcirc_from") }}'>
                        </div>
                        <div id='div_id_lastcirc_to' class='mb-3'>
                            <label for='id_lastcirc_to' class='form-label'>
                                Last Circulation to
                            </label>
                            <input type='text' name='lastcirc_to' placeholder='YYYY-MM-DD HH:MM'
                                data-mask='0000-00-00 00:00' class='datetimeinput form-control' id='id_lastcirc_to'
                                autocomplete='off' style='z-index: 100;' value='{{ old("lastcirc_to") }}'>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100 w-30'>
                <div class='card-body px-0 pt-0'>
                    <fieldset id='cableinfo'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Cable info</h2>
                            <div id='div_id_cableSize' class='mb-3'>
                                <label for='id_cableSize' class='form-label requiredField'>
                                    Cable Size (inch)<span class='asteriskField'>*</span>
                                </label>
                                <select name='cableSize' placeholder='Cable Size' class='select form-select'
                                    id='id_cableSize' required>
                                    <option value='' disabled selected>--- Select Cable Size ---</option>
                                    @foreach ($cableSizes as $cableSize)
                                        <option value={{$cableSize}} {{ old('cableSize') == $cableSize ? 'selected' : '' }}>
                                            {{$cableSize}}</option>
                                    @endforeach
                                </select>
                                @error('cableSize')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_insulation' class='mb-3'>
                                <label for='id_insulation' class='form-label requiredField'>
                                    Insulation<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='insulation' placeholder='Insulation'
                                    class='textinput textInput form-control' id='id_insulation'
                                    value="{{ old('insulation')  }}" required>
                                @error('insulation')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_shoeDate' class='mb-3'>
                                <label for='id_shoeDate' class='form-label requiredField'>
                                    Shoe Date<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='shoeDate' placeholder='YYYY-MM-DD' data-mask='0000-00-00'
                                    class='dateinput form-control' id='id_shoeDate' autocomplete='off' maxlength='10'
                                    value="{{ old('shoeDate') }}" required>
                                @error('shoeDate')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_weakPoint' class='mb-3'>
                                <label for='id_weakPoint' class='form-label requiredField'>
                                    Weak Point<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='weakPoint' placeholder='[e.g. (OL+IL)]'
                                    class='textinput textInput form-control' id='id_weakPoint'
                                    value="{{ old('weakPoint')  }}" required>
                                @error('weakPoint')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_cableHeadSize' class='mb-3'>
                                <label for='id_cableHeadSize' class='form-label requiredField'>
                                    Cable Head Size (inch)<span class='asteriskField'>*</span>
                                </label>
                                <select name='cableHeadSize' placeholder='Cable Head Size'
                                    class='textinput textInput form-control' id='id_cableHeadSize'
                                    required>
                                    <option value='' disabled selected>--- Select Cable Head Size ---</option>
                                    @foreach($cableHeadSizes as $cableHeadSize)
                                        <option value='{{ $cableHeadSize }}' {{ old('cableHeadSize') == $cableHeadSize ? 'selected' : '' }}>{{ $cableHeadSize }}</option>
                                    @endforeach
                                </select>
                                @error('cableHeadSize')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_cableLength' class='mb-3'>
                                <label for='id_cableLength' class='form-label requiredField'>
                                    Cable Length(m)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='cableLength' placeholder='Cable Length(m)'
                                    class='textinput textInput form-control' id='id_cableLength'
                                    value="{{ old('cableLength')  }}" required>
                                @error('cableLength')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_initialLength' class='mb-3'>
                                <label for='id_initialLength' class='form-label requiredField'>
                                    Initial Cable Length(m)<span class='asteriskField'>*</span>
                                </label>
                                <input type='text' name='initialLength' placeholder='Initial Cable Length(m)'
                                    class='textinput textInput form-control' id='id_initialLength'
                                    value="{{ old('initialLength')  }}" required>
                                @error('initialLength')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class='card-body px-0'>
                    <fieldset id='surfaceequipment'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Surface Equipment</h2>
                            <div id='div_id_surfaceEquipment' class='mb-3'>
                                <label for='id_surfaceEquipment' class='form-label requiredField'>Surface
                                    Equipment<span class='asteriskField'>*</span></label>
                                <input type='text' name='surfaceEquipment' placeholder='Surface Equipment'
                                    class='textinput textInput form-control' id='id_surfaceEquipment'
                                    value="{{ old('surfaceEquipment') ? old('surfaceEquipment') : 'OK' }}" required>
                                @error('surfaceEquipment')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_automobile' class='mb-3'>
                                <label for='id_automobile' class='form-label requiredField'>Automobile<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='automobile' placeholder='Automobile'
                                    class='textinput textInput form-control' id='id_automobile'
                                    value="{{ old('automobile') ? old('automobile') : 'OK' }}" required>
                                @error('automobile')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_wellCondition' class='mb-3'>
                                <label for='id_wellCondition' class='form-label requiredField'>Well
                                    Condition<span class='asteriskField'>*</span></label>
                                <input type='text' name='wellCondition' placeholder='Well Condition'
                                    class='textinput textInput form-control' id='id_wellCondition'
                                    value="{{ old('wellCondition') ? old('wellCondition') : 'OK' }}" required>
                                @error('wellCondition')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div id='div_id_timeLoss' class='mb-3'>
                                <label for='id_timeLoss' class='form-label requiredField'>Time Loss(Hr)<span
                                        class='asteriskField'>*</span></label>
                                <input type='text' name='timeLoss' placeholder='Time Loss(Hr)'
                                    class='textinput textInput form-control' id='id_timeLoss'
                                    value="{{ old('timeLoss') ? old('timeLoss') : 'NO' }}" required>
                                @error('timeLoss')
                                    <small class='error'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='card h-100 w-30'>
                <fieldset id='personnel'>
                    <div class='form-card step' id='personnel-form'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Personnel</h2>
                        <div id='wrapper'>
                            @if (old('personnel'))
                                @foreach (old('personnel') as $index => $old_personnel)
                                    <div class='mb-3 element' id='userinlinemodel_{{ $index + 1 }}'>
                                        <label for='select_{{ $index + 1 }}' class='form-label requiredField'
                                            id='personnellabel_{{ $index + 1 }}'>Personnel<span class='asteriskField'
                                                id='asterisk_{{ $index + 1 }}'>*</span></label>
                                        <select data-live-search='true' class='select form-select mb-3 personnelselect'
                                            id='select_{{ $index + 1 }}' name='personnel[{{ $index }}][user_id]' required>
                                            <option value='' {{ old('personnel') == '' ? 'selected' : ''}}>--- Select
                                                Personnel ---</option>
                                            @foreach ($users->sortBy('seniority') as $user)
                                                <option value='{{ $user->id }}' {{ $old_personnel['user_id'] == $user->id ? 'selected' : ''}}>{{ Str::title($user->name) }}</option>
                                            @endforeach
                                        </select>
                                        <button class='btn btn-danger btn-sm remove my-3' id='remove_personnel_{{ $index + 1 }}' type='button'><i class='fa fa-minus-circle'></i></button>
                                        @error('personnel.*.user_id')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endforeach
                            @else
                                <div class='mb-3 element' id='userinlinemodel_1'>
                                    <label for='select_1' class='form-label requiredField' id='personnellabel_1'>Personnel<span
                                            class='asteriskField' id='asterisk_1'>*</span> </label>
                                    <select data-live-search='true' class='select form-select mb-3 personnelselect'
                                        id='select_1' name='personnel[0][user_id]' required>
                                        <option value='' {{ old('personnel') == '' ? 'selected' : ''}}>--- Select
                                            Personnel ---</option>
                                        @foreach ($users->sortBy('seniority') as $user)
                                            <option value='{{ $user->id }}'>{{ Str::title($user->name) }}</option>
                                        @endforeach
                                    </select>
                                    <button class='btn btn-danger btn-sm remove my-3' id='remove_personnel_1' type='button'><i class='fa fa-minus-circle'></i></button>
                                    @error('personnel.*.user_id')
                                        <small class='error'>{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        <button class='btn btn-success btn-lg' id='add_more_personnel' type='button'><i
                                class='fa fa-plus-circle'></i></button>
                        <div class='contingents'>
                            <div id='div_id_contingents' class='mb-3'>
                                <label for='id_contingents' class='form-label'>
                                    Contingents<span class='asteriskField'>*</span>
                                </label>
                                <textarea name='contingents' cols='40' rows='10' type='text'
                                    placeholder='Contingents involved in this job' class='textarea form-control'
                                    id='id_contingents'>{{ old('contingents')  }}</textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
        <button type='button' name='next' id="second-step" class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100'>
                <fieldset id='logsrecorded'>
                    <div class='form-card step container'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Logs Recorded</h2>
                        <div class="logs-wrapper">
                            @if (old('logrecorded'))
                                @foreach (old('logrecorded') as $index => $old_logrecorded)
                                    <div class='log-form' id='id-log-form_{{ $index + 1 }}'>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-runNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-runNo' class='form-label requiredField'>Run
                                                No.<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][runNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-runNo'
                                                value='{{ $old_logrecorded['runNo']  }}' required>
                                            @error('logrecorded.*.runNo')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logRecorded' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logRecorded'
                                                class='form-label requiredField'>
                                                Type of Logs Recorded<span class='asteriskField'>*</span> </label>
                                            <select name='logrecorded[{{ $index }}][logRecorded]'
                                                class='textinput textInput form-control mb-3 logsRecorded'
                                                id='id_logmodel_set-{{ $index + 1 }}-logRecorded' required>
                                                <option value="" disabled>--- Select Recorded Log ---</option>
                                                <optgroup label="Cased Hole logs" id="CH">
                                                <optgroup label="Explosive logs" id="explosive-logs">
                                                    <option value="Perforation(CCL)" {{ $old_logrecorded['logRecorded'] == 'Perforation(CCL)' ? 'selected' : '' }}>
                                                        Perforation(CCL)</option>
                                                    <option value="TTP(CCL)" {{ $old_logrecorded['logRecorded'] == 'TTP(CCL)' ? 'selected' : '' }}>TTP(CCL)</option>
                                                    <option value="Bridge Plug(CCL)" {{ $old_logrecorded['logRecorded'] == 'Bridge Plug(CCL)' ? 'selected' : '' }}>Bridge Plug(CCL)</option>
                                                    <option value="Tubing Puncture" {{ $old_logrecorded['logRecorded'] == 'Tubing Puncture' ? 'selected' : '' }}>Tubing Puncture</option>
                                                    <option value="Casing Cutter" {{ $old_logrecorded['logRecorded'] == 'Casing Cutter' ? 'selected' : '' }}>Casing Cutter</option>
                                                    <option value="Tubing Cutter" {{ $old_logrecorded['logRecorded'] == 'Tubing Cutter' ? 'selected' : '' }}>Tubing Cutter</option>
                                                    <option value="Packer Setting" {{ $old_logrecorded['logRecorded'] == 'Packer Setting' ? 'selected' : '' }}>Packer Setting</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="non-explosive-logs">
                                                    <option value="Junk Basket(CCL)" {{ $old_logrecorded['logRecorded'] == 'Junk Basket(CCL)' ? 'selected' : '' }}>Junk Basket(CCL)</option>
                                                    <option value="GR-CCL" {{ $old_logrecorded['logRecorded'] == 'GR-CCL' ? 'selected' : '' }}>GR-CCL</option>
                                                    <option value="GR-TCL" {{ $old_logrecorded['logRecorded'] == 'GR-TCL' ? 'selected' : '' }}>GR-TCL</option>
                                                    <option value="SBT-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'SBT-GR-CCL' ? 'selected' : '' }}>SBT-GR-CCL</option>
                                                    <option value="RBT-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'RBT-GR-CCL' ? 'selected' : '' }}>RBT-GR-CCL</option>
                                                    <option value="ULTEX-GR-CCL" {{ $old_logrecorded['logRecorded'] == 'ULTEX-GR-CCL' ? 'selected' : '' }}>ULTEX-GR-CCL</option>
                                                </optgroup>
                                                </optgroup>
                                                <optgroup label="Production Logs" id="PL">
                                                    <option value="Production Log" {{ $old_logrecorded['logRecorded'] == 'Production Log' ? 'selected' : '' }}>Production Log</option>
                                                    <option value="Temperature Log" {{ $old_logrecorded['logRecorded'] == 'Temperature Log' ? 'selected' : '' }}>Temperature Log</option>
                                                </optgroup>
                                                <optgroup label="Open Hole Logs" id="OHL">
                                                <optgroup label="Non-Explosive logs" id="oh-non-explosive-logs">
                                                    <option value="HDIL-ORIT-SP-GR" {{ $old_logrecorded['logRecorded'] == 'HDIL-ORIT-SP-GR' ? 'selected' : '' }}>
                                                        HDIL-ORIT-SP-GR</option>
                                                    <option value="RTEX-ORIT-SP-GR" {{ $old_logrecorded['logRecorded'] == 'RTEX-ORIT-SP-GR' ? 'selected' : '' }}>
                                                        RTEX-ORIT-SP-GR</option>
                                                    <option value="ZDEN-CN-GR" {{ $old_logrecorded['logRecorded'] == 'ZDEN-CN-GR' ? 'selected' : '' }}>ZDEN-CN-GR</option>
                                                    <option value="STAR-ORIT-GR" {{ $old_logrecorded['logRecorded'] == 'STAR-ORIT-GR' ? 'selected' : '' }}>STAR-ORIT-GR</option>
                                                    <option value="XMAC-ORIT-GR" {{ $old_logrecorded['logRecorded'] == 'XMAC-ORIT-GR' ? 'selected' : '' }}>XMAC-ORIT-GR</option>
                                                </optgroup>
                                                <optgroup label="Explosive logs" id="oh-explosive-logs">
                                                    <option value="SWC" {{ $old_logrecorded['logRecorded'] == 'SWC' ? 'selected' : '' }}>SWC</option>
                                                </optgroup>
                                                </optgroup>
                                                <optgroup label="Explosive logs" id="other-explosive-logs">
                                                    <option value="Other Explosive Logs">Other Explosive Logs {{ $old_logrecorded['logRecorded'] == 'Other Explosive Logs' ? 'selected' : '' }}</option>
                                                </optgroup>
                                                <optgroup label="Non-Explosive logs" id="other-non-explosive-logs">
                                                    <option value="Other Non-Explosive Logs" {{ $old_logrecorded['logRecorded'] == 'Other Non-Explosive Logs' ? 'selected' : '' }}>Other Non-Explosive Logs</option>
                                                </optgroup>
                                            </select>
                                            @error('logrecorded.*.logRecorded')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                class='form-label requiredField'>
                                                Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number'
                                                name='logrecorded[{{ $index }}][bottomDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-bottomDepth'
                                                value='{{ $old_logrecorded['bottomDepth'] }}' required>
                                            @error('logrecorded.*.bottomDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topDepth' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topDepth' class='form-label requiredField'>
                                                Top Depth(m)<span class='asteriskField'>*</span> </label>
                                            <input type='number' name='logrecorded[{{ $index }}][topDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topDepth'
                                                value='{{ $old_logrecorded['topDepth'] }}' required>
                                            @error('logrecorded.*.topDepth')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-toolNo' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-toolNo' class='form-label'>Tool
                                                No.</label>
                                            <input type='text' name='logrecorded[{{ $index }}][toolNo]' maxlength='255'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-toolNo'
                                                value='{{ $old_logrecorded['toolNo'] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-logQuality' class='mb-3'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                class='form-label requiredField'>
                                                Log Quality<span class='asteriskField'>*</span> </label>
                                            <input type='text' name='logrecorded[{{ $index }}][logQuality]' maxlength='20'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-logQuality'
                                                value='{{ $old_logrecorded["logQuality"] }}' required>
                                            @error('logrecorded.*.logQuality')
                                                <small class='error'>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-bottomShotDepth'
                                            class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth' class='form-label'>
                                                Bottom Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][bottomShotDepth]'
                                                step='any' class='numberinput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-bottomShotDepth'
                                                value='{{ $old_logrecorded["bottomShotDepth"] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-topShotDepth' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-topShotDepth' class='form-label'>
                                                Top Shot Depth(m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][topShotDepth]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-topShotDepth'
                                                value='{{ $old_logrecorded["topShotDepth"] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-charge' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-charge' class='form-label'>Charge
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][charge]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-charge'
                                                value='{{ $old_logrecorded["charge"] }}'>
                                                <option value='' {{ $old_logrecorded["charge"] == '' ? 'selected' : '' }}>--- Select
                                                    Charge type ---</option>
                                                @foreach ($explosivelists as $explosives)
                                                    <option value='{{ $explosives }}' {{ $old_logrecorded['charge'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['charge'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-chargeNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-chargeNo' class='form-label'>
                                                Charge Qty.(Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][chargeNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-chargeNo'
                                                value='{{ $old_logrecorded["chargeNo"] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChord' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChord' class='form-label'>Prima Chord
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][primaChord]'
                                                class='textinput textInput form-control'
                                                id='id_logmodel_set-{{ $index + 1 }}-primaChord'>
                                                <option value='' {{ $old_logrecorded['primaChord'] == '' ? 'selected' : '' }}>---
                                                    Select Prima Chord type ---</option>
                                                @foreach ($primachordlists as $primachord)
                                                    <option value='{{ $primachord }}' {{ $old_logrecorded['primaChord'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['primaChord'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-primaChordQty' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-primaChordQty' class='form-label'>
                                                P/C Length (m)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][primaChordQty]' step='any'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-primaChordQty'
                                                value='{{ $old_logrecorded["primaChordQty"] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuse' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuse' class='form-label'>Fuse
                                                Type</label>
                                            <select name='logrecorded[{{ $index }}][fuse]' maxlength='20'
                                                class='textinput textInput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuse'>
                                                <option value='' {{ $old_logrecorded['fuse'] == '' ? 'selected' : '' }}>
                                                    --- Select Detonator type ---</option>
                                                @foreach ($detonatorlists as $detonator)
                                                    <option value='{{ $detonator }}' {{ $old_logrecorded['fuse'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                @endforeach
                                                <option value='NA' {{ $old_logrecorded['fuse'] == 'NA' ? 'selected' : '' }}>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fuseNo' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fuseNo' class='form-label'>
                                                Fuse Qty. (Nos.)
                                            </label> <input type='number' name='logrecorded[{{ $index }}][fuseNo]'
                                                class='numberinput form-control' id='id_logmodel_set-{{ $index + 1 }}-fuseNo'
                                                value='{{ $old_logrecorded["fuseNo"] }}'>
                                        </div>
                                        <div id='div_logmodel_set-{{ $index + 1 }}-fMf' class='mb-3 explosive-job d-none'>
                                            <label for='id_logmodel_set-{{ $index + 1 }}-fMf' class='form-label'>F/MF</label>
                                            <select name='logrecorded[{{ $index }}][fMf]' class='select form-select'
                                                id='id_logmodel_set-{{ $index + 1 }}-fMf'>
                                                <option value='' {{ $old_logrecorded['fMf'] == '' ? 'selected' : ''}}>
                                                    ---------</option>
                                                <option value='F' {{ $old_logrecorded['fMf'] == 'F' ? 'selected' : ''}}>F
                                                </option>
                                                <option value='MF' {{ $old_logrecorded['fMf'] == 'MF' ? 'selected' : ''}}>
                                                    MF</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class='log-form' id='id-log-form_1'>
                                    <div id='div_logmodel_set-1-runNo' class='mb-3'>
                                        <label for='id_logmodel_set-1-runNo' class='form-label requiredField'>Run
                                            No.<span class='asteriskField'>*</span> </label>
                                        <input type='number' name='logrecorded[0][runNo]' class='numberinput form-control'
                                            id='id_logmodel_set-1-runNo' value='1' required>
                                        @error('logrecorded.*.runNo')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-logRecorded' class='mb-3'>
                                        <label for='id_logmodel_set-1-logRecorded' class='form-label requiredField'>
                                            Type of Logs Recorded<span class='asteriskField'>*</span> </label>
                                        <select name='logrecorded[0][logRecorded]'
                                            class='textinput textInput form-control mb-3 logsRecorded'
                                            id='id_logmodel_set-1-logRecorded' required>
                                            <option value="" selected disabled>--- Select Recorded Log ---</option>
                                            <optgroup label="Cased Hole logs" id="CH">
                                            <optgroup label="Explosive logs" id="explosive-logs">
                                                <option value="Perforation(CCL)">Perforation(CCL)</option>
                                                <option value="TTP(CCL)">TTP(CCL)</option>
                                                <option value="Bridge Plug(CCL)">Bridge Plug(CCL)</option>
                                                <option value="Tubing Puncture">Tubing Puncture</option>
                                                <option value="Casing Cutter">Casing Cutter</option>
                                                <option value="Tubing Cutter">Tubing Cutter</option>
                                                <option value="Packer Setting">Packer Setting</option>
                                            </optgroup>
                                            <optgroup label="Non-Explosive logs" id="non-explosive-logs">
                                                <option value="Junk Basket(CCL)">Junk Basket(CCL)</option>
                                                <option value="GR-CCL">GR-CCL</option>
                                                <option value="GR-TCL">GR-TCL</option>
                                                <option value="SBT-GR-CCL">SBT-GR-CCL</option>
                                                <option value="RBT-GR-CCL">RBT-GR-CCL</option>
                                                <option value="ULTEX-GR-CCL">ULTEX-GR-CCL</option>
                                            </optgroup>
                                            </optgroup>
                                            <optgroup label="Production Logs" id="PL">
                                                <option value="Production Log">Production Log</option>
                                                <option value="Temperature Log">Temperature Log</option>
                                            </optgroup>
                                            <optgroup label="Open Hole Logs" id="OHL">
                                            <optgroup label="Non-Explosive logs" id="oh-non-explosive-logs">
                                                <option value="HDIL-ORIT-SP-GR">HDIL-ORIT-SP-GR</option>
                                                <option value="RTEX-ORIT-SP-GR">RTEX-ORIT-SP-GR</option>
                                                <option value="ZDEN-CN-GR">ZDEN-CN-GR</option>
                                                <option value="STAR-ORIT-GR">STAR-ORIT-GR</option>
                                                <option value="XMAC-ORIT-GR">XMAC-ORIT-GR</option>
                                            </optgroup>
                                            <optgroup label="Explosive logs" id="oh-explosive-logs">
                                                <option value="SWC">SWC</option>
                                            </optgroup>
                                            </optgroup>
                                            <optgroup label="Explosive logs" id="other-explosive-logs">
                                                <option value="Other Explosive Logs">Other Explosive Logs</option>
                                            </optgroup>
                                            <optgroup label="Non-Explosive logs" id="other-non-explosive-logs">
                                                <option value="Other Non-Explosive Logs">Other Non-Explosive Logs</option>
                                            </optgroup>
                                        </select>
                                        @error('logrecorded.*.logRecorded')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-bottomDepth' class='mb-3'>
                                        <label for='id_logmodel_set-1-bottomDepth' class='form-label requiredField'>
                                            Bottom Depth(m)<span class='asteriskField'>*</span> </label> <input type='number'
                                            name='logrecorded[0][bottomDepth]' step='any' class='numberinput form-control'
                                            id='id_logmodel_set-1-bottomDepth' value="{{ old('bottomDepth') }}" required>
                                        @error('logrecorded.*.bottomDepth')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-topDepth' class='mb-3'>
                                        <label for='id_logmodel_set-1-topDepth' class='form-label requiredField'>
                                            Top Depth(m)<span class='asteriskField'>*</span> </label>
                                        <input type='number' name='logrecorded[0][topDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-topDepth'
                                            value='{{ old("topDepth") }}' required>
                                        @error('logrecorded.*.topDepth')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-toolNo' class='mb-3'>
                                        <label for='id_logmodel_set-1-toolNo' class='form-label'>Tool No.</label>
                                        <input type='text' name='logrecorded[0][toolNo]' maxlength='255'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-toolNo'
                                            value='{{ old("toolNo") }}'>
                                    </div>
                                    <div id='div_logmodel_set-1-logQuality' class='mb-3'>
                                        <label for='id_logmodel_set-1-logQuality' class='form-label requiredField'>
                                            Log Quality<span class='asteriskField'>*</span> </label>
                                        <input type='text' name='logrecorded[0][logQuality]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-logQuality'
                                            value='{{ old("logQuality") }}' required>
                                        @error('logrecorded.*.logQuality')
                                            <small class='error'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div id='div_logmodel_set-1-bottomShotDepth' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-bottomShotDepth' class='form-label'>
                                            Bottom Shot Depth(m)
                                        </label> <input type='number' name='logrecorded[0][bottomShotDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-bottomShotDepth'
                                            value="{{ old('bottomShotDepth') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-topShotDepth' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-topShotDepth' class='form-label'>
                                            Top Shot Depth(m)
                                        </label> <input type='number' name='logrecorded[0][topShotDepth]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-topShotDepth'
                                            value="{{ old('topShotDepth') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-charge' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-charge' class='form-label'>Charge Type</label>
                                        <select name='logrecorded[0][charge]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-charge'>
                                            <option value=''>--- Select Charge type ---</option>
                                            @foreach ($explosivelists as $explosives)
                                                <option value='{{ $explosives }}'>{{ $explosives }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-chargeNo' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-chargeNo' class='form-label'>
                                            Charge Qty.(Nos.)
                                        </label> <input type='number' name='logrecorded[0][chargeNo]'
                                            class='numberinput form-control' id='id_logmodel_set-1-chargeNo'
                                            value="{{ old('chargeNo') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-primaChord' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-primaChord' class='form-label'>Prima Chord
                                            Type</label>
                                        <select name='logrecorded[0][primaChord]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-primaChord'>
                                            <option value=''>--- Select Prima Chord type ---</option>
                                            @foreach ($primachordlists as $primachord)
                                                <option value='{{ $primachord }}'>{{ $primachord }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-primaChordQty' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-primaChordQty' class='form-label'>
                                            P/C Length (m)
                                        </label> <input type='number' name='logrecorded[0][primaChordQty]' step='any'
                                            class='numberinput form-control' id='id_logmodel_set-1-primaChordQty'
                                            value="{{ old('primaChordQty') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-fuse' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fuse' class='form-label'>Fuse Type</label>
                                        <select name='logrecorded[0][fuse]' maxlength='20'
                                            class='textinput textInput form-control' id='id_logmodel_set-1-fuse'>
                                            <option value=''>--- Select Detonator type ---</option>
                                            @foreach ($detonatorlists as $detonator)
                                                <option value='{{ $detonator }}'>{{ $detonator }}</option>
                                            @endforeach
                                            <option value='NA'>NA</option>
                                        </select>
                                    </div>
                                    <div id='div_logmodel_set-1-fuseNo' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fuseNo' class='form-label'>
                                            Fuse Qty. (Nos.)
                                        </label> <input type='number' name='logrecorded[0][fuseNo]'
                                            class='numberinput form-control' id='id_logmodel_set-1-fuseNo'
                                            value="{{ old('fuseNo') }}">
                                    </div>
                                    <div id='div_logmodel_set-1-fMf' class='mb-3 explosive-job d-none'>
                                        <label for='id_logmodel_set-1-fMf' class='form-label'>F/MF</label>
                                        <select name='logrecorded[0][fMf]' class='select form-select'
                                            id='id_logmodel_set-1-fMf'>
                                            <option value='' {{ old('fMf') == '' ? 'selected' : ''}}>---------
                                            </option>
                                            <option value='F' {{ old('fMf') == 'F' ? 'selected' : ''}}>F</option>
                                            <option value='MF' {{ old('fMf') == 'MF' ? 'selected' : ''}}>MF</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button class='btn btn-success btn-lg' id='add_more_logs' type='button'><i
                                class='fa fa-plus-circle'></i></button>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100'>
                <div class='card-body px-0 pt-0' id="div-swc">
                    <fieldset id='swc'>
                        <div class='form-card step'>
                            <h2 class='card-header rounded border-0 fs-title text-center' id='swctitle'>Side
                                Wall Core</h2>
                            <div id='div_id_attempted' class='mb-3'>
                                <label for='id_attempted' class='form-label'>
                                    Attempted
                                </label>
                                <input type='text' name='attempted' placeholder='Attempted'
                                    class='textinput textInput form-control' id='id_attempted'
                                    value="{{ old('attempted')  }}">
                            </div>
                            <div id='div_id_recovered' class='mb-3'>
                                <label for='id_recovered' class='form-label'>
                                    Recovered
                                </label>
                                <input type='text' name='recovered' placeholder='Recovered'
                                    class='textinput textInput form-control' id='id_recovered'
                                    value="{{ old('recovered')  }}">
                            </div>
                            <div id='div_id_missFire' class='mb-3'>
                                <label for='id_missFire' class='form-label'>
                                    Miss Fire
                                </label>
                                <input type='text' name='missFire' placeholder='Miss Fire'
                                    class='textinput textInput form-control' id='id_missFire'
                                    value="{{ old('missFire') }}">
                            </div>
                            <div id='div_id_barrelLost' class='mb-3'>
                                <label for='id_barrelLost' class='form-label'>
                                    Barrel Lost
                                </label>
                                <input type='text' name='barrelLost' placeholder='Barrel Lost'
                                    class='textinput textInput form-control' id='id_barrelLost'
                                    value="{{ old('barrelLost')  }}">
                            </div>
                            <div id='div_id_emptyBarrel' class='mb-3'>
                                <label for='id_emptyBarrel' class='form-label'>
                                    Empty Barrel
                                </label>
                                <input type='text' name='emptyBarrel' placeholder='Empty Barrel'
                                    class='textinput textInput form-control' id='id_emptyBarrel'
                                    value="{{ old('emptyBarrel')  }}">
                            </div>
                            <div id='div_id_chargeUsed' class='mb-3'>
                                <label for='id_chargeUsed' class='form-label'>
                                    Charge Used
                                </label>
                                <input type='text' name='chargeUsed' placeholder='Charge Used'
                                    class='textinput textInput form-control' id='id_chargeUsed'
                                    value="{{ old('chargeUsed')  }}">
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class='card-body px-0' id="div-explosive">
                    <fieldset id='explosiveinfo'>
                        <div class='form-card step container'>
                            <h2 class='card-header rounded border-0 fs-title text-center'>Explosive Details</h2>
                            <div class="explosive-wrapper">
                                <div class='explosive-form' id='id-explosive-form_1'>
                                    @if (old('explosive'))
                                        @foreach (old('explosive') as $index => $old_explosive)
                                            <h2 class='my-3'>Explosive - {{ $index + 1 }}</h2>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-explosives' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-explosives'
                                                    class='form-label'>Charges</label>
                                                <select name='explosive[{{ $index }}][explosive]'
                                                    placeholder='--- Select Charge type ---' class='select form-select'
                                                    id='id_explosive_set-{{ $index + 1 }}-explosives'>
                                                    <option value='' {{ $old_explosive['explosive'] == '' ? 'selected' : '' }}>---
                                                        Select Charge type ---</option>
                                                    @foreach ($explosivelists as $explosives)
                                                        <option value='{{ $explosives }}' {{ $old_explosive['explosive'] == $explosives ? 'selected' : '' }}>{{ $explosives }}</option>
                                                    @endforeach
                                                    @foreach ($primachordlists as $primachord)
                                                        <option value='{{ $primachord }}' {{ $old_explosive['explosive'] == $primachord ? 'selected' : '' }}>{{ $primachord }}</option>
                                                    @endforeach
                                                    @foreach ($detonatorlists as $detonator)
                                                        <option value='{{ $detonator }}' {{ $old_explosive['explosive'] == $detonator ? 'selected' : '' }}>{{ $detonator }}</option>
                                                    @endforeach
                                                    <option value='NA' {{ $old_explosive['explosive'] == 'NA' ? 'selected' : '' }}>NA</option>
                                                </select>
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-issued' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-issued' class='form-label'>
                                                    Charge Issued</label>
                                                <input type='text' name='explosive[{{ $index }}][issued]' placeholder='Issued'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-issued'
                                                    value="{{ $old_explosive['issued'] }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-used' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-used' class='form-label'>Charge
                                                    Used</label>
                                                <input type='text' name='explosive[{{ $index }}][used]' placeholder='Used'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-used'
                                                    value="{{ $old_explosive['used'] }}">
                                            </div>
                                            <div id='div_id_explosive_set-{{ $index + 1 }}-returned' class='mb-3'>
                                                <label for='id_explosive_set-{{ $index + 1 }}-returned' class='form-label'>Charge
                                                    Returned</label>
                                                <input type='text' name='explosive[{{ $index }}][returned]' placeholder='Returned'
                                                    class='textinput textInput form-control'
                                                    id='id_explosive_set-{{ $index + 1 }}-returned'
                                                    value="{{ $old_explosive['returned'] }}">
                                            </div>
                                        @endforeach
                                    @else
                                        <h2 class='my-3'>Explosive - 1</h2>
                                        <div id='div_id_explosive_set-1-explosives' class='mb-3'>
                                            <label for='id_explosive_set-1-explosives' class='form-label'>Charges</label>
                                            <select name='explosive[0][explosive]' placeholder='--- Select Charge type ---'
                                                class='select form-select' id='id_explosive_set-1-explosives'>
                                                <option value=''>--- Select Charge type ---</option>
                                                @foreach ($explosivelists as $explosives)
                                                    <option value='{{ $explosives }}'>{{ $explosives }}</option>
                                                @endforeach
                                                @foreach ($primachordlists as $primachord)
                                                    <option value='{{ $primachord }}'>{{ $primachord }}</option>
                                                @endforeach
                                                @foreach ($detonatorlists as $detonator)
                                                    <option value='{{ $detonator }}'>{{ $detonator }}</option>
                                                @endforeach
                                                <option value='NA'>NA</option>
                                            </select>
                                        </div>
                                        <div id='div_id_explosive_set-1-issued' class='mb-3'>
                                            <label for='id_explosive_set-1-issued' class='form-label'> Charge
                                                Issued</label>
                                            <input type='text' name='explosive[0][issued]' placeholder='Issued'
                                                class='textinput textInput form-control' id='id_explosive_set-1-issued'>
                                        </div>
                                        <div id='div_id_explosive_set-1-used' class='mb-3'>
                                            <label for='id_explosive_set-1-used' class='form-label'>Charge
                                                Used</label>
                                            <input type='text' name='explosive[0][used]' placeholder='Used'
                                                class='textinput textInput form-control' id='id_explosive_set-1-used'>
                                        </div>
                                        <div id='div_id_explosive_set-1-returned' class='mb-3'>
                                            <label for='id_explosive_set-1-returned' class='form-label'>Charge
                                                Returned</label>
                                            <input type='text' name='explosive[0][returned]' placeholder='Returned'
                                                class='textinput textInput form-control' id='id_explosive_set-1-returned'>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <button class='btn btn-success btn-lg' id='add_more_explosive' type='button'><i
                                    class='fa fa-plus-circle'></i></button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
        <button type='button' name='next' id="third-step" class='next btn btn-info'>Next Step</button>
    </div>
    <div class='container-fluid pb-3 form-step' style='display: none; position: relative; opacity: 0;'>
        <div class='d-flex'>
            <div class='card h-100'>
                <fieldset id='hseinfo'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>HSE</h2>
                        <div id='div_id_permitType' class='mb-3'>
                            <label for='id_permitType' class='form-label requiredField'>
                                Permit Type<span class='asteriskField'>*</span>
                            </label><select name='permitType' placeholder='--- Select Permit Type ---'
                                class='select form-select' id='id_permitType' required>
                                <option value='' {{ old('permitType') == '' ? 'selected' : ''}}>--- Select Permit
                                    Type ---</option>
                                <option value='Cold Work Permit' {{ old('permitType') == 'Cold Work Permit' ? 'selected' : 'selected'}}>Cold Work Permit</option>
                                <option value='Hot Work Permit' {{ old('permitType') == 'Hot Work Permit' ? 'selected' : ''}}>
                                    Hot Work Permit</option>
                                <option value='NA' {{ old('permitType') == 'NA' ? 'selected' : ''}}>Not Applicable
                                </option>
                            </select>
                            @error('permitType')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_permitNo' class='mb-3'>
                            <label for='id_permitNo' class='form-label'>
                                Permit No.<span class='asteriskField'>*</span>
                            </label>
                            <input type='text' name='permitNo' placeholder='Work Permit No.'
                                class='textinput textInput form-control' id='id_permitNo' value='{{ old("permitNo") }}'
                                required>
                            @error('permitNo')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_permitWork' class='mb-3'>
                            <label for='id_permitWork' class='form-label requiredField'>
                                Form of permit to work<span class='asteriskField'>*</span>
                            </label><select name='permitWork' placeholder='Form of permit to work'
                                class='select form-select' id='id_permitWork' required>
                                <option value='' {{ old('permitWork') == '' ? 'selected' : ''}}>--- Select Form of
                                    permit to work ---</option>
                                <option value='1' {{ old('permitWork') == '1' ? 'selected' : 'selected'}}>Yes
                                </option>
                                <option value='0' {{ old('permitWork') == '0' ? 'selected' : ''}}>No</option>
                            </select>
                            @error('permitWork')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_elecLockout' class='mb-3' style='display: none;'>
                            <label for='id_elecLockout' class='form-label'>
                                Electrical Lock out
                            </label><select name='elecLockout' placeholder='Electrical Lock out' class='select form-select'
                                id='id_elecLockout'>
                                <option value='' {{ old('elecLockout') == '' ? 'selected' : ''}}>--- Select
                                    Electrical Lock out ---</option>
                                <option value='1' {{ old('elecLockout') == '1' ? 'selected' : ''}}>Yes</option>
                                <option value='0' {{ old('elecLockout') == '0' ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id='div_id_elecLockoutNo' class='mb-3' style='display: none;'>
                            <label for='id_elecLockoutNo' class='form-label'>
                                Electrical Lock out No.
                            </label>
                            <input type='text' name='elecLockoutNo' placeholder='Electrical Lock out No.'
                                class='textinput textInput form-control' id='id_elecLockoutNo'
                                value="{{ old('elecLockoutNo') }}">
                        </div>
                        <div id='div_id_safetyMeeting' class='mb-3'>
                            <label for='id_safetyMeeting' class='form-label requiredField'>
                                Safety Meeting conducted<span class='asteriskField'>*</span>
                            </label><select name='safetyMeeting' placeholder='Safety Meeting conducted'
                                class='select form-select' id='id_safetyMeeting' required>
                                <option value='' {{ old('safetyMeeting') == '' ? 'selected' : ''}}>--- Select
                                    Safety Meeting conducted ---</option>
                                <option value='1' {{ old('safetyMeeting') == '1' ? 'selected' : 'selected'}}>Yes
                                </option>
                                <option value='0' {{ old('safetyMeeting') == '0' ? 'selected' : ''}}>No</option>
                            </select>
                            @error('safetyMeeting')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_jobCloseMeeting' class='mb-3'>
                            <label for='id_jobCloseMeeting' class='form-label requiredField'>
                                Job Close up Meeting<span class='asteriskField'>*</span>
                            </label><select name='jobCloseMeeting' placeholder='Job Close up Meeting'
                                class='select form-select' id='id_jobCloseMeeting' required>
                                <option value='' {{ old('jobCloseMeeting') == '' ? 'selected' : '' }}>--- Select
                                    Job Close up Meeting ---</option>
                                <option value='1' {{ old('jobCloseMeeting') == '1' ? 'selected' : 'selected' }}>
                                    Yes</option>
                                <option value='0' {{ old('jobCloseMeeting') == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('jobCloseMeeting')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_nearMiss' class='mb-3'>
                            <label for='id_nearMiss' class='form-label requiredField'>
                                Near Miss<span class='asteriskField'>*</span>
                            </label><select name='nearMiss' placeholder='Near Miss' class='select form-select'
                                id='id_nearMiss' required>
                                <option value='' {{ old('nearMiss') == '' ? 'selected' : '' }}>--- Select Near
                                    Miss ---</option>
                                <option value='1' {{ old('nearMiss') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value='0' {{ old('nearMiss') == '0' ? 'selected' : 'selected' }}>No
                                </option>
                            </select>
                            @error('nearMiss')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_nearMissDesc' class='mb-3' style='display: none;'>
                            <label for='id_nearMissDesc' class='form-label'>
                                Nearmiss Description
                            </label>
                            <textarea name='nearMissDesc' cols='40' rows='10' type='text'
                                placeholder='Nearmiss Description(if any)' class='textarea form-control'
                                id='id_nearMissDesc' value=''>{{ old('nearMissDesc') }}</textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class='card h-100'>
                <fieldset id='jobstatus'>
                    <div class='form-card step'>
                        <h2 class='card-header rounded border-0 fs-title text-center'>Job Status</h2>
                        <div id='div_id_jobStatus' class='mb-3'>
                            <label for='id_jobStatus' class='form-label requiredField'>
                                Job Status<span class='asteriskField'>*</span>
                            </label><select name='jobStatus' placeholder='Job Status' class='select form-select'
                                id='id_jobStatus'>
                                <option value='' {{ old('jobStatus') == '' ? 'selected' : '' }}>--- Select Job Status ---
                                </option>
                                <option value='Complete' {{ old('jobStatus') == 'Complete' ? 'selected' : 'selected' }}>
                                    Complete</option>
                                <option value='Incomplete' {{ old('jobStatus') == 'Incomplete' ? 'selected' : '' }}>Incomplete
                                </option>
                                <option value='Continued' {{ old('jobStatus') == 'Continued' ? 'selected' : '' }}>Continued
                                </option>
                                <option value='Well Problem' {{ old('jobStatus') == 'Well Problem' ? 'selected' : '' }}>Well
                                    Problem</option>
                                <option value='Not Feasible' {{ old('jobStatus') == 'Not Feasible' ? 'selected' : '' }}>Not
                                    Feasible</option>
                            </select>
                            @error('jobStatus')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_remarks' class='mb-3'>
                            <label for='id_remarks' class='form-label requiredField'>
                                Remarks<span class='asteriskField'>*</span>
                            </label>
                            <textarea name='remarks' cols='40' rows='10' type='text' placeholder='Remarks'
                                class='textarea form-control' id='id_remarks'>{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <small class='error'>{{ $message }}</small>
                            @enderror
                        </div>
                        <div id='div_id_objective' class='mb-3' style='display: none;'>
                            <label for='id_objective' class='form-label'>
                                Objective
                            </label>
                            <textarea name='objective' cols='40' rows='10' type='text'
                                placeholder='Write the objective of PL' class='textarea form-control' id='id_objective'
                                value=''>{{ old('objective') }}</textarea>
                        </div>
                        <div id='div_id_observations' class='mb-3' style='display: none;'>
                            <label for='id_observations' class='form-label'>
                                Observations
                            </label>
                            <textarea name='observations' cols='40' rows='10' type='text'
                                placeholder='Write the Observations/Findings from this PL' class='textarea form-control'
                                id='id_observations' value=''>{{ old('observations') }}</textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="mt-3">
            <button type='button' name='previous' class='previous btn btn-secondary'>Previous</button>
            <button type="submit" name="action" value="save_draft" class="btn btn-secondary">
                <i class="fas fa-save"></i> Save as Draft
            </button>

            <button type="submit" name="action" value="submit" class="btn btn-primary ml-2">
                <i class="fas fa-check-circle"></i> Submit for Review
            </button>

            <a href="{{ route('jcr.index') }}" class="btn btn-outline-danger ml-2">Cancel</a>
        </div>
    </div>
@endisset