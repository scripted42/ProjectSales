<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Login</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; padding: 30px; color: #333; max-width: 500px; margin: 0 auto; background-color: #ffffff;">
    
    <h3 style="color: #002c5f; border-bottom: 1px solid #eee; padding-bottom: 10px;">Security Verification</h3>
    
    <p style="font-size: 15px;">Halo Wahyu,</p>
    
    <p style="font-size: 15px; line-height: 1.5;">Berikut adalah kode sandi rahasia (OTP) untuk masuk ke panel manajemen <b>{{ \App\Models\Setting::get('site_name', 'Sistem') }}</b>.</p>
    
    <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 20px; border-radius: 8px; font-size: 32px; font-weight: 900; text-align: center; letter-spacing: 12px; margin: 30px 0; color: #0f172a;">
        {{ $otpCode }}
    </div>
    
    <p style="font-size: 13px; color: #64748b; font-style: italic;">* Kode otomatis hangus dalam 5 menit.<br>* Mohon jangan teruskan email ini ke siapa pun.</p>

</body>
</html>
