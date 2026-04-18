<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QuizMaster') — QuizMaster</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- ─── Sidebar ─────────────────────────────────────────────── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <span class="logo-icon">🎯</span>
            <span class="logo-text">QuizMaster</span>
        </div>
        <button class="sidebar-close" id="sidebarClose">✕</button>
    </div>

    <nav class="sidebar-nav">
        @if(Auth::user()->isAdmin())
            <div class="nav-section">
                <span class="nav-section-label">Quản trị</span>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.quizzes.index') }}"vie
                   class="nav-item {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span> Quản lý Quiz
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏷️</span> Danh mục
                </a>
            </div>
        @else
            <div class="nav-section">
                <span class="nav-section-label">Học tập</span>
                <a href="{{ route('student.dashboard') }}"
                   class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
                <a href="{{ route('student.quizzes.index') }}"
                   class="nav-item {{ request()->routeIs('student.quizzes.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎮</span> Làm bài Quiz
                </a>
                <a href="{{ route('student.results.index') }}"
                   class="nav-item {{ request()->routeIs('student.results.*') ? 'active' : '' }}">
                    <span class="nav-icon">📈</span> Kết quả của tôi
                </a>
            </div>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-details">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->isAdmin() ? 'Quản trị viên' : 'Học sinh' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Đăng xuất</button>
        </form>
    </div>
</aside>

{{-- ─── Main ────────────────────────────────────────────────── --}}
<div class="main-wrapper" id="mainWrapper">
    <header class="topbar">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <div class="topbar-title">@yield('page-title', 'QuizMaster')</div>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </header>

    <main class="content">
        {{-- Flash messages --}}
        @foreach(['success' => 'alert-success', 'error' => 'alert-error', 'warning' => 'alert-warning', 'info' => 'alert-info'] as $type => $class)
            @if(session($type))
                <div class="alert {{ $class }}">
                    {{ session($type) }}
                    <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif
        @endforeach

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
