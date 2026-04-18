@extends('layouts.app')
@section('title', 'Quản lý Quiz')
@section('page-title', 'Quản lý Quiz')

@section('topbar-actions')
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary btn-sm">
        ➕ Tạo Quiz mới
    </a>
@endsection

@section('content')
{{-- Filter bar --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" class="form-input"
                       placeholder="🔍 Tìm kiếm quiz..." value="{{ request('search') }}">
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
                    <option value="">Tất cả độ khó</option>
                    <option value="easy"   {{ request('difficulty') === 'easy'   ? 'selected' : '' }}>Dễ</option>
                    <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Trung bình</option>
                    <option value="hard"   {{ request('difficulty') === 'hard'   ? 'selected' : '' }}>Khó</option>
                </select>
            </div>
            <div class="filter-group">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary btn-sm">Lọc</button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline btn-sm">Xóa lọc</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Tên Quiz</th>
                    <th>Danh mục</th>
                    <th>Câu hỏi</th>
                    <th>Độ khó</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Lượt làm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td>
                        <div class="table-title">{{ $quiz->title }}</div>
                        <div class="table-sub text-muted">{{ Str::limit($quiz->description, 40) }}</div>
                    </td>
                    <td>
                        <span class="category-dot" style="background:{{ $quiz->category->color ?? '#888' }}"></span>
                        {{ $quiz->category->name ?? '—' }}
                    </td>
                    <td class="text-center">{{ $quiz->questions_count }}</td>
                    <td>
                        <span class="badge badge-{{ $quiz->getDifficultyColor() }}">
                            {{ $quiz->getDifficultyLabel() }}
                        </span>
                    </td>
                    <td>{{ $quiz->getTimeLimitLabel() }}</td>
                    <td>
                        <span class="badge {{ $quiz->is_published ? 'badge-success' : 'badge-secondary' }}">
                            {{ $quiz->is_published ? '✓ Xuất bản' : '○ Nháp' }}
                        </span>
                    </td>
                    <td class="text-center">{{ $quiz->attempts()->count() }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.quizzes.show', $quiz) }}"
                               class="btn-icon" title="Xem chi tiết">👁</a>
                            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}"
                               class="btn-icon" title="Quản lý câu hỏi">❓</a>
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                               class="btn-icon" title="Chỉnh sửa">✏️</a>
                            <form method="POST"
                                  action="{{ route('admin.quizzes.toggle-publish', $quiz) }}"
                                  style="display:inline">
                                @csrf
                                <button type="submit" class="btn-icon"
                                        title="{{ $quiz->is_published ? 'Ẩn' : 'Xuất bản' }}">
                                    {{ $quiz->is_published ? '🔒' : '🔓' }}
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.quizzes.destroy', $quiz) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Xóa quiz này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Xóa">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-8">
                        Chưa có quiz nào. <a href="{{ route('admin.quizzes.create') }}">Tạo quiz đầu tiên</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quizzes->hasPages())
    <div class="card-footer">
        {{ $quizzes->links() }}
    </div>
    @endif
</div>
@endsection
