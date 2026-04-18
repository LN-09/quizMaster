@extends('layouts.app')
@section('title', 'Sửa danh mục')
@section('page-title', 'Chỉnh sửa danh mục')

@section('content')
<div class="card" style="max-width:540px;margin:0 auto">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label" for="name">Tên danh mục <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                       class="form-input @error('name') is-error @enderror"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="color">Màu sắc</label>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <input type="color" id="color" name="color"
                           class="color-picker"
                           value="{{ old('color', $category->color) }}">
                    <span class="text-muted">Chọn màu đại diện</span>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">✓ Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection
