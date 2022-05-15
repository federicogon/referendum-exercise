<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionVoters extends Pivot
{
    protected $table = 'question_voters';
}
