<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'video_path',
        'external_video_url',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($video) {
            if ($video->isDirty('video_path')) {
                $oldPath = $video->getOriginal('video_path');
                if ($oldPath && $oldPath !== $video->video_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
        });

        static::deleting(function ($video) {
            if ($video->video_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($video->video_path);
            }
        });
    }
}
