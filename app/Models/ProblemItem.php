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
        'issue',
        'price',
        'status',
        'note'
    ];

    protected static $STATUS = ['MENUNGGU', 'DIPROSES', 'SELESAI', 'TIDAK BISA DI PERBAIKI'];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
