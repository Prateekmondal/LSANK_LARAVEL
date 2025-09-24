<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 60%">Upon-Arrival Checklist Items</th>
                <th style="width: 10%">Status</th>
                <th style="width: 25%">Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'Remove any rig wiring that might contact the cable while rigging up cable',
                'Install the Casing - to Rig Voltage Monitor. Check for residual voltage. Do not process with operations if the value exceeds 0.25V. Investigate the cause and reduce the level to 0.25V',
                'If the residual voltage is less than 0.25V AC-DC, install safety grounding cable between unit, rig and wellhead. Leave the voltage monitor connected between the rig and the casing',
                'Turn off the AC Generator of Truck / Unit.',
                'Engage the Safety Switch to SAFE mode and remove the key. The key shall be in the personal custody of Incharge Logging.',
                'Does the Casing to Rig voltage monitor reads less than 0.25V DC-AC?',
                'Clear all personnel from the line of fire.',
                'Attach the explosives device to the cable. At this time, the Safety key must be in the possession of the Incharge Logging and till the time the tool/ perforator is at least 100m below ground level.',
                'Attach the cable to the gun before arming the gun.',
                'Keeping the detonator in Safety Tube, test it with a Safety Meter.',
                'With the detonator in safety tube arm the gun electrically. First connect the ground lead.',
                'With the detonator in safety tube arm the gun electrically. Then connect the central lead.',
                'Arm the gun ballistically last by crimping the detonator to the detonating cord.',
                'Lift the gun and lower it into the well safely and quickly.',
                'After the gun string is at least 100m below the ground level, turn on the power generators etc.',
                'Disengage the safety lock; insert the cable jack to the collar locator socket for depth tie up.',
                'Tie in, position gun and shoot',
                'Switch off the shooting circuit and bring back the cable jack to collar socket and/pull up the gun.',
                'While pulling out of the hole. stop at least 100m below the ground level and return cable jack to SAFE mode and remove the key and keep with in-charge Logging.',
                'After any shooting. successful or not and when the gun is at around 30m below ground sea level proceed the same ways ie before running it',
                'On surface, all gun shall be safely relieved of any trapped pressure gases.',
                'If misfired, ensure temp. of gun below 100°C and disarm the gun by disconnectng the detonator. First cut the prima cord from the detonator with a shard blade.',
                'If misfired, ensure temp. of gun below 100°C and disarm the gun by disconnectng the detonator. Disconnect the central lead and disconnect the ground lead.',
                'Police the area for detonator cord remnants. unused/damaged charges etc. And store then in the explosives remnant box and lock.',
                'Store all unused detonators in the detonator carving case and lock.',
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