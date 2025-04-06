@component('mail::message')
# Interview Scheduled: 

**Candidate:** {{ $interview->candidate->full_name }}  
**Position:** {{ $interview->candidate->job->title ?? 'N/A' }}  
**Date:** {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('g:i A') }} ({{ config('app.timezone') }})

@component('mail::panel')
### Meeting Details:
{!! nl2br(e($interview->notes)) !!}

@if(str_contains($interview->notes, 'zoom.us'))
**Zoom Guide:** [How to join Zoom meeting](https://support.zoom.us/hc/en-us/articles/201362193-Joining-a-Meeting)
@elseif(str_contains($interview->notes, 'meet.google.com'))
**Google Meet Guide:** [How to join Google Meet](https://support.google.com/meet/answer/9303069)
@endif
@endcomponent

**Preparation Checklist:**
- Test your microphone and camera
- Have a stable internet connection
- Review the job description
- Join 5 minutes early

@component('mail::button', ['url' => $interview->notes_meeting_link ?? '#', 'color' => 'primary'])
Join Meeting
@endcomponent

If you have any questions, please contact us at {{ config('mail.from.address') }}.

Best regards,  
{{ config('app.name') }} Team
@endcomponent