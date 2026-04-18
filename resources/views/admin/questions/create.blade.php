@extends('layouts.app')
@section('title', 'Thêm câu hỏi')
@section('page-title', 'Thêm câu hỏi mới')

@section('topbar-actions')
    <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline btn-sm">← Danh sách câu hỏi</a>
@endsection

@section('content')
<div class="card" style="max-width:820px;margin:0 auto">
    <div class="card-header">
        <h3 class="card-title">Quiz: {{ $quiz->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}" id="questionForm">
            @csrf
            @include('admin.questions._form', ['question' => null])
            <div class="form-actions">
                <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-outline">Hủy</a>
                <button type="submit" name="action" value="save_add" class="btn btn-secondary">
                    💾 Lưu & Thêm tiếp
                </button>
                <button type="submit" name="action" value="save" class="btn btn-primary">
                    ✓ Lưu câu hỏi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/question-form.js') }}"></script>
@endpush
