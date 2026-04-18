<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_attempts' => QuizAttempt::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'timed_out'])->count(),
            'passed'         => QuizAttempt::where('user_id', $user->id)
                ->where('passed', true)->count(),
            'avg_score'      => QuizAttempt::where('user_id', $user->id)
                ->where('status', 'completed')->avg('score') ?? 0,
            'best_score'     => QuizAttempt::where('user_id', $user->id)
                ->where('status', 'completed')->max('score') ?? 0,
        ];

        $recentAttempts = QuizAttempt::with('quiz.category')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'timed_out'])
            ->latest()
            ->take(5)
            ->get();

        $availableQuizzes = Quiz::published()
            ->with('category')
            ->withCount('questions')
            ->latest()
            ->take(6)
            ->get();

        $availableQuizzes->each(function ($quiz) use ($user) {
            $quiz->user_best_score  = $quiz->getUserBestScore($user->id);
            $quiz->user_can_attempt = $quiz->canUserAttempt($user);
        });

        $categories = Category::withCount(['quizzes' => fn($q) => $q->published()])->get();

        return view('student.dashboard', compact(
            'stats', 'recentAttempts', 'availableQuizzes', 'categories'
        ));
    }
}
