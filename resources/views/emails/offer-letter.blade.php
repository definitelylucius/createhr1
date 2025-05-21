<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Job Offer</title>
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
        }
        .offer-highlight {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
        }
        .signature {
            margin-top: 25px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="email-container">
        @component('mail::message')
        <div class="header">🎉 Official Job Offer: {{ $offerLetter->position }}</div>

        <p>Dear <strong>{{ $offerLetter->candidate->first_name }}</strong>,</p>

        <p>Congratulations! We are delighted to extend to you an official offer for the position of <strong>{{ $offerLetter->position }}</strong> at NexFleet Dynamics.</p>

        <div class="offer-highlight">
            <p>This offer represents our confidence in your skills and experience, and we're excited about the value you'll bring to our team.</p>
            
            <p>Your offer package includes:</p>
            <ul style="margin: 10px 0 0 20px; padding-left: 5px;">
                <li>Competitive compensation package</li>
                <li>Comprehensive benefits</li>
                <li>Professional development opportunities</li>
            </ul>
        </div>

        <p>Please carefully review all details in your official offer letter. To accept this position:</p>
        <ol style="margin: 15px 0 0 20px; padding-left: 5px;">
            <li>Click the button below to access your offer letter</li>
            <li>Review all terms and conditions</li>
            <li>Electronically sign the document</li>
            <li>Submit by {{ $offerLetter->expiry_date->format('F j, Y') }}</li>
        </ol>

        <div class="button-container">
            @component('mail::button', ['url' => route('offer-letters.show', $offerLetter), 'color' => 'primary'])
                ✍️ Review & Sign Offer Letter
            @endcomponent
        </div>

        <p>We recommend saving or printing a copy of your signed offer letter for your records.</p>

        <div class="signature">
            <p>We look forward to welcoming you to the NexFleet Dynamics team!</p>
        </div>

        <div class="footer">
            <p><strong>Need assistance?</strong><br>
            Contact our HR team at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>

            <p>Best regards,<br>
            <strong>The NexFleet Dynamics Hiring Team</strong></p>
        </div>
        @endcomponent
    </div>
</body>
</html>