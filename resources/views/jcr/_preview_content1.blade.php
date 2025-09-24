<div class="row">
    <div class="col-md-6">
        <h4 class="text-primary">Basic Information</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Field Name</th>
                    <td>{{ $jcr->fieldName }}</td>
                </tr>
                <tr>
                    <th>Well No</th>
                    <td>{{ $jcr->wellNo }}</td>
                </tr>
                <tr>
                    <th>Job Date</th>
                    <td>{{ $jcr->jobDate }}</td>
                </tr>
                <tr>
                    <th>Job No</th>
                    <td>{{ $jcr->jobNo }}</td>
                </tr>
                <tr>
                    <th>Well Owner</th>
                    <td>{{ $jcr->wellOwner }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <h4 class="text-primary">Technical Details</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Depth (Driller)</th>
                    <td>{{ $jcr->depthDriller }}</td>
                </tr>
                <tr>
                    <th>Depth (Logger)</th>
                    <td>{{ $jcr->depthLogger }}</td>
                </tr>
                <tr>
                    <th>Casing Size</th>
                    <td>{{ $jcr->casingSize }}</td>
                </tr>
                <tr>
                    <th>Bit Size</th>
                    <td>{{ $jcr->bitSize }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<hr>

<h4 class="text-primary">Personnel</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jcr->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr>

<h4 class="text-primary">Logs Recorded</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Run No</th>
                <th>Log Recorded</th>
                <th>Bottom Depth</th>
                <th>Top Depth</th>
                <th>Log Quality</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jcr->logs as $log)
            <tr>
                <td>{{ $log->runNo }}</td>
                <td>{{ $log->logRecorded }}</td>
                <td>{{ $log->bottomDepth }}</td>
                <td>{{ $log->topDepth }}</td>
                <td>{{ $log->logQuality }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr>

<h4 class="text-primary">Explosives Used</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Run No</th>
                <th>Attempted</th>
                <th>Recovered</th>
                <th>Miss Fire</th>
                <th>Charge Used</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jcr->explosives as $explosive)
            <tr>
                <td>{{ $explosive->runNo }}</td>
                <td>{{ $explosive->attempted }}</td>
                <td>{{ $explosive->recovered }}</td>
                <td>{{ $explosive->missFire }}</td>
                <td>{{ $explosive->chargeUsed }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($jcr->remarks)
<hr>
<h4 class="text-primary">Remarks</h4>
<div class="p-3 bg-light rounded">
    {{ $jcr->remarks }}
</div>
@endif