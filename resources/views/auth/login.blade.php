@extends('layouts.auth')
@section('title', 'Đăng nhập')

@section('content')
<h2 class="auth-title">Đăng nhập</h2>

@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login') }}" class="auth-form">
    @csrf
    <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email"
               class="form-input @error('email') is-error @enderror"
               value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Mật khẩu</label>
        <input type="password" id="password" name="password"
               class="form-input" placeholder="••••••••" required>
    </div>

    <div class="form-group form-check">
        <label class="check-label">
            <input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập
        </label>
    </div>

    <button type="submit" class="btn btn-primary btn-full">Đăng nhập</button>
</form>

<p class="auth-footer">
    Chưa có tài khoản?
    <a href="{{ route('register') }}">Đăng ký ngay</a>
</p>

<div class="auth-demo">
    <p class="demo-title">Tài khoản demo:</p>
    <div class="demo-accounts">
        <div class="demo-account">
            <strong>Admin:</strong> admin@quizmaster.com / password
        </div>
        <div class="demo-account">
            <strong>Student:</strong> an@student.com / password
        </div>
    </div>
</div>
@endsection
