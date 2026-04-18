<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getAttemptForQuiz(int $quizId): ?QuizAttempt
    {
        return $this->attempts()
            ->where('quiz_id', $quizId)
            ->where('status', 'in_progress')
            ->latest()
            ->first();
    }

    public function getTotalAttempts(): int
    {
        return $this->attempts()->where('status', 'completed')->count();
    }

    public function getAverageScore(): float
    {
        return (float) $this->attempts()
            ->where('status', 'completed')
            ->avg('score') ?? 0;
    }

    public function getPassedQuizzes(): int
    {
        return $this->attempts()
            ->where('status', 'completed')
            ->where('passed', true)
            ->count();
    }
}
