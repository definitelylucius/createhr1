@props([
    'application',
    'details'
])

<x-mail::message>
<style>
    .header {
        color: #1e40af;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }
    .details-container {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 18px;
        margin: 18px 0;
        border-left: 3px solid #3b82f6;
    }
    .detail-item {
        margin-bottom: 10px;
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
    .notes-section {
        background-color: #eff6ff;
        padding: 15px;
        border-radius: 6px;
        margin: 15px 0;
    }
    .footer {
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
    }
</style>

<div class="header">📅 Interview Invitation: {{ $application->job->title }}</div>

<p>Dear {{ $application->user->name }},</p>

<p>Congratulations! We're excited to invite you for an interview for the <strong>{{ $application->job->title }}</strong> position.</p>

<div class="details-container">
    <div class="detail-item">
        <span class="detail-label">📅 Date:</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($details['date'])->format('l, F j, Y') }}</span>
    </div>
    <div class="detail-item">
        <span class="detail-label">⏰ Time:</span>
        <span class="detail-value">{{ $details['time'] }}</span>
    </div>
    <div class="detail-item">
        <span class="detail-label">💻 Type:</span>
        <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $details['type'])) }} interview</span>
    </div>
    @if($details['type'] === 'in_person' && !empty($details['location']))
    <div class="detail-item">
        <span class="detail-label">📍 Location:</span>
        <span class="detail-value">{{ $details['location'] }}</span>
    </div>
    @elseif($details['type'] === 'virtual' && !empty($details['meeting_link']))
    <div class="detail-item">
        <span class="detail-label">🔗 Meeting Link:</span>
        <span class="detail-value"><a href="{{ $details['meeting_link'] }}">Click to join</a></span>
    </div>
    @endif
    <div class="detail-item">
        <span class="detail-label">👤 Interviewer:</span>
        <span class="detail-value">{{ $details['interviewer_name'] }}</span>
    </div>
</div>

@if(!empty($details['notes']))
<div class="notes-section">
    <strong>ℹ️ Additional Instructions:</strong><br>
    {{ $details['notes'] }}
</div>
@endif

<div style="margin: 20px 0;">
    <strong>Please note:</strong>
    <ul style="margin: 8px 0 0 20px; padding-left: 5px;">
        <li>Arrive 5-10 minutes early</li>
        @if($details['type'] === 'virtual')
        <li>Test your audio/video setup beforehand</li>
        <li>Find a quiet, well-lit space</li>
        @else
        <li>Bring a copy of your resume</li>
        <li>Check in with reception upon arrival</li>
        @endif
    </ul>
</div>

@if($details['type'] === 'virtual' && !empty($details['meeting_link']))
<div style="text-align: center; margin: 25px 0;">
    <x-mail::button :url="$details['meeting_link']" color="primary">
        🚀 Join Virtual Interview
    </x-mail::button>
</div>
@endif

<div style="margin-top: 15px;">
    Need to reschedule? Please contact us at least 24 hours in advance.
</div>

<div class="footer">
    Best regards,<br>
    <strong>{{ $details['company_name'] }}</strong>
</div>
</x-mail::message>