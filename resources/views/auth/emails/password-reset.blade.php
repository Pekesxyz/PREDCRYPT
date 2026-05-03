<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #3b82f6;">Halo!</h2>
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di <strong>PREDCRYPT</strong>.</p>
        <p>Silakan klik tombol di bawah ini untuk mereset password Anda. Link ini akan kedaluwarsa dalam 60 menit.</p>
        
        <a href="{{ url('reset-password/'.$token.'?email='.$email) }}" class="button">Reset Password</a>
        
        <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>
        <p>Salam,<br>Tim PREDCRYPT</p>
        
        <div class="footer">
            <hr>
            <p>Jika Anda kesulitan mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser Anda:</p>
            <p>{{ url('reset-password/'.$token.'?email='.$email) }}</p>
        </div>
    </div>
</body>
</html>

