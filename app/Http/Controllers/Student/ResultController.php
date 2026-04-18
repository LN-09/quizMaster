<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $attempts = QuizAttempt::with('quiz.category')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'timed_out'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total'      => $attempts->total(),
            'passed'     => QuizAttempt::where('user_id', Auth::id())->where('passed', true)->count(),
            'avg_score'  => QuizAttempt::where('user_id', Auth::id())
                ->where('status', 'completed')->avg('score') ?? 0,
            'best_score' => QuizAttempt::where('user_id', Auth::id())
                ->where('status', 'completed')->max('score') ?? 0,
        ];

        return view('student.results.index', compact('attempts', 'stats'));
    }

    public function show(QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === Auth::id(), 403);

        // Auto-finalize timed-out attempts
        if ($attempt->status === 'in_progress' && $attempt->isTimedOut()) {
            $attempt->finalize();
            $attempt->refresh();
        }

        $attempt->load([
            'quiz.questions.answers',
            'attemptAnswers.answer',
            'attemptAnswers.question.answers',
        ]);

        $questionsWithResult = $attempt->quiz->questions->map(function ($question) use ($attempt) {
            $attemptAnswer = $attempt->attemptAnswers
                ->firstWhere('question_id', $question->id);

            return [
                'question'      => $question,
                'selected'      => $attemptAnswer?->answer,
                'is_correct'    => $attemptAnswer?->is_correct ?? false,
                'correct_answers' => $question->correctAnswers,
            ];
        });

        return view('student.results.show', compact('attempt', 'questionsWithResult'));
    }
}
