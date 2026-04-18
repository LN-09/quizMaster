<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttemptAnswer;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with('category')
            ->withCount('questions')
            ->published();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $quizzes    = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::all();

        // Attach best score for current user
        $user = Auth::user();
        $quizzes->each(function ($quiz) use ($user) {
            $quiz->user_best_score = $quiz->getUserBestScore($user->id);
            $quiz->user_can_attempt = $quiz->canUserAttempt($user);
            $quiz->user_attempts_count = $quiz->attempts()
                ->where('user_id', $user->id)
                ->whereIn('status', ['completed', 'timed_out'])
                ->count();
        });

        return view('student.quizzes.index', compact('quizzes', 'categories'));
    }

    public function show(Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);
        $quiz->load(['category', 'questions']);

        $user          = Auth::user();
        $bestScore     = $quiz->getUserBestScore($user->id);
        $canAttempt    = $quiz->canUserAttempt($user);
        $attemptsCount = $quiz->attempts()
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'timed_out'])
            ->count();

        $previousAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'timed_out'])
            ->latest()
            ->take(5)
            ->get();

        return view('student.quizzes.show', compact(
            'quiz', 'bestScore', 'canAttempt', 'attemptsCount', 'previousAttempts'
        ));
    }

    public function start(Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);

        $user = Auth::user();

        if (!$quiz->canUserAttempt($user)) {
            return back()->with('error', 'Bạn đã đạt giới hạn số lần làm bài.');
        }

        // Check for existing in-progress attempt
        $existing = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            if ($existing->isTimedOut()) {
                $existing->update(['status' => 'timed_out']);
            } else {
                return redirect()->route('student.quizzes.take', [$quiz, $existing]);
            }
        }

        $attempt = QuizAttempt::create([
            'quiz_id'    => $quiz->id,
            'user_id'    => $user->id,
            'started_at' => now(),
            'status'     => 'in_progress',
        ]);

        return redirect()->route('student.quizzes.take', [$quiz, $attempt]);
    }

    public function take(Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($quiz->is_published, 404);
        abort_unless($attempt->user_id === Auth::id(), 403);
        abort_unless($attempt->status === 'in_progress', 404);

        if ($attempt->isTimedOut()) {
            $attempt->update(['status' => 'timed_out']);
            return redirect()
                ->route('student.results.show', $attempt)
                ->with('warning', 'Bài làm đã hết giờ và được nộp tự động.');
        }

        $questions = $quiz->questions()->with('answers')->get();
        if ($quiz->shuffle_questions) {
            $questions = $questions->shuffle();
        }
        if ($quiz->shuffle_answers) {
            $questions->each(fn($q) => $q->setRelation('answers', $q->answers->shuffle()));
        }

        $savedAnswers = $attempt->attemptAnswers()->pluck('answer_id', 'question_id');
        $remainingSeconds = $attempt->getRemainingSeconds();

        return view('student.quizzes.take', compact(
            'quiz', 'attempt', 'questions', 'savedAnswers', 'remainingSeconds'
        ));
    }

    public function saveAnswer(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === Auth::id(), 403);
        abort_unless($attempt->status === 'in_progress', 422);

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id'   => 'nullable|exists:answers,id',
        ]);

        AttemptAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'question_id'     => $request->question_id,
            ],
            [
                'answer_id'  => $request->answer_id,
                'is_correct' => $request->answer_id
                    ? (bool) \App\Models\Answer::find($request->answer_id)?->is_correct
                    : false,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function submit(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === Auth::id(), 403);
        abort_unless($attempt->status === 'in_progress', 422);

        $attempt->finalize();

        return redirect()
            ->route('student.results.show', $attempt)
            ->with('success', 'Bài làm đã được nộp thành công!');
    }
}
