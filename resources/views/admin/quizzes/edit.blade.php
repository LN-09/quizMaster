@extends('layouts.app')
@section('title', 'Chỉnh sửa Quiz')
@section('page-title', 'Chỉnh sửa Quiz')

@section('content')
<div class="card" style="max-width:760px;margin:0 auto">
    <div class="card-header">
        <h3 class="card-title">{{ $quiz->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}">
            @csrf @method('PUT')
            @include('admin.quizzes._form')
            <div class="form-actions">
                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection
