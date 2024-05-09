<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    public static $STATUS = [
        'SELESAI',
        'DIAJUKAN',
        'PROSES',
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

    public function user() 
    {
        return $this->belongsTo(User::class);    
    }
    
}
