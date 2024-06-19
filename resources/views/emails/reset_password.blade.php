<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
</head>
<body>
    <h1>Xin chào!</h1>
    <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
    <p>
        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 10px 20px; color: white; background-color: blue; text-decoration: none;">
            Đặt lại mật khẩu
        </a>
    </p>
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>
