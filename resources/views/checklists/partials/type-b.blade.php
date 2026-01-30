<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 60%">On-Site Checklist Items</th>
                <th style="width: 10%">Status</th>
                <th style="width: 25%">Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'Has the following sign boards in Hindi, English and regional languages been displayed at appropriate place in the well site area?',
                'Danger Zone, Explosive in use, turn off all radios.',
                'Danger Zone, Explosive in use, turn off all generators.',
                'Danger Zone, Explosive in use, turn off all welding machines.',
                'Have all the arc/gas welding machines been turned off?',
                'Have the Radio Transmitters/Receivers phone within 300m been turned off?',
                'is there a large radio/television station within 4 Km (if yes, contact the Head Logging base for insturctions)?',
                'Have all generators been turned off?',
                'Has the cathodic protection system been turned off?',
                'Any state electiricity board power connection to SRP within 30m from wellhead has been switched off and ends connection insulated?',
                'Confirm that there is no high tension linne (over ground and underground) in the vicinity of perforation job site. If it exists, has it been disconnected and insulated?',
                'Has the defective rig wiring, if any, been removed?',
                'Is there any warning for sand storm/thunder storm? In case of any storm suspend operations till the storm subsides.',
                'Have all open fire, if any, in the vicinity of the well site been removed?',
                'Has the WLS safety officer visited the site and his recommendations complied with?',
                'Have the Mobile phones been switched off?',
                'Is the well site free from obstacles and slippery areas? (Specially catwalk, logging unit parking place, etc.)',
                'Is the well scrapped throughly after last cementation/squeeze perforation job?',
                'Is the well circulated throughly?',
                'Is the well filled with the fluid?',
                'Is the BOP tested? - time when last tested',
                'Whether pressure control equipment is used',
                'Are lubricators and hydraulic pack offs function tested?',
                'Wireline BOP is function tested?',
                'Are there any other hazards coming up in the way of the safe working practices?',
                'Has the logging unit been parked at a safe distance? (to be confirmed by Logging Crew Chief)',
                ] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item }}</td>
                    <td>
                        <select name="checklist_data[{{ $index }}][status]" class="form-select form-select-sm">
                            @switch($index + 1)
                                @case(7)
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                    @break
                                @case(9)
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                    <option value="" selected>N/A</option>
                                    @break
                                @case(13)
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                    @break
                                @case(22)
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                    @break
                                @case(23)
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                    <option value="" selected>N/A</option>
                                    @break
                                @case(24)
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                    <option value="" selected>N/A</option>
                                    @break
                                @case(25)
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                    @break
                            @default
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            @endswitch
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