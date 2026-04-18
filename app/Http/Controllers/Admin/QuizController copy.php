<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with(['category', 'creator'])
            ->withCount('questions');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $quizzes    = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.quizzes.index', compact('quizzes', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                   => 'required|string|max:255',
            'category_id'             => 'required|exists:categories,id',
            'description'             => 'nullable|string',
            'time_limit'              => 'nullable|integer|min:1|max:360',
            'passing_score'           => 'required|integer|min:0|max:100',
            'max_attempts'            => 'required|integer|min:0',
            'difficulty'              => 'required|in:easy,medium,hard',
            'shuffle_questions'       => 'boolean',
            'shuffle_answers'         => 'boolean',
            'show_result_immediately' => 'boolean',
            'is_published'            => 'boolean',
        ]);

        $validated['created_by']              = Auth::id();
        $validated['shuffle_questions']       = $request->boolean('shuffle_questions');
        $validated['shuffle_answers']         = $request->boolean('shuffle_answers');
        $validated['show_result_immediately'] = $request->boolean('show_result_immediately');
        $validated['is_published']            = $request->boolean('is_published');

        $quiz = Quiz::create($validated);

        return redirect()
            ->route('admin.quizzes.questions.index', $quiz)
            ->with('success', "Quiz \"{$quiz->title}\" đã được tạo! Bây giờ hãy thêm câu hỏi.");
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'creator', 'questions.answers']);
        $stats = [
            'total_attempts'   => $quiz->attempts()->count(),
            'completed'        => $quiz->attempts()->where('status', 'completed')->count(),
            'avg_score'        => $quiz->getAverageScore(),
            'pass_rate'        => $quiz->attempts()->where('status', 'completed')->count() > 0
                ? $quiz->attempts()->where('passed', true)->count() /
                  $quiz->attempts()->where('status', 'completed')->count() * 100
                : 0,
        ];

        return view('admin.quizzes.show', compact('quiz', 'stats'));
    }

    public function edit(Quiz $quiz)
    {
        $categories = Category::all();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'                   => 'required|string|max:255',
            'category_id'             => 'required|exists:categories,id',
            'description'             => 'nullable|string',
            'time_limit'              => 'nullable|integer|min:1|max:360',
            'passing_score'           => 'required|integer|min:0|max:100',
            'max_attempts'            => 'required|integer|min:0',
            'difficulty'              => 'required|in:easy,medium,hard',
            'shuffle_questions'       => 'boolean',
            'shuffle_answers'         => 'boolean',
            'show_result_immediately' => 'boolean',
            'is_published'            => 'boolean',
        ]);

        $validated['shuffle_questions']       = $request->boolean('shuffle_questions');
        $validated['shuffle_answers']         = $request->boolean('shuffle_answers');
        $validated['show_result_immediately'] = $request->boolean('show_result_immediately');
        $validated['is_published']            = $request->boolean('is_published');

        $quiz->update($validated);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz đã được cập nhật thành công!');
    }

    public function destroy(Quiz $quiz)
    {
        $title = $quiz->title;
        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', "Quiz \"{$title}\" đã được xóa.");
    }

    public function togglePublish(Quiz $quiz)
    {
        $quiz->update(['is_published' => !$quiz->is_published]);

        $status = $quiz->is_published ? 'xuất bản' : 'ẩn';
        return back()->with('success', "Quiz đã được {$status} thành công!");
    }
}
