@extends('layouts.app')
@section('title', 'Đang làm: ' . $quiz->title)
@section('page-title', $quiz->title)

@section('content')
<div class="quiz-take-wrapper" id="quizTake"
     data-attempt-id="{{ $attempt->id }}"
     data-quiz-id="{{ $quiz->id }}"
     data-save-url="{{ route('student.quizzes.save-answer', [$quiz, $attempt]) }}"
     data-submit-url="{{ route('student.quizzes.submit', [$quiz, $attempt]) }}"
     data-time-limit="{{ $quiz->time_limit }}"
     data-remaining="{{ $remainingSeconds }}">

    {{-- Top bar --}}
    <div class="quiz-topbar">
        <div class="quiz-progress-info">
            <span id="answeredCount">0</span>/{{ $questions->count() }} câu đã trả lời
        </div>
        @if($quiz->time_limit)
        <div class="quiz-timer" id="quizTimer">
            ⏱ <span id="timerDisplay">--:--</span>
        </div>
        @endif
        <button type="button" class="btn btn-primary btn-sm" id="submitBtn"
                onclick="submitQuiz()">
            ✓ Nộp bài
        </button>
    </div>

    {{-- Progress bar --}}
    <div class="progress-bar-wrapper">
        <div class="progress-bar-fill" id="progressBar" style="width:0%"></div>
    </div>

    {{-- Questions --}}
    <form id="quizForm" method="POST" action="{{ route('student.quizzes.submit', [$quiz, $attempt]) }}">
        @csrf
        <div class="questions-container">
            @foreach($questions as $index => $question)
            <div class="question-block" id="q{{ $question->id }}" data-question-id="{{ $question->id }}">
                <div class="question-header">
                    <span class="question-number">Câu {{ $index + 1 }}</span>
                    <span class="question-points">{{ $question->points }} điểm</span>
                    <span class="question-type-label">{{ $question->getTypeLabel() }}</span>
                </div>
                <div class="question-text-display">{{ $question->content }}</div>
                <div class="answers-container">
                    @foreach($question->answers as $answer)
                    <label class="answer-option {{ $savedAnswers->get($question->id) == $answer->id ? 'selected' : '' }}"
                           for="a{{ $answer->id }}">
                        <input type="radio"
                               name="answers[{{ $question->id }}]"
                               id="a{{ $answer->id }}"
                               value="{{ $answer->id }}"
                               class="answer-radio"
                               data-question-id="{{ $question->id }}"
                               {{ $savedAnswers->get($question->id) == $answer->id ? 'checked' : '' }}>
                        <span class="option-marker"></span>
                        <span class="option-text">{{ $answer->content }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Submit section --}}
        <div class="submit-section">
            <div class="submit-summary">
                <span id="summaryAnswered">0</span>/{{ $questions->count() }} câu đã trả lời
            </div>
            <button type="button" class="btn btn-primary btn-lg" onclick="submitQuiz()">
                ✓ Nộp bài thi
            </button>
        </div>
    </form>
</div>

{{-- Confirm submit modal --}}
<div class="modal-backdrop" id="submitModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <h3>Xác nhận nộp bài</h3>
        </div>
        <div class="modal-body">
            <p>Bạn đã trả lời <strong id="modalAnswered">0</strong>/{{ $questions->count() }} câu.</p>
            <p id="unansweredWarning" class="text-warning" style="display:none">
                ⚠️ Bạn còn <strong id="unansweredCount">0</strong> câu chưa trả lời!
            </p>
            <p>Bạn có chắc chắn muốn nộp bài?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal()">Tiếp tục làm</button>
            <button type="button" class="btn btn-primary" onclick="confirmSubmit()">✓ Xác nhận nộp</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/quiz-take.js') }}"></script>
@endpush
