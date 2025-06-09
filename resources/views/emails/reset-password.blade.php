<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Klinik Hestia Medika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6B5B95;
            padding-bottom: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .logo-box {
            border: 2px solid #666;
            width: 30px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        .clinic-name {
            color: #666;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #6B5B95;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #5a4a7f;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="logo-box">H</div>
                <div>
                    <div class="clinic-name">KLINIK</div>
                    <div class="clinic-name">HESTIA MEDIKA</div>
                </div>
            </div>
        </div>

        <div class="content">
            <h2>Hello, {{ $user->name }}!</h2>
            
            <p>You are receiving this email because we received a password reset request for your account at Klinik Hestia Medika.</p>
            
            <p>To reset your password, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> This password reset link will expire in 24 hours for security reasons.
            </div>
            
            <p>If you did not request a password reset, no further action is required. Your account remains secure.</p>
            
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
            <p style="word-break: break-all; color: #6B5B95;">{{ $resetUrl }}</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Klinik Hestia Medika. All rights reserved.</p>
            <p>This is an automated email, please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>