<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

    public static $STATUS = [
        'DRAFT', // 0 
        'DIAJUKAN', // 1
        'PROSES', // 2
        'SELESAI DIKERJAKAN', // 3
        'DIBATALKAN', // 4
        'MENUNGGU PERSETUJUAN MANAGEMENT', // 5
        'MENUNGGU PERSETUJUAN ADMIN', // 6
        'MENUNGGU PERSETUJUAN KEUANGAN', // 7
    ];

    protected $fillable = [
        'user_id',
        'issue',
        'status',
        'code',
        'date',
        'note',
        'user_technician_id',
        'user_management_id',
        'user_finance_id',
        'admin_id'
    ];

    public function items()
    {
        return $this->hasMany(ProblemItem::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);    
    }

    public function user_management() 
    {
        return $this->belongsTo(User::class, 'user_management_id');    
    }

    public function technician() 
    {
        return $this->belongsTo(User::class, 'user_technician_id');    
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function finance()
    {
        return $this->belongsTo(User::class, 'user_finance_id');
    }

    public static function generateLetterNumber($prefix="SURAT")
    {
        return $prefix . '/' . date('Ymd') . '/' . Problem::count();
    }
    
}
