<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Letter - {{ $employee['name'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Onboarding Letter</h1>

    <p>Dear {{ $employee->name }},</p>

    <p>We are delighted to welcome you to <strong>NexFleet Dynamics</strong>. Congratulations on your new role as <strong>{{ $employee->job_type }}</strong>. We are excited to have you on board and look forward to working with you.</p>

    <p>As part of our onboarding process, we kindly request you to complete the following tasks:</p>

    <ul>
        <li>Fill out and submit your personal information in the Employee Portal.</li>
        <li>Review and sign the required documents, including:
            <ul>
                <li>Non-Disclosure Agreement (NDA)</li>
                <li>Employee Handbook Acknowledgment</li>
                <li>Company Policies Agreement</li>
            </ul>
        </li>
        <li>Attend the scheduled orientation sessions.</li>
        <li>Meet with your assigned mentor and team.</li>
    </ul>

    <p>Your official start date is <strong>{{ \Carbon\Carbon::parse($employee->hired_date)->format('F d, Y') }}</strong>. Please ensure that all required documents are submitted before this date to facilitate a smooth onboarding process.</p>

    <p>If you have any questions or need assistance, feel free to reach out to the HR department at [HR Email or Contact Information].</p>

    <p>We look forward to seeing you thrive in your new role!</p>

    <p>Best regards,</p>

    <p><strong>NexFleet Dynamics HR Department</strong></p>
</body>
</html>
