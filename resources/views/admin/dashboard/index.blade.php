@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-blue">👥</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['total_students']) }}</div>
            <div class="stat-label">Học sinh</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-purple">📝</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_quizzes'] }}</div>
            <div class="stat-label">Tổng quiz
                <span class="stat-sub">({{ $stats['published_quizzes'] }} đã xuất bản)</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-green">✅</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['total_attempts']) }}</div>
            <div class="stat-label">Lượt làm bài</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-orange">🏆</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['avg_pass_rate'], 1) }}%</div>
            <div class="stat-label">Tỷ lệ đạt</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    {{-- Recent Attempts --}}
    <div class="card" style="grid-column: span 2">
        <div class="card-header">
            <h3 class="card-title">Lượt làm bài gần đây</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Học sinh</th>
                        <th>Quiz</th>
                        <th>Điểm</th>
                        <th>Kết quả</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttempts as $attempt)
                    <tr>
                        <td>{{ $attempt->user->name }}</td>
                        <td>{{ Str::limit($attempt->quiz->title, 30) }}</td>
                        <td><strong>{{ number_format($attempt->score, 1) }}%</strong></td>
                        <td>
                            <span class="badge badge-{{ $attempt->passed ? 'success' : 'danger' }}">
                                {{ $attempt->passed ? 'Đạt' : 'Không đạt' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $attempt->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Chưa có lượt làm bài nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Popular Quizzes --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quiz phổ biến nhất</h3>
        </div>
        <div class="card-body p-0">
            @forelse($popularQuizzes as $quiz)
            <div class="list-item">
                <div class="list-item-info">
                    <div class="list-item-title">{{ Str::limit($quiz->title, 28) }}</div>
                    <div class="list-item-sub">
                        <span class="badge badge-{{ $quiz->getDifficultyColor() }} badge-sm">
                            {{ $quiz->getDifficultyLabel() }}
                        </span>
                    </div>
                </div>
                <div class="list-item-meta">
                    <strong>{{ $quiz->attempts_count }}</strong>
                    <div class="text-muted" style="font-size:.75rem">lượt</div>
                </div>
            </div>
            @empty
            <p class="text-muted p-4">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="quick-actions">
    <h3 class="section-title">Thao tác nhanh</h3>
    <div class="action-buttons">
        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
            ➕ Tạo Quiz mới
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary">
            🏷️ Thêm danh mục
        </a>
        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline">
            📋 Xem tất cả Quiz
        </a>
    </div>
</div>
@endsection
