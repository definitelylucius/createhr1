<!DOCTYPE html>
<html>
<head>
    <title>Application Status Update</title>
</head>
<body>
    <h2>Application Status Update</h2>
    <p>Dear {{ $application->name }},</p>
    <p>Thank you for applying for the <strong>{{ $application->job->title }}</strong> position.</p>
    <p>After reviewing your application, we regret to inform you that you have not been selected to proceed further.</p>
    <p>We appreciate your interest and encourage you to apply for future opportunities.</p>
    <p>Best regards,</p>
    <p><strong>HR Team</strong></p>
</body>
</html>
