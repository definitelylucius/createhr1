@component('mail::message')
<style>
    .header {
        color: #1e40af;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }
    .offer-card {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        border-left: 4px solid #10b981;
    }
    .offer-row {
        margin-bottom: 12px;
        display: flex;
    }
    .offer-label {
        font-weight: 600;
        color: #4a5568;
        min-width: 100px;
    }
    .offer-value {
        color: #1e3a8a;
    }
    .action-buttons {
        display: flex;
        gap: 15px;
        margin: 25px 0;
        flex-wrap: wrap;
    }
    .steps-list {
        margin: 20px 0 0 20px;
        padding-left: 5px;
    }
    .steps-list li {
        margin-bottom: 12px;
        position: relative;
        padding-left: 30px;
        counter-increment: step-counter;
    }
    .steps-list li:before {
        content: counter(step-counter);
        color: white;
        background-color: #10b981;
        font-weight: bold;
        position: absolute;
        left: 0;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        text-align: center;
        font-size: 14px;
        line-height: 22px;
    }
    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
    }
</style>

<div class="header">🎉 Congratulations! Job Offer: {{ $application->job->title }}</div>

<p>Dear <strong>{{ $application->name }}</strong>,</p>

<p>We are thrilled to offer you the position of <strong>{{ $application->job->title }}</strong> at {{ config('app.name') }}! After careful consideration, we believe you would be a valuable addition to our team.</p>

<div class="offer-card">
    <div class="offer-row">
        <span class="offer-label">🏢 Position:</span>
        <span class="offer-value">{{ $application->job->title }}</span>
    </div>
    <div class="offer-row">
        <span class="offer-label">🏛️ Department:</span>
        <span class="offer-value">{{ $application->job->department }}</span>
    </div>
    <div class="offer-row">
        <span class="offer-label">📅 Start Date:</span>
        <span class="offer-value">{{ $data['start_date'] }}</span>
    </div>
    <div class="offer-row">
        <span class="offer-label">💰 Salary:</span>
        <span class="offer-value">{{ $data['salary'] }}</span>
    </div>
    <div class="offer-row">
        <span class="offer-label">🏆 Benefits:</span>
        <span class="offer-value">{{ $data['benefits'] ?? 'Comprehensive package as per company policy' }}</span>
    </div>
</div>

<p>Please review the attached offer letter which contains complete details about your compensation, benefits, and terms of employment.</p>

<div style="margin: 25px 0;">
    <strong>To accept this offer:</strong>
    <ol class="steps-list">
        <li>Carefully review all terms and conditions</li>
        <li>Sign the document electronically using the button below</li>
        <li>Return the signed copy by <strong>{{ $data['deadline'] ?? '5 business days' }}</strong></li>
    </ol>
</div>

@if(isset($data['meeting_link']))
<div style="background-color: #ecfdf5; padding: 15px; border-radius: 6px; margin: 20px 0;">
    <strong>📅 Offer Discussion Meeting:</strong><br>
    We've scheduled a meeting to discuss this offer and answer any questions you may have:<br><br>
    <strong>Date/Time:</strong> {{ $data['meeting_time'] }}<br>
    <strong>Meeting Link:</strong> <a href="{{ $data['meeting_link'] }}">Click here to join</a>
</div>
@endif

<div class="action-buttons">
    @component('mail::button', ['url' => $data['offer_path'], 'color' => 'primary'])
        📄 View Offer Letter
    @endcomponent
    
    @component('mail::button', ['url' => route('offer-letter.sign', $application->id), 'color' => 'success'])
        ✍️ Sign Offer Letter
    @endcomponent
</div>

<div style="margin: 20px 0;">
    <strong>Have questions?</strong><br>
    If you need clarification or wish to discuss any aspect of this offer, please contact our HR team immediately at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
</div>

<p>We're excited about the prospect of you joining our team and look forward to your positive response!</p>

<div class="footer">
    Best regards,<br>
    <strong>{{ config('app.name') }} HR Team</strong>
</div>
@endcomponent