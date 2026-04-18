@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Xin chào, {{ Auth::user()->name }}! 👋</h2>
        <p>Hãy tiếp tục luyện tập và nâng cao kiến thức của bạn.</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-blue">📝</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_attempts'] }}</div>
            <div class="stat-label">Lượt đã làm</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-green">🏆</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['passed'] }}</div>
            <div class="stat-label">Bài đạt yêu cầu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-purple">📊</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['avg_score'], 1) }}%</div>
            <div class="stat-label">Điểm trung bình</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-orange">⭐</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['best_score'], 1) }}%</div>
            <div class="stat-label">Điểm cao nhất</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    {{-- Available Quizzes --}}
    <div style="grid-column: span 2">
        <div class="section-header">
            <h3 class="section-title">Quiz mới nhất</h3>
            <a href="{{ route('student.quizzes.index') }}" class="section-link">Xem tất cả →</a>
        </div>
        <div class="quiz-cards-grid">
            @forelse($availableQuizzes as $quiz)
            <div class="quiz-card">
                <div class="quiz-card-header">
                    <span class="category-pill" style="background:{{ $quiz->category->color ?? '#888' }}20;color:{{ $quiz->category->color ?? '#888' }}">
                        {{ $quiz->category->name ?? 'Khác' }}
                    </span>
                    <span class="badge badge-{{ $quiz->getDifficultyColor() }} badge-sm">
                        {{ $quiz->getDifficultyLabel() }}
                    </span>
                </div>
                <h4 class="quiz-card-title">{{ $quiz->title }}</h4>
                <p class="quiz-card-desc">{{ Str::limit($quiz->description, 70) }}</p>
                <div class="quiz-card-meta">
                    <span>❓ {{ $quiz->questions_count }} câu</span>
                    <span>⏱ {{ $quiz->getTimeLimitLabel() }}</span>
                    <span>🎯 Đạt: {{ $quiz->passing_score }}%</span>
                </div>
                @if($quiz->user_best_score !== null)
                <div class="quiz-best-score">
                    Điểm tốt nhất: <strong>{{ number_format($quiz->user_best_score, 1) }}%</strong>
                </div>
                @endif
                <div class="quiz-card-footer">
                    @if($quiz->user_can_attempt)
                        <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn btn-primary btn-sm btn-full">
                            {{ $quiz->user_best_score !== null ? '🔄 Làm lại' : '▶ Bắt đầu' }}
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm btn-full" disabled>
                            🔒 Đã hết lượt
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <p>Chưa có quiz nào được xuất bản.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent results --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kết quả gần đây</h3>
            <a href="{{ route('student.results.index') }}" class="section-link">Xem tất cả</a>
        </div>
        <div class="card-body p-0">
            @forelse($recentAttempts as $attempt)
            <a href="{{ route('student.results.show', $attempt) }}" class="result-list-item">
                <div class="result-list-info">
                    <div class="result-list-title">{{ Str::limit($attempt->quiz->title, 25) }}</div>
                    <div class="result-list-meta">{{ $attempt->created_at->diffForHumans() }}</div>
                </div>
                <div class="result-list-score">
                    <div class="score-circle {{ $attempt->passed ? 'score-pass' : 'score-fail' }}">
                        {{ number_format($attempt->score, 0) }}%
                    </div>
                </div>
            </a>
            @empty
            <p class="text-muted p-4 text-center">Chưa có kết quả nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
