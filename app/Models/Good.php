<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    public static $STATUS = ['AKTIF', 'TIDAK AKTIF'];

    protected $fillable = [
        'name',
        'location_id',
        'merk',
        'detail',
        'status'
    ];


    public function location()
    {
        return $this->belongsTo(location::class);
    }
}
