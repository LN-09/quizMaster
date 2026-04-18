@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', 'Chi tiết Quiz')

@section('topbar-actions')
    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-secondary btn-sm">✏️ Chỉnh sửa</a>
    <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-primary btn-sm">❓ Câu hỏi</a>
@endsection

@section('content')
<div class="quiz-detail-grid">
    {{-- Info card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin Quiz</h3>
            <span class="badge {{ $quiz->is_published ? 'badge-success' : 'badge-secondary' }}">
                {{ $quiz->is_published ? '✓ Đã xuất bản' : '○ Bản nháp' }}
            </span>
        </div>
        <div class="card-body">
            <dl class="info-list">
                <dt>Danh mục</dt>
                <dd>
                    <span class="category-dot" style="background:{{ $quiz->category->color }}"></span>
                    {{ $quiz->category->name }}
                </dd>
                <dt>Độ khó</dt>
                <dd><span class="badge badge-{{ $quiz->getDifficultyColor() }}">{{ $quiz->getDifficultyLabel() }}</span></dd>
                <dt>Thời gian</dt>
                <dd>{{ $quiz->getTimeLimitLabel() }}</dd>
                <dt>Điểm đạt</dt>
                <dd>{{ $quiz->passing_score }}%</dd>
                <dt>Số lần thi</dt>
                <dd>{{ $quiz->max_attempts === 0 ? 'Không giới hạn' : $quiz->max_attempts . ' lần' }}</dd>
                <dt>Số câu hỏi</dt>
                <dd>{{ $quiz->questions->count() }} câu ({{ $quiz->getTotalPoints() }} điểm)</dd>
                <dt>Xáo trộn</dt>
                <dd>
                    {{ $quiz->shuffle_questions ? '✓ Câu hỏi' : '' }}
                    {{ $quiz->shuffle_answers ? '✓ Đáp án' : '' }}
                    {{ !$quiz->shuffle_questions && !$quiz->shuffle_answers ? 'Không' : '' }}
                </dd>
            </dl>
            @if($quiz->description)
            <p class="text-muted mt-3">{{ $quiz->description }}</p>
            @endif
        </div>
    </div>

    {{-- Stats card --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Thống kê</h3></div>
        <div class="card-body">
            <div class="mini-stats">
                <div class="mini-stat">
                    <div class="mini-stat-value">{{ $stats['total_attempts'] }}</div>
                    <div class="mini-stat-label">Tổng lượt thi</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value">{{ $stats['completed'] }}</div>
                    <div class="mini-stat-label">Hoàn thành</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value">{{ number_format($stats['avg_score'], 1) }}%</div>
                    <div class="mini-stat-label">Điểm TB</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value">{{ number_format($stats['pass_rate'], 1) }}%</div>
                    <div class="mini-stat-label">Tỷ lệ đạt</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Questions list --}}
    <div class="card" style="grid-column:span 2">
        <div class="card-header">
            <h3 class="card-title">Danh sách câu hỏi ({{ $quiz->questions->count() }})</h3>
            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary btn-sm">
                ➕ Thêm câu hỏi
            </a>
        </div>
        <div class="card-body p-0">
            @forelse($quiz->questions as $index => $question)
            <div class="question-preview">
                <div class="question-num">{{ $index + 1 }}</div>
                <div class="question-body">
                    <div class="question-text">{{ $question->content }}</div>
                    <div class="question-meta">
                        <span class="badge badge-secondary badge-sm">{{ $question->getTypeLabel() }}</span>
                        <span class="text-muted">{{ $question->points }} điểm</span>
                        <span class="text-muted">{{ $question->answers->count() }} đáp án</span>
                    </div>
                </div>
                <div class="question-actions">
                    <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}"
                       class="btn-icon" title="Sửa">✏️</a>
                    <form method="POST"
                          action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                          onsubmit="return confirm('Xóa câu hỏi này?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon btn-icon-danger" title="Xóa">🗑</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-6">
                Chưa có câu hỏi nào.
                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}">Thêm câu hỏi đầu tiên →</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
