<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionVoters extends Pivot
{
    protected $table = 'question_voters';

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class,'question_id');
    }
}
