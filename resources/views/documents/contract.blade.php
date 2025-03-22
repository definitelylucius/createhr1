<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Contract - {{ $employee['name'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        h1 { text-align: center; }
        .content { margin-top: 20px; }
        .section-title { font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Hiring Contract</h1>

    <div class="content">
        <p>This Hiring Contract (the "Agreement") is made and entered into as of <strong>{{ date('F d, Y') }}</strong>, by and between <strong>NexFleet Dynamics</strong>, hereinafter referred to as the "Company," and <strong>{{ $employee['name'] }}</strong>, hereinafter referred to as the "Employee."</p>

        <p><strong>Position:</strong> {{ $employee['job_type'] }}</p>
        <p><strong>Department:</strong> {{ $employee['department'] }}</p>
        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($employee->hired_date)->format('F d, Y') }}</p>
        <p><strong>Employment Type:</strong> [Full-Time/Part-Time/Contract]</p>
        <p><strong>Work Location:</strong> [Remote/On-Site/Hybrid]</p>

        <p class="section-title">1. Responsibilities</p>
        <p>The Employee agrees to perform the duties and responsibilities assigned to their role, adhering to the Company’s policies and standards.</p>

        <p class="section-title">2. Compensation & Benefits</p>
        <p><strong>Salary:</strong> [Salary Amount] per [Month/Year]. Payments will be processed on [Pay Schedule].</p>
        <p>Additional benefits include:</p>
        <ul>
            <li>Health Insurance (if applicable)</li>
            <li>Pension/Retirement Plan</li>
            <li>Paid Time Off</li>
        </ul>

        <p class="section-title">3. Confidentiality & Non-Compete</p>
        <p>The Employee agrees to maintain the confidentiality of all proprietary and sensitive information and will not engage in business that competes with the Company for a period of [X months/years] after employment.</p>

        <p class="section-title">4. Probation & Termination</p>
        <p>The Employee will be under probation for [X months]. Either party may terminate this contract with [X days] notice or immediate termination for misconduct.</p>

        <p class="section-title">5. Governing Law</p>
        <p>This contract shall be governed by the laws of [State/Country].</p>

        <p class="section-title">6. Acknowledgment & Acceptance</p>
        <p>By signing below, both parties acknowledge and agree to the terms outlined in this contract.</p>

        <p><strong>Signed:</strong></p>
        <p>_____________________ (Company Representative)</p>
        <p>_____________________ (Employee)</p>
        <p><strong>Date:</strong> _____________________</p>
    </div>
</body>
</html>