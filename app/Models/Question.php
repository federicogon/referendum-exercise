<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    public function referendum(): BelongsTo
    {
        return $this->belongsTo(Referendum::class, 'referendum_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(QuestionVotes::class);
    }
}
