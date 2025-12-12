<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4F46E5;
            text-align: center;
            letter-spacing: 10px;
            padding: 20px;
            background: #F3F4F6;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 12px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Halo, {{ $userName }}!</h2>
        
        <p>Terima kasih telah mendaftar di Perpustakaan Digital. Untuk menyelesaikan proses verifikasi akun, gunakan kode OTP berikut:</p>
        
        <div class="otp-code">
            {{ $otpCode }}
        </div>
        
        <p><strong>Kode ini akan kadaluarsa dalam 10 menit.</strong></p>
        
        <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        
        <div class="footer">
            <p>Email ini dikirim otomatis, mohon tidak membalas.</p>
            <p>&copy; 2025 Perpustakaan Digital. All rights reserved.</p>
        </div>
    </div>
</body>
</html>