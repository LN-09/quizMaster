<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $quiz->load(['questions.answers']);
        return view('admin.questions.index', compact('quiz'));
    }

    public function create(Quiz $quiz)
    {
        return view('admin.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'content'     => 'required|string',
            'type'        => 'required|in:single,multiple,true_false',
            'explanation' => 'nullable|string',
            'points'      => 'required|integer|min:1|max:100',
            'answers'     => 'required|array|min:2',
            'answers.*.content'    => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        $hasCorrect = collect($request->answers)->contains(fn($a) => !empty($a['is_correct']));
        if (!$hasCorrect) {
            return back()->withErrors(['answers' => 'Phải có ít nhất một đáp án đúng.'])->withInput();
        }

        $question = $quiz->questions()->create([
            'content'     => $validated['content'],
            'type'        => $validated['type'],
            'explanation' => $validated['explanation'] ?? null,
            'points'      => $validated['points'],
            'order'       => $quiz->questions()->max('order') + 1,
        ]);

        foreach ($request->answers as $index => $answerData) {
            $question->answers()->create([
                'content'    => $answerData['content'],
                'is_correct' => !empty($answerData['is_correct']),
                'order'      => $index,
            ]);
        }

        return redirect()
            ->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Câu hỏi đã được thêm thành công!');
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $question->load('answers');
        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'content'     => 'required|string',
            'type'        => 'required|in:single,multiple,true_false',
            'explanation' => 'nullable|string',
            'points'      => 'required|integer|min:1|max:100',
            'answers'     => 'required|array|min:2',
            'answers.*.content'    => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        $hasCorrect = collect($request->answers)->contains(fn($a) => !empty($a['is_correct']));
        if (!$hasCorrect) {
            return back()->withErrors(['answers' => 'Phải có ít nhất một đáp án đúng.'])->withInput();
        }

        $question->update([
            'content'     => $validated['content'],
            'type'        => $validated['type'],
            'explanation' => $validated['explanation'] ?? null,
            'points'      => $validated['points'],
        ]);

        $question->answers()->delete();
        foreach ($request->answers as $index => $answerData) {
            $question->answers()->create([
                'content'    => $answerData['content'],
                'is_correct' => !empty($answerData['is_correct']),
                'order'      => $index,
            ]);
        }

        return redirect()
            ->route('admin.quizzes.questions.index', $quiz)
            ->with('success', 'Câu hỏi đã được cập nhật!');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        $question->delete();
        return back()->with('success', 'Câu hỏi đã được xóa.');
    }

    public function reorder(Request $request, Quiz $quiz)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $questionId) {
            Question::where('id', $questionId)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
