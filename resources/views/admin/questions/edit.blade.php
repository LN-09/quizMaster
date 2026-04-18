@extends('layouts.app')
@section('title', 'Sửa câu hỏi')
@section('page-title', 'Chỉnh sửa câu hỏi')

@section('topbar-actions')
    <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline btn-sm">← Danh sách câu hỏi</a>
@endsection

@section('content')
<div class="card" style="max-width:820px;margin:0 auto">
    <div class="card-header">
        <h3 class="card-title">Quiz: {{ $quiz->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.questions.update', [$quiz, $question]) }}" id="questionForm">
            @csrf @method('PUT')
            @include('admin.questions._form')
            <div class="form-actions">
                <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">✓ Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/question-form.js') }}"></script>
@endpush
