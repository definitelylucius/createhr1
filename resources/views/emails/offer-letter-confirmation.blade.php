<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter Accepted</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
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
            color: #1e40af;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        .confirmation-badge {
            background-color: #10b981;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 15px;
        }
        .details-card {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
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
            color: #1e3a8a;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        @component('mail::message')
        <div class="header">
            📝 Offer Letter Status
            <span class="confirmation-badge">Accepted</span>
        </div>

        <p>We're pleased to confirm that the offer letter has been officially signed and accepted by:</p>

        <div class="details-card">
            <div class="detail-row">
                <span class="detail-label">👤 Candidate:</span>
                <span class="detail-value"><strong>{{ $offerLetter->candidate->full_name }}</strong></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">💼 Position:</span>
                <span class="detail-value">{{ $offerLetter->position }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📅 Start Date:</span>
                <span class="detail-value">{{ $offerLetter->start_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">💰 Salary:</span>
                <span class="detail-value">${{ number_format($offerLetter->salary, 2) }} per year</span>
            </div>
        </div>

        <p>The candidate has completed all necessary documentation and is officially joining the team on the specified start date.</p>

        <div class="button-container">
            @component('mail::button', ['url' => route('admin.candidates.show', $offerLetter->candidate), 'color' => 'primary'])
                👤 View Candidate Profile
            @endcomponent
        </div>

        <div style="margin-top: 20px;">
            <strong>Next Steps:</strong>
            <ul style="margin: 10px 0 0 20px; padding-left: 5px;">
                <li>Notify the hiring manager and team</li>
                <li>Prepare onboarding materials</li>
                <li>Schedule orientation sessions</li>
                <li>Set up necessary system access</li>
            </ul>
        </div>

        <div class="footer">
            Best regards,<br>
            <strong>{{ config('app.name') }} HR Team</strong>
        </div>
        @endcomponent
    </div>
</body>
</html>