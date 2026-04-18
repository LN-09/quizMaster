// ─── Quiz Take Page ─────────────────────────────────────────────────
(function () {
    const container   = document.getElementById('quizTake');
    if (!container) return;

    const saveUrl     = container.dataset.saveUrl;
    const submitUrl   = container.dataset.submitUrl;
    const timeLimitMin = parseInt(container.dataset.timeLimit) || 0;
    let remainingSec   = parseInt(container.dataset.remaining) || 0;
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;

    // ─── Progress tracking ──────────────────────────────────────────
    const questions   = document.querySelectorAll('.question-block');
    const totalQ      = questions.length;

    function countAnswered() {
        let count = 0;
        questions.forEach(q => {
            const qId = q.dataset.questionId;
            if (document.querySelector(`input[name="answers[${qId}]"]:checked`)) count++;
        });
        return count;
    }

    function updateProgress() {
        const answered = countAnswered();
        const pct      = totalQ > 0 ? (answered / totalQ) * 100 : 0;
        document.getElementById('progressBar').style.width = pct + '%';
        document.getElementById('answeredCount').textContent  = answered;
        document.getElementById('summaryAnswered').textContent = answered;
    }

    // ─── Auto-save answers via AJAX ─────────────────────────────────
    let saveQueue = {};
    let saveTimer = null;

    function saveAnswer(questionId, answerId) {
        saveQueue[questionId] = answerId;
        clearTimeout(saveTimer);
        saveTimer = setTimeout(flushSaveQueue, 600);
    }

    async function flushSaveQueue() {
        const entries = Object.entries(saveQueue);
        saveQueue = {};
        for (const [questionId, answerId] of entries) {
            try {
                await fetch(saveUrl, {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ question_id: questionId, answer_id: answerId }),
                });
            } catch (err) {
                console.error('Save answer failed:', err);
            }
        }
    }

    // Listen for answer selections
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            // Update selected style
            const qId = this.dataset.questionId;
            document.querySelectorAll(`input[data-question-id="${qId}"]`).forEach(r => {
                r.closest('.answer-option').classList.toggle('selected', r === this);
            });
            saveAnswer(qId, this.value);
            updateProgress();
        });
    });

    // ─── Timer ──────────────────────────────────────────────────────
    const timerEl = document.getElementById('timerDisplay');
    const timerContainer = document.getElementById('quizTimer');

    function formatTime(sec) {
        const m = Math.floor(sec / 60).toString().padStart(2, '0');
        const s = (sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    let timerInterval = null;
    if (timeLimitMin > 0 && timerEl) {
        timerEl.textContent = formatTime(remainingSec);

        timerInterval = setInterval(() => {
            remainingSec--;
            timerEl.textContent = formatTime(Math.max(0, remainingSec));

            if (remainingSec <= 60) timerContainer?.classList.add('urgent');

            if (remainingSec <= 0) {
                clearInterval(timerInterval);
                // Auto-submit
                document.getElementById('quizForm').submit();
            }
        }, 1000);
    }

    // ─── Submit modal ───────────────────────────────────────────────
    window.submitQuiz = function () {
        const answered   = countAnswered();
        const unanswered = totalQ - answered;

        document.getElementById('modalAnswered').textContent  = answered;
        document.getElementById('unansweredCount').textContent = unanswered;
        document.getElementById('unansweredWarning').style.display = unanswered > 0 ? '' : 'none';
        document.getElementById('submitModal').style.display = '';
    };

    window.closeModal = function () {
        document.getElementById('submitModal').style.display = 'none';
    };

    window.confirmSubmit = async function () {
        // Flush any pending saves first
        clearTimeout(saveTimer);
        await flushSaveQueue();
        document.getElementById('quizForm').submit();
    };

    // Initial progress update
    updateProgress();
})();
