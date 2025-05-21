<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Invitation</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            color: #1a365d;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .details-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3182ce;
        }
        .detail-row {
            margin-bottom: 12px;
            display: flex;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 100px;
        }
        .detail-value {
            color: #2d3748;
        }
        .notes-section {
            background-color: #ebf8ff;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 3px solid #63b3ed;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        a {
            color: #3182ce;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        @component('mail::message')
        <div class="header">✨ Interview Invitation</div>

        <p>Dear {{ $candidate->first_name }},</p>

        <p>We're delighted to invite you for the <strong>{{ ucfirst(str_replace('_', ' ', $stage->stage)) }}</strong> stage of the hiring process for the <strong>{{ $candidate->job->title }}</strong> position at NexFleet Dynamics.</p>

        <div class="details-card">
            <div class="detail-row">
                <span class="detail-label">📅 Date:</span>
                <span class="detail-value">{{ $event->start_time->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">⏰ Time:</span>
                <span class="detail-value">{{ $event->start_time->format('g:i A') }} - {{ $event->end_time->format('g:i A') }}</span>
            </div>
            @if($event->location)
            <div class="detail-row">
                <span class="detail-label">📍 Location:</span>
                <span class="detail-value">{{ $event->location }}</span>
            </div>
            @endif
            @if($event->meeting_link)
            <div class="detail-row">
                <span class="detail-label">🔗 Meeting Link:</span>
                <span class="detail-value"><a href="{{ $event->meeting_link }}">Click to join video call</a></span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">👤 Interviewer:</span>
                <span class="detail-value">{{ $stage->interviewer }}</span>
            </div>
        </div>

        @if($stage->notes)
        <div class="notes-section">
            <strong>📋 Additional Notes:</strong><br>
            {{ $stage->notes }}
        </div>
        @endif

        <div style="margin: 20px 0;">
            <strong>Please prepare:</strong>
            <ul style="margin: 10px 0 0 20px; padding-left: 5px;">
                <li>A copy of your resume/CV</li>
                @if($event->location)
                <li>Valid photo identification</li>
                <li>Plan to arrive 10-15 minutes early</li>
                @else
                <li>Test your audio/video equipment beforehand</li>
                <li>Find a quiet, well-lit space for the interview</li>
                @endif
            </ul>
        </div>

        @if($event->meeting_link)
        <div class="button-container">
            @component('mail::button', ['url' => $event->meeting_link, 'color' => 'primary'])
                🚀 Join Virtual Interview
            @endcomponent
        </div>
        @endif

        <p>Please confirm your attendance by replying to this email or contacting our HR team.</p>

        <div class="footer">
            Best regards,<br>
            <strong>The NexFleet Dynamics Recruitment Team</strong>
        </div>
        @endcomponent
    </div>
</body>
</html>