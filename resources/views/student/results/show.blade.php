@extends('layouts.app')
@section('title', 'Kết quả bài thi')
@section('page-title', 'Kết quả bài thi')

@section('content')
<div style="max-width:800px;margin:0 auto">

    {{-- Result header card --}}
    <div class="result-hero {{ $attempt->passed ? 'result-pass' : 'result-fail' }}">
        <div class="result-hero-icon">{{ $attempt->passed ? '🏆' : '📚' }}</div>
        <div class="result-hero-title">{{ $attempt->passed ? 'Chúc mừng! Bạn đã đạt!' : 'Chưa đạt. Hãy cố gắng thêm!' }}</div>
        <div class="result-hero-score">{{ number_format($attempt->score, 1) }}%</div>
        <div class="result-hero-sub">
            {{ $attempt->correct_answers }}/{{ $attempt->total_questions }} câu đúng
            · {{ $attempt->earned_points }}/{{ $attempt->total_points }} điểm
        </div>
    </div>

    {{-- Stats row --}}
    <div class="result-stats-row">
        <div class="result-stat">
            <div class="rs-value {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                {{ number_format($attempt->score, 1) }}%
            </div>
            <div class="rs-label">Điểm đạt được</div>
        </div>
        <div class="result-stat">
            <div class="rs-value">{{ $attempt->quiz->passing_score }}%</div>
            <div class="rs-label">Điểm yêu cầu</div>
        </div>
        <div class="result-stat">
            <div class="rs-value">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</div>
            <div class="rs-label">Câu đúng</div>
        </div>
        <div class="result-stat">
            <div class="rs-value">{{ $attempt->getFormattedTimeSpent() }}</div>
            <div class="rs-label">Thời gian làm</div>
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="result-actions">
        <a href="{{ route('student.quizzes.index') }}" class="btn btn-outline">← Danh sách Quiz</a>
        @if($attempt->quiz->canUserAttempt(Auth::user()))
        <form method="POST" action="{{ route('student.quizzes.start', $attempt->quiz) }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-secondary">🔄 Làm lại</button>
        </form>
        @endif
        <a href="{{ route('student.quizzes.show', $attempt->quiz) }}" class="btn btn-primary">📋 Về trang quiz</a>
    </div>

    {{-- Detailed question review --}}
    @if($attempt->quiz->show_result_immediately)
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">📖 Xem lại câu hỏi</h3>
        </div>
        <div class="card-body p-0">
            @foreach($questionsWithResult as $index => $item)
            @php
                $question  = $item['question'];
                $selected  = $item['selected'];
                $isCorrect = $item['is_correct'];
                $corrects  = $item['correct_answers'];
            @endphp
            <div class="review-question {{ $isCorrect ? 'review-correct' : 'review-wrong' }}">
                <div class="review-question-header">
                    <span class="review-num">{{ $index + 1 }}</span>
                    <div class="review-question-text">{{ $question->content }}</div>
                    <span class="review-badge">{{ $isCorrect ? '✓ Đúng' : '✗ Sai' }}</span>
                </div>
                <div class="review-answers">
                    @foreach($question->answers as $answer)
                    <div class="review-answer
                        {{ $answer->is_correct ? 'answer-correct-highlight' : '' }}
                        {{ $selected && $selected->id === $answer->id && !$answer->is_correct ? 'answer-wrong-highlight' : '' }}">
                        <span class="review-answer-marker">
                            @if($answer->is_correct)
                                ✓
                            @elseif($selected && $selected->id === $answer->id)
                                ✗
                            @else
                                ○
                            @endif
                        </span>
                        {{ $answer->content }}
                        @if($answer->is_correct)
                            <span class="answer-tag tag-correct">Đáp án đúng</span>
                        @elseif($selected && $selected->id === $answer->id)
                            <span class="answer-tag tag-wrong">Bạn chọn</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @if(!$selected)
                <div class="review-unanswered">⚠️ Bạn không trả lời câu này</div>
                @endif
                @if($question->explanation)
                <div class="review-explanation">
                    💡 <strong>Giải thích:</strong> {{ $question->explanation }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
