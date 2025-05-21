<x-mail::message>
<style>
    .header {
        color: #1e40af;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }
    .congrats {
        background-color: #eff6ff;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        border-left: 4px solid #3b82f6;
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
        font-size: 18px;
        position: absolute;
        left: 5px;
    }
</style>

<div class="header">🎯 Final Interview Invitation</div>

<div class="content">
    Dear <strong>{{ $application->firstname }}</strong>,
</div>

<div class="congrats">
    Congratulations! We're impressed with your qualifications and are pleased to invite you for the final interview for the position of <strong>{{ $application->job->title }}</strong>.
</div>

<x-mail::panel>
### 📅 Interview Details
- **Date & Time:** {{ \Carbon\Carbon::parse($data['date'])->format('l, F j, Y \a\t g:i A') }}
- **Interview Panel:** {{ $data['interviewer'] }}
@if(isset($data['location']) && $data['location'])
- **📍 Location:** {{ $data['location'] }}
@elseif(isset($data['meeting_link']) && $data['meeting_link'])
- **💻 Format:** Virtual Interview
@endif
</x-mail::panel>

@if(isset($data['meeting_link']) && $data['meeting_link'])
<div style="margin: 15px 0;">
    <strong>Meeting Link:</strong><br>
    <a href="{{ $data['meeting_link'] }}">{{ $data['meeting_link'] }}</a>
</div>
@endif

<div class="section-title">📝 Preparation Tips:</div>
<ul class="prep-list">
    <li>Review the job description and your application materials</li>
    <li>Prepare examples of your achievements and experiences</li>
    <li>Have questions ready about the role and organization</li>
    @if(isset($data['location']) && $data['location'])
    <li>Plan your route and aim to arrive 15 minutes early</li>
    @else
    <li>Test your technology (audio/video) in advance</li>
    <li>Choose a quiet, professional setting for the interview</li>
    @endif
</ul>

<div style="text-align: center; margin: 30px 0;">
    <x-mail::button :url="isset($data['meeting_link']) ? $data['meeting_link'] : '#'" color="{{ isset($data['meeting_link']) ? 'primary' : 'default' }}">
        @if(isset($data['meeting_link']))
        🚀 Join Virtual Interview
        @else
        📋 View Interview Details
        @endif
    </x-mail::button>
</div>

<div style="margin-top: 20px;">
    <strong>Need assistance?</strong><br>
    If you have any questions or need to reschedule, please contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a> within 24 hours.
</div>

<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
    Best regards,<br>
    <strong style="color: #1e40af;">{{ config('app.name') }} Hiring Team</strong>
</div>
</x-mail::message>