@extends('layouts.app')
@section('title', 'Thêm danh mục')
@section('page-title', 'Thêm danh mục mới')

@section('content')
<div class="card" style="max-width:540px;margin:0 auto">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Tên danh mục <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                       class="form-input @error('name') is-error @enderror"
                       value="{{ old('name') }}" placeholder="Ví dụ: Lập trình PHP" required autofocus>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-textarea" rows="3"
                          placeholder="Mô tả ngắn về danh mục...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="color">Màu sắc</label>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <input type="color" id="color" name="color"
                           class="color-picker"
                           value="{{ old('color', '#6366f1') }}">
                    <span class="text-muted">Chọn màu đại diện cho danh mục</span>
                </div>
                @error('color')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">✓ Tạo danh mục</button>
            </div>
        </form>
    </div>
</div>
@endsection
