{{-- Shared question form --}}
<div class="form-group">
    <label class="form-label" for="content">Nội dung câu hỏi <span class="required">*</span></label>
    <textarea id="content" name="content" class="form-textarea @error('content') is-error @enderror"
              rows="3" placeholder="Nhập nội dung câu hỏi..." required>{{ old('content', $question?->content) }}</textarea>
    @error('content')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-grid-2">
    <div class="form-group">
        <label class="form-label" for="type">Loại câu hỏi <span class="required">*</span></label>
        <select id="type" name="type" class="form-select" required id="questionType">
            <option value="single"     {{ old('type', $question?->type) === 'single'     ? 'selected' : '' }}>
                ◉ Một đáp án đúng
            </option>
            <option value="multiple"   {{ old('type', $question?->type) === 'multiple'   ? 'selected' : '' }}>
                ☑ Nhiều đáp án đúng
            </option>
            <option value="true_false" {{ old('type', $question?->type) === 'true_false' ? 'selected' : '' }}>
                ✓✗ Đúng / Sai
            </option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="points">Điểm <span class="required">*</span></label>
        <input type="number" id="points" name="points" class="form-input"
               min="1" max="100" value="{{ old('points', $question?->points ?? 1) }}" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="explanation">Giải thích đáp án (hiện sau khi làm)</label>
    <textarea id="explanation" name="explanation" class="form-textarea" rows="2"
              placeholder="Giải thích tại sao đáp án đúng...">{{ old('explanation', $question?->explanation) }}</textarea>
</div>

<hr class="form-divider">

<div class="form-group" id="answersSection">
    <div class="answers-header">
        <label class="form-label">Các đáp án <span class="required">*</span></label>
        <button type="button" class="btn btn-sm btn-outline" id="addAnswer">➕ Thêm đáp án</button>
    </div>
    @error('answers')<span class="form-error">{{ $message }}</span>@enderror

    <div id="answersList">
        @php
            $existingAnswers = old('answers', $question?->answers?->toArray() ?? [
                ['content' => '', 'is_correct' => false],
                ['content' => '', 'is_correct' => false],
                ['content' => '', 'is_correct' => false],
                ['content' => '', 'is_correct' => false],
            ]);
        @endphp

        @foreach($existingAnswers as $i => $answer)
        <div class="answer-row" data-index="{{ $i }}">
            <div class="answer-correct-toggle">
                <input type="checkbox" name="answers[{{ $i }}][is_correct]"
                       value="1" id="correct_{{ $i }}"
                       class="answer-correct-cb"
                       {{ !empty($answer['is_correct']) ? 'checked' : '' }}>
                <label for="correct_{{ $i }}" class="correct-label" title="Đánh dấu là đáp án đúng">✓</label>
            </div>
            <input type="text" name="answers[{{ $i }}][content]"
                   class="form-input answer-content"
                   value="{{ $answer['content'] ?? '' }}"
                   placeholder="Nội dung đáp án {{ $i + 1 }}"
                   required>
            <button type="button" class="btn-icon btn-icon-danger remove-answer" title="Xóa đáp án">✕</button>
        </div>
        @endforeach
    </div>
    <p class="form-hint" id="correctHint">Tích ✓ vào ô xanh để đánh dấu đáp án đúng</p>
</div>
