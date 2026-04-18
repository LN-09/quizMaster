{{-- Shared form fields for create & edit --}}
<div class="form-grid-2">
    <div class="form-group form-span-2">
        <label class="form-label" for="title">Tên Quiz <span class="required">*</span></label>
        <input type="text" id="title" name="title"
               class="form-input @error('title') is-error @enderror"
               value="{{ old('title', $quiz?->title) }}"
               placeholder="Ví dụ: PHP Cơ Bản - Bài Kiểm Tra" required>
        @error('title')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="category_id">Danh mục <span class="required">*</span></label>
        <select id="category_id" name="category_id"
                class="form-select @error('category_id') is-error @enderror" required>
            <option value="">— Chọn danh mục —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('category_id', $quiz?->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="difficulty">Độ khó <span class="required">*</span></label>
        <select id="difficulty" name="difficulty" class="form-select" required>
            <option value="easy"   {{ old('difficulty', $quiz?->difficulty) === 'easy'   ? 'selected' : '' }}>😊 Dễ</option>
            <option value="medium" {{ old('difficulty', $quiz?->difficulty) === 'medium' ? 'selected' : '' }}>😐 Trung bình</option>
            <option value="hard"   {{ old('difficulty', $quiz?->difficulty) === 'hard'   ? 'selected' : '' }}>😤 Khó</option>
        </select>
    </div>

    <div class="form-group form-span-2">
        <label class="form-label" for="description">Mô tả</label>
        <textarea id="description" name="description" class="form-textarea" rows="3"
                  placeholder="Mô tả nội dung và mục tiêu của quiz...">{{ old('description', $quiz?->description) }}</textarea>
    </div>

    <div class="form-group">
        <label class="form-label" for="time_limit">
            Giới hạn thời gian (phút)
            <span class="form-hint">Để trống = không giới hạn</span>
        </label>
        <input type="number" id="time_limit" name="time_limit"
               class="form-input" min="1" max="360"
               value="{{ old('time_limit', $quiz?->time_limit) }}"
               placeholder="Ví dụ: 30">
    </div>

    <div class="form-group">
        <label class="form-label" for="passing_score">
            Điểm đạt (%) <span class="required">*</span>
        </label>
        <input type="number" id="passing_score" name="passing_score"
               class="form-input" min="0" max="100"
               value="{{ old('passing_score', $quiz?->passing_score ?? 60) }}" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="max_attempts">
            Số lần làm tối đa
            <span class="form-hint">0 = không giới hạn</span>
        </label>
        <input type="number" id="max_attempts" name="max_attempts"
               class="form-input" min="0"
               value="{{ old('max_attempts', $quiz?->max_attempts ?? 0) }}" required>
    </div>
</div>

<div class="form-section-title">Cài đặt bổ sung</div>
<div class="toggle-group">
    <label class="toggle-item">
        <input type="checkbox" name="shuffle_questions" value="1"
               {{ old('shuffle_questions', $quiz?->shuffle_questions) ? 'checked' : '' }}>
        <span class="toggle-label">🔀 Xáo trộn câu hỏi</span>
    </label>
    <label class="toggle-item">
        <input type="checkbox" name="shuffle_answers" value="1"
               {{ old('shuffle_answers', $quiz?->shuffle_answers) ? 'checked' : '' }}>
        <span class="toggle-label">🔀 Xáo trộn đáp án</span>
    </label>
    <label class="toggle-item">
        <input type="checkbox" name="show_result_immediately" value="1"
               {{ old('show_result_immediately', $quiz?->show_result_immediately ?? true) ? 'checked' : '' }}>
        <span class="toggle-label">📊 Hiện kết quả ngay sau khi nộp</span>
    </label>
    <label class="toggle-item">
        <input type="checkbox" name="is_published" value="1"
               {{ old('is_published', $quiz?->is_published) ? 'checked' : '' }}>
        <span class="toggle-label">🌐 Xuất bản (học sinh có thể làm bài)</span>
    </label>
</div>
