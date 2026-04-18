<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'content', 'is_correct', 'order'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
