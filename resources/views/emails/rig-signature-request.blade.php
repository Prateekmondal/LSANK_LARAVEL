<!DOCTYPE html>
<html>
<head>
    <title>Signature Required - Time Register</title>
</head>
<body>
    <h2>Rig Representative Signature Required</h2>
    
    <p>You have been requested to provide your signature for a Time Register record.</p>
    
    <h3>Record Details:</h3>
    
    <ul>
        <li><strong>Logging Unit No:</strong> {{ $timeRegister->logging_unit_no }}</li>
        <li><strong>Well No:</strong> {{ $timeRegister->well_no }}</li>
        <li><strong>Rig No:</strong> {{ $timeRegister->rig_no }}</li>
        <li><strong>Job Carried Out:</strong> {{ $timeRegister->job_carried_out }}</li>
        <li><strong>Submitted By:</strong> {{ $timeRegister->creator->name }}</li>
    </ul>
    
    <p>Please click the link below to review and sign the record:</p>
    
    <a href="{{ $signatureUrl }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Review and Sign Time Register
    </a>
    
    <p><strong>This link is unique and should not be shared with others.</strong></p>
    
    <hr>
    <p><small>This is an automated message. Please do not reply to this email.</small></p>
</body>
</html>