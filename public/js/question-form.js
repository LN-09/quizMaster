// ─── Dynamic Answer Management ──────────────────────────────────────
(function () {
    const answersList = document.getElementById('answersList');
    const addBtn      = document.getElementById('addAnswer');
    const typeSelect  = document.getElementById('type') || document.querySelector('[name="type"]');

    if (!answersList) return;

    function getIndex() {
        return answersList.querySelectorAll('.answer-row').length;
    }

    function createAnswerRow(index, content = '', isCorrect = false) {
        const row = document.createElement('div');
        row.className = 'answer-row';
        row.dataset.index = index;
        row.innerHTML = `
            <div class="answer-correct-toggle">
                <input type="checkbox" name="answers[${index}][is_correct]"
                       value="1" id="correct_${index}"
                       class="answer-correct-cb"
                       ${isCorrect ? 'checked' : ''}>
                <label for="correct_${index}" class="correct-label" title="Đáp án đúng">✓</label>
            </div>
            <input type="text" name="answers[${index}][content]"
                   class="form-input answer-content"
                   value="${content}"
                   placeholder="Nội dung đáp án ${index + 1}"
                   required>
            <button type="button" class="btn-icon btn-icon-danger remove-answer" title="Xóa">✕</button>
        `;
        return row;
    }

    function reindex() {
        answersList.querySelectorAll('.answer-row').forEach((row, i) => {
            row.dataset.index = i;
            const cb    = row.querySelector('.answer-correct-cb');
            const label = row.querySelector('.correct-label');
            const input = row.querySelector('.answer-content');
            const id    = `correct_${i}`;

            cb.name   = `answers[${i}][is_correct]`;
            cb.id     = id;
            label.htmlFor = id;
            input.name = `answers[${i}][content]`;
            input.placeholder = `Nội dung đáp án ${i + 1}`;
        });
    }

    addBtn?.addEventListener('click', () => {
        const idx = getIndex();
        const row = createAnswerRow(idx);
        answersList.appendChild(row);
        row.querySelector('.answer-content').focus();
    });

    answersList.addEventListener('click', e => {
        if (e.target.classList.contains('remove-answer')) {
            const row  = e.target.closest('.answer-row');
            const rows = answersList.querySelectorAll('.answer-row');
            if (rows.length <= 2) {
                alert('Phải có ít nhất 2 đáp án!');
                return;
            }
            row.remove();
            reindex();
        }
    });

    // Handle True/False type: lock to exactly 2 answers
    function handleTypeChange(type) {
        if (type === 'true_false') {
            // Replace all answers with True/False
            answersList.innerHTML = '';
            answersList.appendChild(createAnswerRow(0, 'Đúng', true));
            answersList.appendChild(createAnswerRow(1, 'Sai', false));
            addBtn.disabled = true;
            addBtn.style.opacity = '.4';
        } else {
            addBtn.disabled = false;
            addBtn.style.opacity = '';
        }
    }

    typeSelect?.addEventListener('change', e => handleTypeChange(e.target.value));

    // Init
    if (typeSelect?.value === 'true_false') {
        handleTypeChange('true_false');
    }
})();
