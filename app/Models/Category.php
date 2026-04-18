<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'color'];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // ─── Relationships ────────────────────────────────────────────
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────
    public function getPublishedQuizzesCount(): int
    {
        return $this->quizzes()->where('is_published', true)->count();
    }
}
