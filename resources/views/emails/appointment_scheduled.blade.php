<!DOCTYPE html>
<html>
<head>
    <title>Document Verification Appointment Scheduled</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #3f51b5;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 25px;
        }
        .details {
            background-color: #f9f9f9;
            border-left: 4px solid #3f51b5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .details ul {
            margin: 0;
            padding-left: 20px;
        }
        .details li {
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            color: #666666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background-color: #3f51b5;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .notes {
            background-color: #fff8e1;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Document Verification Appointment</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $application->firstname }} {{ $application->lastname }},</p>
            
            <p>We're pleased to confirm your document verification appointment with {{ config('app.name') }}. Below are the details:</p>
            
            <div class="details">
                <ul>
                    <li><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($document->scheduled_date)->format('l, F j, Y \a\t g:i A') }}</li>
                    <li><strong>Location:</strong> {{ $document->location }}</li>
                    @if(!empty($document->verification_type))
                    <li><strong>Verification Type:</strong> {{ ucwords(str_replace('_', ' ', $document->verification_type)) }}</li>
                    @else
                    <li><strong>Verification Type:</strong> Document Submission</li>
                    @endif
                </ul>
            </div>
            
            @if(!empty($document->notes))
            <div class="notes">
                <p><strong>Additional Notes:</strong></p>
                <p>{{ $document->notes }}</p>
            </div>
            @endif
            
            <p><strong>Please bring the following documents:</strong></p>
            <ul>
                <li>Valid government-issued ID</li>
                <li>NBI Clearance</li>
                <li>Police Clearance</li>
                <li>Other requested documents</li>
            </ul>
            
            <p>If you have any questions or need to reschedule, please contact us at <a href="mailto:support@example.com">nexfleet.dynamics9@gmail.com</a>.</p>
            
            <p>Best regards,</p>
            <p><strong>{{ config('app.name') }} Team</strong></p>
        </div>
        
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            <small>This is an automated message - please do not reply directly to this email.</small>
        </div>
    </div>
</body>
</html>