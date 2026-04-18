@extends('layouts.app')
@section('title', 'Tạo Quiz mới')
@section('page-title', 'Tạo Quiz mới')

@section('content')
<div class="card" style="max-width:760px;margin:0 auto">
    <div class="card-header">
        <h3 class="card-title">Thông tin Quiz</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.quizzes.store') }}">
            @csrf
            @include('admin.quizzes._form', ['quiz' => null])
            <div class="form-actions">
                <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Tạo Quiz & Thêm câu hỏi →</button>
            </div>
        </form>
    </div>
</div>
@endsection
