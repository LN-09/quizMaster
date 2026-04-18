<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'content', 'type', 'explanation', 'points', 'order',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('order');
    }

    public function correctAnswers()
    {
        return $this->hasMany(Answer::class)->where('is_correct', true);
    }

    public function attemptAnswers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'single'     => 'Một đáp án',
            'multiple'   => 'Nhiều đáp án',
            'true_false' => 'Đúng / Sai',
            default      => 'Không xác định',
        };
    }

    public function isCorrectAnswer(int $answerId): bool
    {
        return $this->correctAnswers()->where('id', $answerId)->exists();
    }
}
