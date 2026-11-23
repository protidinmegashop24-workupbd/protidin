<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7f7f7; padding:30px;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:10px; padding:30px; border:1px solid #e5e7eb;">
        <h2 style="margin-top:0; color:#111827;">Protidin Mega Earn Email Verification</h2>

        <p>Hello {{ $name ?? 'User' }},</p>

        <p>Your OTP verification code is:</p>

        <div style="font-size:32px; font-weight:700; letter-spacing:6px; color:#16a34a; margin:20px 0;">
            {{ $otp }}
        </div>

        <p>Please enter this OTP on the verification page to activate your account.</p>

        <p>If you did not request this, please ignore this email.</p>

        <p style="margin-top:30px;">Thanks,<br>Protidin Mega Earn</p>
    </div>
</body>
</html>