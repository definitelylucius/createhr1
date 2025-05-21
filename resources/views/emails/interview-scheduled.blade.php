@component('mail::message')
<style>
    .header {
        color: #1e40af;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }
    .detail-card {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        border-left: 4px solid #3b82f6;
    }
    .detail-row {
        margin-bottom: 12px;
    }
    .detail-label {
        font-weight: 600;
        color: #4a5568;
        display: inline-block;
        width: 80px;
    }
    .prep-list {
        margin: 15px 0 0 20px;
        padding-left: 5px;
    }
    .prep-list li {
        margin-bottom: 10px;
        position: relative;
        padding-left: 25px;
    }
    .prep-list li:before {
        content: "•";
        color: #3b82f6;
        font-weight: bold;
        position: absolute;
        left: 5px;
    }
    .meeting-guide {
        background-color: #eff6ff;
        padding: 12px 15px;
        border-radius: 6px;
        margin-top: 15px;
        display: inline-block;
    }
</style>

<div class="header">📅 Interview Scheduled</div>

<div class="detail-card">
    <div class="detail-row">
        <span class="detail-label">Candidate:</span>
        <strong>{{ $interview->candidate->full_name }}</strong>
    </div>
    <div class="detail-row">
        <span class="detail-label">Position:</span>
        {{ $interview->candidate->job->title ?? 'N/A' }}
    </div>
    <div class="detail-row">
        <span class="detail-label">Date:</span>
        {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('l, F j, Y') }}
    </div>
    <div class="detail-row">
        <span class="detail-label">Time:</span>
        {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('g:i A') }} ({{ config('app.timezone') }})
    </div>
</div>

@component('mail::panel')
### 🎯 Meeting Details
{!! nl2br(e($interview->notes)) !!}

@if(str_contains($interview->notes, 'zoom.us'))
<div class="meeting-guide">
    🔍 <strong>Zoom Guide:</strong> <a href="https://support.zoom.us/hc/en-us/articles/201362193-Joining-a-Meeting" target="_blank">How to join Zoom meeting</a>
</div>
@elseif(str_contains($interview->notes, 'meet.google.com'))
<div class="meeting-guide">
    🔍 <strong>Google Meet Guide:</strong> <a href="https://support.google.com/meet/answer/9303069" target="_blank">How to join Google Meet</a>
</div>
@endif
@endcomponent

<div style="margin: 20px 0;">
    <strong>✅ Preparation Checklist:</strong>
    <ul class="prep-list">
        <li>Test your microphone and camera in advance</li>
        <li>Ensure you have a stable internet connection</li>
        <li>Review the job description and your application</li>
        <li>Prepare questions about the role and company</li>
        <li>Join the meeting 5-10 minutes early</li>
        @if(str_contains($interview->notes, 'zoom.us') || str_contains($interview->notes, 'meet.google.com'))
        <li>Close unnecessary applications on your computer</li>
        @endif
    </ul>
</div>

@if($interview->notes_meeting_link)
<div style="text-align: center; margin: 25px 0;">
    @component('mail::button', ['url' => $interview->notes_meeting_link, 'color' => 'primary'])
        🚀 Join Interview Meeting
    @endcomponent
</div>
@endif

<div style="margin-top: 20px;">
    <strong>Need assistance?</strong><br>
    If you have any questions or technical issues, please contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
</div>

<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
    Best regards,<br>
    <strong>{{ config('app.name') }} Hiring Team</strong>
</div>
@endcomponent