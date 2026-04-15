<!DOCTYPE html>
<html>
<head>
    <title>Job Carried Out Report Signed Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .details-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✓ Job Carried Out Report Signed Successfully</h2>
        </div>

        <p>Dear {{ $timeRegister->rig_representative_name }},</p>

        <p>Your signature has been recorded for the Job Carried Out Report. Please find the signed copy of the report attached below.</p>

        <div class="section">
            <h3>Report Details</h3>
            <div class="details-box">
                <div class="detail-row">
                    <div class="detail-label">Well No: {{ $timeRegister->well_no }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Rig No: {{ $timeRegister->rig_no }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Logging Unit No: {{ $timeRegister->logging_unit_no }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Indent No: {{ $timeRegister->indent_no }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Timeline</h3>
            <div class="details-box">
                @if($timeRegister->well_indented_date && $timeRegister->well_indented_time)
                <div class="detail-row">
                    <div class="detail-label">Well Indented: {{ $timeRegister->well_indented_date->format('Y-m-d') }} {{ date('H:i', strtotime($timeRegister->well_indented_time)) }}</div>
                </div>
                @endif
                @if($timeRegister->well_taken_up_date && $timeRegister->well_taken_up_time)
                <div class="detail-row">
                    <div class="detail-label">Well Taken Up: {{ $timeRegister->well_taken_up_date->format('Y-m-d') }} {{ date('H:i', strtotime($timeRegister->well_taken_up_time)) }}</div>
                </div>
                @endif
                @if($timeRegister->well_handed_over_date && $timeRegister->well_handed_over_time)
                <div class="detail-row">
                    <div class="detail-label">Well Handed Over: {{ $timeRegister->well_handed_over_date->format('Y-m-d') }} {{ date('H:i', strtotime($timeRegister->well_handed_over_time)) }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="section">
            <h3>Your Signature Details</h3>
            <div class="details-box">
                <div class="detail-row">
                    <div class="detail-label">Name: {{ $timeRegister->rig_representative_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Designation: {{ $timeRegister->rig_representative_designation }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Signed At: {{ $timeRegister->rig_representative_signed_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <p>Thank you for completing the signature process. If you have any questions or need clarification, please contact the logging team.</p>

        <hr>

        <p><strong>Best regards,</strong></p>
        <p>
            <strong>Well Logging Services</strong><br>
            Oil and Natural Gas Corporation Limited<br>
            Ankleshwar Asset, Ankleshwar-393010
        </p>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this address.</p>
            <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
