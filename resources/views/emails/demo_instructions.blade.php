@component('mail::message')
<style>
    .header {
        color: #2d3748;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .details-container {
        background-color: #f8fafc;
        border-left: 4px solid #3b82f6;
        padding: 15px;
        margin: 15px 0;
        border-radius: 0 4px 4px 0;
    }
    .detail-label {
        font-weight: 600;
        color: #4a5568;
        display: inline-block;
        width: 80px;
    }
    .section-title {
        font-weight: 600;
        color: #2d3748;
        margin: 15px 0 5px 0;
    }
    .content {
        color: #4a5568;
        line-height: 1.5;
    }
    .footer {
        margin-top: 30px;
        color: #718096;
    }
</style>

<div class="header">Practical Demo Instructions</div>

<div class="content">
    Dear {{ $application->user->name }},
</div>

<div class="content" style="margin: 15px 0;">
    Your practical demo for <strong>{{ $application->job->title }}</strong> has been scheduled:
</div>

<div class="details-container">
    <div>
        <span class="detail-label">Date:</span> {{ $data['date']->format('l, F j, Y') }}
    </div>
    <div style="margin: 8px 0;">
        <span class="detail-label">Time:</span> {{ $data['date']->format('g:i A') }}
    </div>
    <div>
        <span class="detail-label">Location:</span> {{ $data['location'] }}
    </div>
</div>

<div class="section-title">What to Prepare:</div>
<div class="content" style="margin-bottom: 15px;">
    {{ $data['preparation_instructions'] }}
</div>

<div class="section-title">Additional Notes:</div>
<div class="content">
    {{ $data['notes'] }}
</div>

<div class="footer">
    Best regards,<br>
    <strong>{{ config('app.name') }} Team</strong>
</div>
@endcomponent