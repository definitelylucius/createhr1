<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interview Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        blockquote {
            border-left: 4px solid #007BFF;
            padding-left: 15px;
            color: #555;
            font-style: italic;
        }
        
    
    </style>
</head>
<body>
    <h2>Dear {{ $name }},</h2>

    <p>We are pleased to invite you to an online interview for the position of <strong>{{ $jobTitle }}</strong>.</p>

    <p><strong>Interview Details:</strong></p>
    <blockquote>
        {{ $customMessage }}
    </blockquote>

    <p>To join the interview, please click the button below at the scheduled time:</p>

   

    <p>If you have any questions or need to reschedule, feel free to contact us.</p>

    <p>We look forward to meeting you virtually!</p>

    <p>Best regards,</p>
    <p><strong>HR Team</strong></p>
</body>
</html>
