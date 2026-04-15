<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Carried Out Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header img {
            height: 60px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .header h2 {
            font-size: 14px;
            font-weight: normal;
            margin: 5px 0;
        }
        
        .row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .col {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
            vertical-align: top;
        }
        .col:last-child {
            padding-right: 0;
        }
        .field {
            margin-bottom: 8px;
        }
        .field-label {
            font-weight: bold;
            color: #333;
        }
        .field-value {
            color: #555;
            padding-left: 5px;
        }
        .content-box {
            border: 1px solid #ddd;
            padding: 12px;
            background-color: #fafafa;
            margin-bottom: 10px;
        }
        .content-box p {
            margin: 0;
            white-space: pre-wrap;
        }
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .signature-box h3 {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background-color: #0066cc;
            padding: 8px 10px;
            margin: -15px -15px 12px -15px;
        }
        .signature-box.rig h3 {
            background-color: #28a745;
        }
        .signature-box p {
            margin: 6px 0;
        }
        .signature-box .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-draft {
            background-color: #ffc107;
            color: #000;
        }
        .badge-preview {
            background-color: #17a2b8;
            color: #fff;
        }
        .badge-pending {
            background-color: #007bff;
            color: #fff;
        }
        .badge-completed {
            background-color: #28a745;
            color: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table td {
            padding: 4px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img class="img-fluid" src="{{ public_path('/static/images/ongc.png') }}" style="max-height: 80px;"/>
            <div class="text-center">
                <h1>Oil and Natural Gas Corporation Limited</h1>
                <h2>Well Logging Services, Ankleshwar Asset, Ankleshwar-393010</h2>
                <h1>Job Carried Out Report</h1>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="row">
            <div class="col">
                <div class="field">
                    <span class="field-label">Logging Unit No:</span>
                    <span class="field-value">{{ $timeRegister->logging_unit_no }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Indent No:</span>
                    <span class="field-value">{{ $timeRegister->indent_no }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Well No:</span>
                    <span class="field-value">{{ $timeRegister->well_no }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Rig No:</span>
                    <span class="field-value">{{ $timeRegister->rig_no }}</span>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <span class="field-label">Status:</span>
                    <span class="badge {{ 'badge-' . $timeRegister->status }}">
                        {{ ucfirst(str_replace('_', ' ', $timeRegister->status)) }}
                    </span>
                </div>
                <div class="field">
                    <span class="field-label">Created By:</span>
                    <span class="field-value">{{ $timeRegister->creator->name ?? 'N/A' }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Created At:</span>
                    <span class="field-value">{{ $timeRegister->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Timeline Information -->
        <table>
            <tr>
                <td width="30%"><strong>Well Indented:</strong></td>
                <td>
                    @if($timeRegister->well_indented_time && $timeRegister->well_indented_date)
                    {{ date('H:i', strtotime($timeRegister->well_indented_time)) }} on {{ $timeRegister->well_indented_date->format('Y-m-d') }}
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Well Taken Up:</strong></td>
                <td>
                    @if($timeRegister->well_taken_up_time && $timeRegister->well_taken_up_date)
                    {{ date('H:i', strtotime($timeRegister->well_taken_up_time)) }} on {{ $timeRegister->well_taken_up_date->format('Y-m-d') }}
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Well Handed Over:</strong></td>
                <td>
                    @if($timeRegister->well_handed_over_time && $timeRegister->well_handed_over_date)
                    {{ date('H:i', strtotime($timeRegister->well_handed_over_time)) }} on {{ $timeRegister->well_handed_over_date->format('Y-m-d') }}
                    @else
                    N/A
                    @endif
                </td>
            </tr>
        </table>

        <!-- Job Details -->
        <div class="mb-2"><h4>Job Carried Out:</h4></div>
        <div class="content-box">
            <p>{{ $timeRegister->job_carried_out }}</p>
        </div>

        <!-- Observations -->
        <div class="mb-2"><h4>Observations by Logging Chief:</h4></div>
        <div class="content-box">
            <p>{{ $timeRegister->observations_by_logging_chief }}</p>
        </div>

        @if($timeRegister->rig_representative_observations)
        <div class="mb-2"><h4>Observations by Rig In-Charge:</h4></div>
        <div class="content-box">
            <p>{{ $timeRegister->rig_representative_observations }}</p>
        </div>
        @endif

        <!-- Signatures Section -->
        <div class="signature-section">
            <div class="row">
                <div class="col">
                    <div class="signature-box">
                        <h3>Logging Chief</h3>
                        <p>
                            <span class="label">Name:</span>
                            {{ $timeRegister->logging_chief_name }}
                        </p>
                        <p>
                            <span class="label">Designation:</span>
                            {{ $timeRegister->logging_chief_designation }}
                        </p>
                        <p>
                            <span class="label">Signed At:</span>
                            {{ $timeRegister->logging_chief_signed_at->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                </div>
                <div class="col">
                    @if($timeRegister->rig_representative_signature)
                    <div class="signature-box rig">
                        <h3>Rig Representative Signature</h3>
                        <p>
                            <span class="label">Name:</span>
                            {{ $timeRegister->rig_representative_name }}
                        </p>
                        <p>
                            <span class="label">Designation:</span>
                            {{ $timeRegister->rig_representative_designation }}
                        </p>
                        <p>
                            <span class="label">Signed At:</span>
                            {{ $timeRegister->rig_representative_signed_at->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer">
            <p>This is an electronically generated document. Printed on {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
