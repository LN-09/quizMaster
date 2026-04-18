@extends('layouts.app')
@section('title', 'Danh sách Quiz')
@section('page-title', 'Làm bài Quiz')

@section('content')
{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" class="form-input"
                       placeholder="🔍 Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="category" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="difficulty" class="form-select">
                    <option value="">Độ khó</option>
                    <option value="easy"   {{ request('difficulty') === 'easy'   ? 'selected' : '' }}>Dễ</option>
                    <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Trung bình</option>
                    <option value="hard"   {{ request('difficulty') === 'hard'   ? 'selected' : '' }}>Khó</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary btn-sm">Lọc</button>
            <a href="{{ route('student.quizzes.index') }}" class="btn btn-outline btn-sm">Xóa</a>
        </form>
    </div>
</div>

{{-- Grid of quizzes --}}
@if($quizzes->isEmpty())
<div class="empty-state card">
    <div style="font-size:3rem">📚</div>
    <p>Không tìm thấy quiz nào phù hợp.</p>
    <a href="{{ route('student.quizzes.index') }}" class="btn btn-outline">Xóa bộ lọc</a>
</div>
@else
<div class="quiz-cards-grid-large">
    @foreach($quizzes as $quiz)
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
        <p class="quiz-card-desc">{{ Str::limit($quiz->description, 80) }}</p>
        <div class="quiz-card-meta">
            <span>❓ {{ $quiz->questions_count }} câu</span>
            <span>⏱ {{ $quiz->getTimeLimitLabel() }}</span>
            <span>🎯 Đạt: {{ $quiz->passing_score }}%</span>
            @if($quiz->max_attempts > 0)
            <span>🔁 {{ $quiz->user_attempts_count }}/{{ $quiz->max_attempts }} lần</span>
            @endif
        </div>
        @if($quiz->user_best_score !== null)
        <div class="quiz-best-score">
            Điểm tốt nhất: <strong>{{ number_format($quiz->user_best_score, 1) }}%</strong>
            @if($quiz->user_best_score >= $quiz->passing_score)
                <span class="badge badge-success badge-sm">✓ Đạt</span>
            @else
                <span class="badge badge-warning badge-sm">Chưa đạt</span>
            @endif
        </div>
        @endif
        <div class="quiz-card-footer">
            @if($quiz->user_can_attempt)
                <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn btn-primary btn-sm btn-full">
                    {{ $quiz->user_best_score !== null ? '🔄 Làm lại' : '▶ Bắt đầu làm bài' }}
                </a>
            @else
                <span class="btn btn-secondary btn-sm btn-full disabled">🔒 Đã hết lượt thi</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">
    {{ $quizzes->links() }}
</div>
@endif
@endsection
