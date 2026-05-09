<!DOCTYPE html>
<html>
<head>
    <title>Developer OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2>AutoShow Pro - Developer Security</h2>
    <p>Halo Developer,</p>
    <p>Anda mencoba login ke halaman admin. Gunakan kode OTP 6 digit berikut untuk melanjutkan:</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; font-size: 24px; font-weight: bold; text-align: center; letter-spacing: 5px; margin: 20px 0;">
        {{ $otpCode }}
    </div>
    
    <p>Kode ini hanya berlaku selama 5 menit.</p>
    <p>Jika Anda tidak mencoba login, abaikan email ini.</p>
    <br>
    <p>Terima kasih,<br>Sistem Keamanan AutoShow Pro</p>
</body>
</html>
