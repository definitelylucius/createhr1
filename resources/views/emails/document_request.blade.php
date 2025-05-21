<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document Submission Required</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8f9fa; padding: 20px; color: #2d3748; line-height: 1.6;">
    <table style="max-width: 640px; margin: 0 auto; background-color: white; border-radius: 10px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <tr>
            <td>
                <div style="text-align: center; margin-bottom: 25px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;">
                        <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" fill="#3B82F6"/>
                        <path d="M14 2V8H20M16 13H8M16 17H8M10 9H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h1 style="color: #1e40af; font-size: 24px; margin: 15px 0 5px; display: inline-block; vertical-align: middle; margin-left: 10px;">Document Submission Required</h1>
                </div>

                <p style="margin-bottom: 20px;">Hello <strong style="color: #1e3a8a;">{{ $application->firstname }}</strong>,</p>
                
                <p style="margin-bottom: 20px;">Thank you for progressing in our hiring process. To complete your application for <strong>{{ $application->job->title }}</strong>, we require the following documents:</p>

                <div style="background-color: #f0f7ff; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #3b82f6;">
                    <h3 style="color: #1e40af; margin-top: 0; margin-bottom: 15px;">Required Documents</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($documents as $doc)
                        <li style="margin-bottom: 8px;">{{ ucwords(str_replace('_', ' ', $doc)) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div style="display: flex; margin: 25px 0; background-color: #fff7ed; border-radius: 8px; padding: 15px; border-left: 4px solid #f97316;">
                    <div style="margin-right: 15px;">⏰</div>
                    <div>
                        <strong style="color: #9a3412;">Submission Deadline:</strong><br>
                        {{ \Carbon\Carbon::parse($deadline)->format('l, F j, Y') }}
                    </div>
                </div>

                <div style="margin: 25px 0;">
                    <h3 style="color: #1e40af; margin-top: 0; margin-bottom: 10px;">Submission Instructions</h3>
                    <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px;">
                        {!! nl2br(e($instructions)) !!}
                    </div>
                </div>

                <div style="text-align: center; margin: 35px 0;">
                    <a href="{{ $uploadUrl }}" style="background-color: #2563eb; color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; transition: background-color 0.3s;">📤 Upload Documents Now</a>
                </div>

                <p style="margin-bottom: 25px;">Please ensure all documents are:</p>
                <ul style="margin: 0 0 25px 20px; padding-left: 20px;">
                    <li style="margin-bottom: 8px;">Clear and legible</li>
                    <li style="margin-bottom: 8px;">In PDF or high-quality image format</li>
                    <li style="margin-bottom: 8px;">Named appropriately (e.g., "Resume_YourName.pdf")</li>
                </ul>

                <p style="margin-bottom: 5px;">For any questions, please contact our HR team.</p>
                
                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <p style="margin: 0;">Best regards,</p>
                    <p style="margin: 0; font-weight: bold; color: #1e40af;">The {{ config('app.name') }} Team</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>