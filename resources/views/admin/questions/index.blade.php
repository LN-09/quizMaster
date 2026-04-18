@extends('layouts.app')
@section('title', 'Câu hỏi - ' . $quiz->title)
@section('page-title', 'Quản lý câu hỏi')

@section('topbar-actions')
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-outline btn-sm">← Về Quiz</a>
    <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary btn-sm">➕ Thêm câu hỏi</a>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-body" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
        <div>
            <strong>{{ $quiz->title }}</strong>
            <span class="text-muted">— {{ $quiz->questions->count() }} câu hỏi · {{ $quiz->getTotalPoints() }} điểm</span>
        </div>
        <span class="badge badge-{{ $quiz->getDifficultyColor() }}">{{ $quiz->getDifficultyLabel() }}</span>
        <span class="badge {{ $quiz->is_published ? 'badge-success' : 'badge-secondary' }}">
            {{ $quiz->is_published ? 'Đã xuất bản' : 'Bản nháp' }}
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($quiz->questions as $index => $question)
        <div class="question-item" data-id="{{ $question->id }}">
            <div class="question-drag-handle" title="Kéo để sắp xếp">⠿</div>
            <div class="question-num-badge">{{ $index + 1 }}</div>
            <div class="question-content">
                <div class="question-text">{{ $question->content }}</div>
                <div class="question-meta">
                    <span class="badge badge-secondary badge-sm">{{ $question->getTypeLabel() }}</span>
                    <span class="text-muted badge-sm">{{ $question->points }} điểm</span>
                </div>
                <div class="answer-list">
                    @foreach($question->answers as $answer)
                    <div class="answer-chip {{ $answer->is_correct ? 'answer-correct' : 'answer-wrong' }}">
                        {{ $answer->is_correct ? '✓' : '✗' }} {{ Str::limit($answer->content, 50) }}
                    </div>
                    @endforeach
                </div>
                @if($question->explanation)
                <div class="question-explanation">
                    💡 <em>{{ Str::limit($question->explanation, 100) }}</em>
                </div>
                @endif
            </div>
            <div class="question-actions">
                <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}"
                   class="btn btn-sm btn-secondary">✏️ Sửa</a>
                <form method="POST"
                      action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                      onsubmit="return confirm('Xóa câu hỏi này?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">🗑 Xóa</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-10">
            <div style="font-size:3rem;margin-bottom:.5rem">❓</div>
            <p class="text-muted">Quiz này chưa có câu hỏi nào.</p>
            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary mt-2">
                ➕ Thêm câu hỏi đầu tiên
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
