<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'created_by', 'title', 'slug', 'description',
        'time_limit', 'passing_score', 'max_attempts', 'difficulty',
        'shuffle_questions', 'shuffle_answers', 'show_result_immediately',
        'is_published',
    ];

    protected $casts = [
        'shuffle_questions'       => 'boolean',
        'shuffle_answers'         => 'boolean',
        'show_result_immediately' => 'boolean',
        'is_published'            => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Quiz $quiz) {
            $quiz->slug = Str::slug($quiz->title) . '-' . Str::random(6);
        });
    }

    // ─── Relationships ────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function getDifficultyLabel(): string
    {
        return match($this->difficulty) {
            'easy'   => 'Dễ',
            'medium' => 'Trung bình',
            'hard'   => 'Khó',
            default  => 'Không xác định',
        };
    }

    public function getDifficultyColor(): string
    {
        return match($this->difficulty) {
            'easy'   => 'success',
            'medium' => 'warning',
            'hard'   => 'danger',
            default  => 'secondary',
        };
    }

    public function getTotalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function getQuestionsCount(): int
    {
        return $this->questions()->count();
    }

    public function getTimeLimitLabel(): string
    {
        if (!$this->time_limit) return 'Không giới hạn';
        return $this->time_limit . ' phút';
    }

    public function canUserAttempt(User $user): bool
    {
        if ($this->max_attempts === 0) return true;

        $completedAttempts = $this->attempts()
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'timed_out'])
            ->count();

        return $completedAttempts < $this->max_attempts;
    }

    public function getUserBestScore(int $userId): ?float
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->max('score');
    }

    public function getAverageScore(): float
    {
        return (float) $this->attempts()
            ->where('status', 'completed')
            ->avg('score') ?? 0;
    }
}
