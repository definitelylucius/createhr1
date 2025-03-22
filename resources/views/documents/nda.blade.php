<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NDA - {{ $employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
            color: #000;
        }
        h1 {
            text-align: center;
            font-size: 20px;
        }
        .content {
            margin-top: 20px;
            font-size: 14px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            font-size: 14px;
        }
        .signature-line {
            margin-top: 30px;
            border-bottom: 1px solid #000;
            width: 250px;
            display: inline-block;
        }
        .date-line {
            border-bottom: 1px solid #000;
            width: 150px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Non-Disclosure Agreement</h1>
    <div class="content">
        <p>This Non-Disclosure Agreement (NDA) is made and entered into as of <strong>{{ date('F d, Y') }}</strong>, by and between <strong>NexFleet Dynamics</strong>, hereinafter referred to as the "Company," and <strong>{{ $employee->name }}</strong>, hereinafter referred to as the "Employee."</p>

        <p><strong>Employee Name:</strong> {{ $employee->name }}</p>
        <p><strong>Position:</strong> {{ $employee->job_type }}</p>
        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($employee->hired_date)->format('F d, Y') }}</p>

        <p class="section-title">1. Confidential Information</p>
        <p>The Employee acknowledges that during their employment with the Company, they may have access to confidential and proprietary information, including but not limited to business strategies, financial data, customer lists, trade secrets, operational processes, and other sensitive information.</p>

        <p class="section-title">2. Obligations of Employee</p>
        <ul>
            <li>Not to disclose, share, or distribute any Confidential Information to any unauthorized third party.</li>
            <li>Not to use the Confidential Information for personal gain or for any purpose outside the scope of their employment.</li>
            <li>To take reasonable precautions to protect the confidentiality of the Company’s information.</li>
        </ul>

        <p class="section-title">3. Exclusions</p>
        <ul>
            <li>Information that is publicly known through lawful means.</li>
            <li>Information obtained from a third party lawfully possessing such information without an obligation of confidentiality.</li>
            <li>Information disclosed with prior written consent from the Company.</li>
        </ul>

        <p class="section-title">4. Duration</p>
        <p>The obligations of confidentiality shall remain in effect for the duration of the Employee’s employment and continue for a period of <strong>two (2) years</strong> after termination of employment.</p>

        <p class="section-title">5. Return of Materials</p>
        <p>Upon termination of employment, the Employee agrees to return all company materials, including documents, files, records, and any confidential information in any form.</p>

        <p class="section-title">6. Legal Remedies</p>
        <p>Any breach of this NDA may result in disciplinary action, including termination of employment, and may subject the Employee to legal action.</p>

        <p class="section-title">7. Governing Law</p>
        <p>This Agreement shall be governed by and construed in accordance with the laws of [State/Country].</p>

        <p class="section-title">8. Acknowledgment</p>
        <p>The Employee acknowledges that they have read, understood, and agreed to the terms of this NDA.</p>

        <p><strong>Signed:</strong></p>
        <p>Company Representative: <span class="signature-line"></span></p>
        <p>Employee: <span class="signature-line"></span></p>
        <p><strong>Date:</strong> <span class="date-line"></span></p>
    </div>
</body>
</html>