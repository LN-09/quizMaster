@extends('layouts.app')
@section('title', 'Danh mục')
@section('page-title', 'Quản lý Danh mục')

@section('topbar-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">➕ Thêm danh mục</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Danh mục</th>
                    <th>Mô tả</th>
                    <th class="text-center">Số Quiz</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem">
                            <span class="color-swatch" style="background:{{ $category->color }}"></span>
                            <strong>{{ $category->name }}</strong>
                        </div>
                    </td>
                    <td class="text-muted">{{ Str::limit($category->description, 60) ?? '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.quizzes.index', ['category' => $category->id]) }}"
                           class="badge badge-secondary">{{ $category->quizzes_count }}</a>
                    </td>
                    <td class="text-muted">{{ $category->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="btn-icon" title="Sửa">✏️</a>
                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Xóa danh mục {{ $category->name }}?')"
                                  style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Xóa">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-8">
                        Chưa có danh mục nào.
                        <a href="{{ route('admin.categories.create') }}">Tạo danh mục đầu tiên</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="card-footer">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
