<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => User::where('role', 'student')->count(),
            'total_quizzes'   => Quiz::count(),
            'total_attempts'  => QuizAttempt::where('status', 'completed')->count(),
            'total_categories'=> Category::count(),
            'published_quizzes' => Quiz::where('is_published', true)->count(),
            'avg_pass_rate'   => QuizAttempt::where('status', 'completed')->count() > 0
                ? QuizAttempt::where('status', 'completed')->where('passed', true)->count()
                  / QuizAttempt::where('status', 'completed')->count() * 100
                : 0,
        ];

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
            ->where('status', 'completed')
            ->latest()
            ->take(8)
            ->get();

        $popularQuizzes = Quiz::withCount(['attempts' => fn($q) => $q->where('status', 'completed')])
            ->where('is_published', true)
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->take(5)
            ->get();

        // Monthly attempts for chart (last 6 months)
        $monthlyData = QuizAttempt::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label' => date('M Y', mktime(0, 0, 0, $r->month, 1, $r->year)),
                'total' => $r->total,
            ]);

        return view('admin.dashboard.index', compact(
            'stats', 'recentAttempts', 'popularQuizzes', 'recentStudents', 'monthlyData'
        ));
    }
}
