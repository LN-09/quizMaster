@extends('layouts.auth')
@section('title', 'Đăng ký')

@section('content')
<h2 class="auth-title">Tạo tài khoản</h2>

<form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf
    <div class="form-group">
        <label class="form-label" for="name">Họ và tên</label>
        <input type="text" id="name" name="name"
               class="form-input @error('name') is-error @enderror"
               value="{{ old('name') }}" placeholder="Nguyễn Văn A" required autofocus>
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email"
               class="form-input @error('email') is-error @enderror"
               value="{{ old('email') }}" placeholder="your@email.com" required>
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Mật khẩu</label>
        <input type="password" id="password" name="password"
               class="form-input" placeholder="Tối thiểu 8 ký tự" required>
        @error('password')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="form-input" placeholder="Nhập lại mật khẩu" required>
    </div>

    <button type="submit" class="btn btn-primary btn-full">Đăng ký</button>
</form>

<p class="auth-footer">
    Đã có tài khoản?
    <a href="{{ route('login') }}">Đăng nhập</a>
</p>
@endsection
