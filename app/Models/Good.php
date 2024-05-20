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
        'code',
        'location_id',
        'merk',
        'detail',
        'status'
    ];


    public function location()
    {
        return $this->belongsTo(location::class);
    }

    public static function generateAutoNumber($prefix = "BRG", $delimeter = "-")
    {
        $lastest = Good::orderBy('code', 'DESC')->first();
        
        if(!$lastest) {
            return "{$prefix}{$delimeter}1";
        }

        $no = str_replace($prefix . $delimeter, "", $lastest->code);
        $no++;
        return "{$prefix}{$delimeter}{$no}";
    }

    protected static function booted(): void
    {
        // static::creating(function (Good $good){
        //     $good->code = self::generateAutoNumber();
        // });

        static::saving(function(Good $good){
            if(!$good->code) {
                $good->code = self::generateAutoNumber();
            }
        });
    }
}
