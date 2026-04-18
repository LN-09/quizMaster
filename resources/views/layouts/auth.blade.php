<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Đăng nhập') — QuizMaster</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-brand">
            <span class="auth-logo">🎯</span>
            <h1 class="auth-brand-name">QuizMaster</h1>
            <p class="auth-brand-tagline">Nền tảng kiểm tra kiến thức trực tuyến</p>
        </div>
        <div class="auth-card">
            @yield('content')
        </div>
    </div>
</body>
</html>
