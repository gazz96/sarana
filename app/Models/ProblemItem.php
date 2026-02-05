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
        'note',
        'photos'
    ];

    protected $casts = [
        'photos' => 'array',
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

    public function addPhoto($photoPath)
    {
        $photos = $this->photos ?? [];
        $photos[] = $photoPath;
        $this->update(['photos' => $photos]);
    }

    public function removePhoto($photoPath)
    {
        $photos = $this->photos ?? [];
        $key = array_search($photoPath, $photos);
        if ($key !== false) {
            unset($photos[$key]);
            $this->update(['photos' => array_values($photos)]);
        }
    }

    public function getFirstPhotoAttribute()
    {
        return $this->photos[0] ?? null;
    }

    public function hasPhotos()
    {
        return !empty($this->photos) && count($this->photos) > 0;
    }
}
