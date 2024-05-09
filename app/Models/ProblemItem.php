<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'problem_id',
        'good_id',
        'problem',
        'price',
        'status',
        'note'
    ];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
