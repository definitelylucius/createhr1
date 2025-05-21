<!DOCTYPE html>
<html>
<head>
    <title>Application Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            color: #1a365d;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px 0;
        }
        .appreciation {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 3px solid #718096;
        }
        .encouragement {
            margin-top: 25px;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <h2 class="header">Application Status Update</h2>
    
    <div class="content">
        <p>Dear {{ $application->name }},</p>
        
        <p>Thank you for taking the time to apply for the <strong>{{ $application->job->title }}</strong> position at {{ config('app.name') }}. We sincerely appreciate your interest in joining our team.</p>
        
        <p>After careful consideration of all applications, we regret to inform you that we have decided to move forward with other candidates whose qualifications more closely align with our current needs.</p>
    </div>
    
    <div class="appreciation">
        <p>We recognize the effort you put into your application and want to thank you for sharing your skills and experience with us. The selection process was highly competitive, and this decision does not reflect on your capabilities or potential.</p>
    </div>
    
    <div class="encouragement">
        <p>We encourage you to apply for future opportunities that match your background and interests. Our team grows regularly, and new positions become available frequently.</p>
    </div>
    
    <div class="content">
        <p>To stay informed about future openings, you may wish to:</p>
        <ul style="margin: 10px 0 0 20px;">
            <li>Follow our careers page</li>
            <li>Set up job alerts for relevant positions</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>We wish you the very best in your job search and professional endeavors.</p>
        
        <p>Best regards,</p>
        <p><strong>{{ config('app.name') }} Talent Acquisition Team</strong></p>
    </div>
</body>
</html>