@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', 'Chi tiết Quiz')

@section('content')
<div style="max-width:700px;margin:0 auto">
    <div class="card">
        <div class="card-body">
            <div class="quiz-intro-header">
                <span class="category-pill" style="background:{{ $quiz->category->color }}20;color:{{ $quiz->category->color }}">
                    {{ $quiz->category->name }}
                </span>
                <span class="badge badge-{{ $quiz->getDifficultyColor() }}">{{ $quiz->getDifficultyLabel() }}</span>
            </div>
            <h2 class="quiz-intro-title">{{ $quiz->title }}</h2>
            @if($quiz->description)
            <p class="quiz-intro-desc">{{ $quiz->description }}</p>
            @endif

            <div class="quiz-info-grid">
                <div class="quiz-info-item">
                    <div class="quiz-info-icon">❓</div>
                    <div class="quiz-info-val">{{ $quiz->getQuestionsCount() }}</div>
                    <div class="quiz-info-label">Câu hỏi</div>
                </div>
                <div class="quiz-info-item">
                    <div class="quiz-info-icon">⏱</div>
                    <div class="quiz-info-val">{{ $quiz->getTimeLimitLabel() }}</div>
                    <div class="quiz-info-label">Thời gian</div>
                </div>
                <div class="quiz-info-item">
                    <div class="quiz-info-icon">🎯</div>
                    <div class="quiz-info-val">{{ $quiz->passing_score }}%</div>
                    <div class="quiz-info-label">Điểm đạt</div>
                </div>
                <div class="quiz-info-item">
                    <div class="quiz-info-icon">🔁</div>
                    <div class="quiz-info-val">
                        {{ $quiz->max_attempts === 0 ? '∞' : $attemptsCount . '/' . $quiz->max_attempts }}
                    </div>
                    <div class="quiz-info-label">Số lần thi</div>
                </div>
            </div>

            @if($bestScore !== null)
            <div class="best-score-banner {{ $bestScore >= $quiz->passing_score ? 'banner-success' : 'banner-warning' }}">
                🏆 Điểm tốt nhất của bạn: <strong>{{ number_format($bestScore, 1) }}%</strong>
                — {{ $bestScore >= $quiz->passing_score ? '✓ Đã đạt yêu cầu' : 'Chưa đạt' }}
            </div>
            @endif

            <div class="quiz-rules">
                <h4>📋 Lưu ý trước khi làm bài:</h4>
                <ul>
                    @if($quiz->time_limit)
                    <li>Thời gian làm bài: <strong>{{ $quiz->time_limit }} phút</strong>. Hết giờ sẽ tự động nộp.</li>
                    @else
                    <li>Không giới hạn thời gian làm bài.</li>
                    @endif
                    <li>Điểm đạt yêu cầu: <strong>{{ $quiz->passing_score }}%</strong></li>
                    @if($quiz->shuffle_questions)<li>Câu hỏi sẽ được xáo trộn ngẫu nhiên.</li>@endif
                    @if($quiz->shuffle_answers)<li>Đáp án sẽ được xáo trộn ngẫu nhiên.</li>@endif
                    @if($quiz->show_result_immediately)<li>Kết quả sẽ được hiển thị ngay sau khi nộp bài.</li>@endif
                    @if($quiz->max_attempts > 0)
                    <li>Bạn còn <strong>{{ $quiz->max_attempts - $attemptsCount }}</strong> lượt làm.</li>
                    @endif
                </ul>
            </div>

            <div class="quiz-intro-actions">
                <a href="{{ route('student.quizzes.index') }}" class="btn btn-outline">← Quay lại</a>
                @if($canAttempt)
                <form method="POST" action="{{ route('student.quizzes.start', $quiz) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ $attemptsCount > 0 ? '🔄 Làm lại bài' : '▶ Bắt đầu làm bài' }}
                    </button>
                </form>
                @else
                <button class="btn btn-secondary btn-lg" disabled>🔒 Đã hết lượt thi</button>
                @endif
            </div>
        </div>
    </div>

    @if($previousAttempts->count() > 0)
    <div class="card mt-4">
        <div class="card-header"><h3 class="card-title">Lịch sử làm bài</h3></div>
        <div class="card-body p-0">
            <table class="table">
                <thead><tr><th>#</th><th>Điểm</th><th>Kết quả</th><th>Thời gian</th><th></th></tr></thead>
                <tbody>
                    @foreach($previousAttempts as $i => $attempt)
                    <tr>
                        <td>Lần {{ $i + 1 }}</td>
                        <td><strong>{{ number_format($attempt->score, 1) }}%</strong></td>
                        <td>
                            <span class="badge badge-{{ $attempt->passed ? 'success' : 'danger' }}">
                                {{ $attempt->passed ? '✓ Đạt' : '✗ Chưa đạt' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('student.results.show', $attempt) }}"
                               class="btn btn-sm btn-outline">Xem kết quả</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
