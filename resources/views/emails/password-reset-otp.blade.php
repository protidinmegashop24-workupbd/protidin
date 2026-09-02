<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Workup BD Password Reset OTP' }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f7fb; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #e6edf5;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#eefaf2 0%,#f8fbff 100%); padding:28px 30px; text-align:center;">
                            <h2 style="margin:0; color:#172b4d; font-size:28px; font-weight:800;">Workup BD Password Reset OTP</h2>
                            <p style="margin:10px 0 0; color:#64748b; font-size:15px; line-height:1.7;">
                                Use the OTP below to reset your password.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 14px; color:#172b4d; font-size:15px;">Hello {{ $name ?? 'User' }},</p>

                            <p style="margin:0 0 14px; color:#56697f; font-size:15px; line-height:1.8;">
                                We received a request to reset your Workup BD account password. Your OTP code is:
                            </p>

                            <div style="margin:20px 0; padding:16px 18px; background:#f8fbff; border:1px dashed #cbd8e6; border-radius:12px; text-align:center;">
                                <span style="font-size:32px; font-weight:800; color:#16a34a; letter-spacing:4px;">{{ $otp ?? '' }}</span>
                            </div>

                            <p style="margin:0 0 14px; color:#56697f; font-size:15px; line-height:1.8;">
                                This OTP will expire at {{ $expires_at ?? '' }}.
                            </p>

                            <p style="margin:18px 0 0; color:#172b4d; font-size:14px;">
                                Email: {{ $email ?? '' }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; background:#fbfdff; border-top:1px solid #eef2f7; text-align:center;">
                            <p style="margin:0; color:#7b8797; font-size:13px; line-height:1.7;">
                                If you did not request this OTP, please ignore this email and secure your account.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>