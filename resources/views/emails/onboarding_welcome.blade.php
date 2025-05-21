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
    .welcome-banner {
        background-color: #eff6ff;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
        border-left: 4px solid #3b82f6;
    }
    .steps-container {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }
    .step-item {
        margin-bottom: 15px;
        padding-left: 25px;
        position: relative;
    }
    .step-number {
        position: absolute;
        left: 0;
        background-color: #3b82f6;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        text-align: center;
        font-size: 12px;
        line-height: 20px;
        font-weight: bold;
    }
    .first-day-card {
        background-color: #ecfdf5;
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
    .button-container {
        text-align: center;
        margin: 30px 0;
    }
    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
</style>

<div class="header">🎉 Welcome to {{ config('app.name') }}!</div>

<div class="welcome-banner">
    <p style="font-size: 18px; margin: 0;">We're thrilled to welcome you as our new <strong>{{ $application->job->title }}</strong>!</p>
</div>

<p>Dear <strong>{{ $application->name }}</strong>,</p>

<p>Congratulations on your new position! We're excited to have you join our team and look forward to the contributions you'll make.</p>

<div class="steps-container">
    <h3 style="margin-top: 0; color: #1e40af;">📋 Next Steps in Your Onboarding Process:</h3>
    
    <div class="step-item">
        <span class="step-number">1</span>
        <strong>Complete Onboarding Documents</strong> - Review and sign your employment paperwork
    </div>
    <div class="step-item">
        <span class="step-number">2</span>
        <strong>Submit Tax Forms</strong> - Required for payroll setup (W-4 and state equivalents)
    </div>
    <div class="step-item">
        <span class="step-number">3</span>
        <strong>Review Company Policies</strong> - Employee handbook and workplace guidelines
    </div>
    <div class="step-item">
        <span class="step-number">4</span>
        <strong>Complete Training Modules</strong> - Mandatory and role-specific training materials
    </div>
    <div class="step-item">
        <span class="step-number">5</span>
        <strong>Set Up Your Accounts</strong> - Company email and system access credentials
    </div>
</div>

<div class="first-day-card">
    <h3 style="margin-top: 0; color: #047857;">📅 Your First Day Details:</h3>
    
    <div class="detail-row">
        <span class="detail-label">Date:</span>
        <span>{{ $data['start_date'] }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Time:</span>
        <span>{{ $data['start_time'] ?? '9:00 AM' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Location:</span>
        <span>{{ $data['location'] ?? 'Main Office' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Dress Code:</span>
        <span>{{ $data['dress_code'] ?? 'Business Casual' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Bring:</span>
        <span>{{ $data['what_to_bring'] ?? 'Valid ID, Bank Details, and Emergency Contact Information' }}</span>
    </div>
    
    <p style="margin-bottom: 0; margin-top: 15px;">Lunch will be provided on your first day. Please inform us of any dietary restrictions.</p>
</div>

<div class="button-container">
    @component('mail::button', ['url' => route('employee.onboarding', $application->id), 'color' => 'primary'])
        🚀 Access Your Onboarding Portal
    @endcomponent
</div>

<p>Your hiring manager, <strong>{{ $data['manager_name'] ?? '[Manager Name]' }}</strong>, will reach out to schedule a welcome meeting prior to your start date.</p>

<p><strong>Need help?</strong> Contact our onboarding team at <a href="mailto:onboarding@{{ config('app.domain') }}">onboarding@{{ config('app.domain') }}</a> or call (555) 123-4567.</p>

<div class="footer">
    <p>We're committed to making your transition smooth and enjoyable. Welcome to the team!</p>
    
    <p>Best regards,<br>
    <strong>{{ config('app.name') }} Onboarding Team</strong></p>
</div>
@endcomponent