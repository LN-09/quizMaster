@extends('layouts.app')
@section('title', 'Kết quả của tôi')
@section('page-title', 'Lịch sử làm bài')

@section('content')
{{-- Stats --}}
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon stat-blue">📝</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Tổng lượt làm</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-green">✅</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['passed'] }}</div>
            <div class="stat-label">Bài đạt</div>
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

<div class="card">
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Danh mục</th>
                    <th>Điểm</th>
                    <th>Câu đúng</th>
                    <th>Kết quả</th>
                    <th>Thời gian làm</th>
                    <th>Ngày thi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                <tr>
                    <td>
                        <div class="table-title">{{ Str::limit($attempt->quiz->title, 30) }}</div>
                    </td>
                    <td>
                        <span class="category-dot" style="background:{{ $attempt->quiz->category->color ?? '#888' }}"></span>
                        {{ $attempt->quiz->category->name ?? '—' }}
                    </td>
                    <td>
                        <div class="score-pill {{ $attempt->passed ? 'score-pass' : 'score-fail' }}">
                            {{ number_format($attempt->score, 1) }}%
                        </div>
                    </td>
                    <td class="text-center">
                        {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $attempt->passed ? 'success' : 'danger' }}">
                            {{ $attempt->passed ? '✓ Đạt' : '✗ Chưa đạt' }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $attempt->getFormattedTimeSpent() }}</td>
                    <td class="text-muted">{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('student.results.show', $attempt) }}"
                           class="btn btn-sm btn-outline">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-8">
                        Bạn chưa làm bài quiz nào.
                        <a href="{{ route('student.quizzes.index') }}">Bắt đầu ngay →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attempts->hasPages())
    <div class="card-footer">{{ $attempts->links() }}</div>
    @endif
</div>
@endsection
