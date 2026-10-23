<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your verification code</title>
</head>
<body style="margin:0;padding:24px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <div style="max-width:480px;margin:0 auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:32px;">
        <h1 style="margin:0 0 12px;font-size:22px;">Your OTP verification code</h1>
        <p style="margin:0 0 24px;color:#475569;font-size:15px;line-height:1.6;">
            Use the code below to verify your email address. It expires in 5 minutes.
        </p>

        <p style="margin:0 0 24px;padding:18px 12px;background:#eef2ff;border-radius:12px;text-align:center;font-size:34px;font-weight:700;letter-spacing:10px;color:#3730a3;">
            {{ $otp }}
        </p>

        <p style="margin:0;color:#64748b;font-size:13px;line-height:1.6;">
            If you did not request this code, you can safely ignore this email.
        </p>
    </div>
</body>
</html>
