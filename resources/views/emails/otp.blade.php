<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Code</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#f8fafc; color:#0f172a; margin:0; padding:24px;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:18px; padding:32px; border:1px solid #e5e7eb;">
        <h2 style="margin-top:0;">OTP Verification Code</h2>
        <p style="font-size:16px; line-height:1.6;">Use the following 6-digit code to complete your admin action:</p>
        <div style="font-size:32px; letter-spacing:6px; font-weight:700; background:#eff6ff; color:#1d4ed8; padding:18px 24px; border-radius:14px; text-align:center; margin:24px 0;">
            {{ $otp }}
        </div>
        <p style="margin-bottom:0; color:#475569;">Action: {{ str_replace('.', ' ', ucwords($action, '.')) }}</p>
        <p style="margin-bottom:0; color:#475569;">This code expires in 10 minutes.</p>
    </div>
</body>
</html>