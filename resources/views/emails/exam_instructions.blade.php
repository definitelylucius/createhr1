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
    .details-container {
        background-color: #f8fafc;
        border-left: 4px solid #3b82f6;
        padding: 20px;
        margin: 20px 0;
        border-radius: 0 8px 8px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .detail-label {
        font-weight: 600;
        color: #4a5568;
        display: inline-block;
        width: 90px;
    }
    .detail-value {
        color: #1e3a8a;
        font-weight: 500;
    }
    .section-title {
        font-weight: 700;
        color: #1e40af;
        margin: 25px 0 15px 0;
        font-size: 18px;
    }
    .content {
        color: #4a5568;
        line-height: 1.6;
        font-size: 15px;
    }
    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
    }
    .highlight-box {
        background-color: #eff6ff;
        padding: 15px;
        border-radius: 6px;
        margin: 15px 0;
    }
    .rules-list {
        margin: 10px 0 0 20px;
        padding-left: 5px;
    }
    .rules-list li {
        margin-bottom: 8px;
        position: relative;
        padding-left: 25px;
    }
    .rules-list li:before {
        content: "•";
        color: #3b82f6;
        font-weight: bold;
        position: absolute;
        left: 0;
    }
</style>

<div class="header">✍️ Written Exam Instructions</div>

<div class="content">
    Dear <strong>{{ $application->firstname }} {{ $application->lastname }}</strong>,
</div>

<div class="content" style="margin: 20px 0;">
    You have been scheduled to take the written exam for the position of <strong style="color: #1e40af;">{{ $application->job->title }}</strong>.
</div>

<div class="details-container">
    <div style="margin-bottom: 12px;">
        <span class="detail-label">📅 Date:</span>
        <span class="detail-value">{{ $data['date']->format('l, F j, Y') }}</span>
    </div>
    <div style="margin-bottom: 12px;">
        <span class="detail-label">⏰ Time:</span>
        <span class="detail-value">{{ $data['date']->format('g:i A') }}</span>
    </div>
    <div style="margin-bottom: 12px;">
        <span class="detail-label">📍 Location:</span>
        <span class="detail-value">{{ $data['location'] }}</span>
    </div>
    <div>
        <span class="detail-label">⏳ Duration:</span>
        <span class="detail-value">{{ $data['duration'] }}</span>
    </div>
</div>

<div class="section-title">📝 What to Bring:</div>
<ul class="rules-list">
    <li>Valid government-issued photo ID</li>
    <li>Blue or black pens (2 recommended)</li>
    <li>Basic calculator (if permitted for your exam)</li>
    <li>Water in a clear bottle</li>
</ul>

<div class="section-title">📚 Exam Content:</div>
<div class="highlight-box">
    {{ $data['exam_content'] ?? 'The exam will cover general knowledge, situational judgment, and role-specific questions relevant to the position.' }}
</div>

<div class="section-title">🚫 Exam Rules:</div>
<ul class="rules-list">
    <li>No electronic devices (phones, smartwatches, etc.) are permitted</li>
    <li>No outside reference materials or notes allowed</li>
    <li>Arrive at least 30 minutes before your scheduled time</li>
    <li>Late arrivals may not be admitted</li>
    <li>Follow all proctor instructions</li>
</ul>

@if(isset($data['map_link']))
<div style="text-align: center; margin: 30px 0;">
    @component('mail::button', ['url' => $data['map_link'], 'color' => 'primary'])
    📍 View Exam Location on Map
    @endcomponent
</div>
@endif

<div class="content" style="margin-top: 20px;">
    <strong>Need to reschedule?</strong><br>
    If you cannot attend, please notify us immediately at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
</div>

<div class="footer">
    Best regards,<br>
    <strong style="color: #1e40af;">{{ config('app.name') }} Recruitment Team</strong>
</div>
@endcomponent