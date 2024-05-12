<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    public static $STATUS = [
        'DIAJUKAN',
        'PROSES',
        'SELESAI',
        'DIBATALKAN'
    ];

    protected $fillable = [
        'user_id',
        'problem',
        'status',
        'code',
        'date',
        'note'
    ];

    public function items()
    {
        return $this->hasMany(ProblemItem::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);    
    }

    public static function generateLetterNumber($prefix="SURAT")
    {
        return $prefix . '/' . date('Ymd') . '/' . Problem::count();
    }
    
}
