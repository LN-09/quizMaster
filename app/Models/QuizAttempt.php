<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'user_id', 'status', 'score',
        'total_points', 'earned_points', 'correct_answers',
        'total_questions', 'passed', 'started_at', 'finished_at', 'time_spent',
    ];

    protected $casts = [
        'passed'      => 'boolean',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'in_progress' => 'Đang làm',
            'completed'   => 'Hoàn thành',
            'timed_out'   => 'Hết giờ',
            default       => 'Không xác định',
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'in_progress' => 'info',
            'completed'   => $this->passed ? 'success' : 'danger',
            'timed_out'   => 'warning',
            default       => 'secondary',
        };
    }

    public function getFormattedScore(): string
    {
        return number_format($this->score, 1) . '%';
    }

    public function getFormattedTimeSpent(): string
    {
        if (!$this->time_spent) return 'N/A';

        $minutes = intdiv($this->time_spent, 60);
        $seconds = $this->time_spent % 60;

        if ($minutes === 0) return "{$seconds}s";
        return "{$minutes}m {$seconds}s";
    }

    public function isTimedOut(): bool
    {
        if (!$this->quiz->time_limit || $this->status !== 'in_progress') {
            return false;
        }

        $deadline = $this->started_at->addMinutes($this->quiz->time_limit);
        return Carbon::now()->isAfter($deadline);
    }

    public function getRemainingSeconds(): int
    {
        if (!$this->quiz->time_limit) return 0;

        $deadline = $this->started_at->addMinutes($this->quiz->time_limit);
        return max(0, Carbon::now()->diffInSeconds($deadline, false));
    }

    /**
     * Calculate and save final score
     */
    public function finalize(): void
    {
        $quiz      = $this->quiz;
        $questions = $quiz->questions()->with('correctAnswers')->get();
        $answers   = $this->attemptAnswers()->with('answer')->get()->keyBy('question_id');

        $totalPoints   = 0;
        $earnedPoints  = 0;
        $correctCount  = 0;

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $attemptAnswer = $answers->get($question->id);

            if ($attemptAnswer && $attemptAnswer->answer && $attemptAnswer->answer->is_correct) {
                $earnedPoints += $question->points;
                $correctCount++;
            }
        }

        $score  = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $score >= $quiz->passing_score;

        $timeSpent = $this->started_at->diffInSeconds(now());

        $this->update([
            'status'          => 'completed',
            'score'           => $score,
            'total_points'    => $totalPoints,
            'earned_points'   => $earnedPoints,
            'correct_answers' => $correctCount,
            'total_questions' => $questions->count(),
            'passed'          => $passed,
            'finished_at'     => now(),
            'time_spent'      => $timeSpent,
        ]);
    }
}
