<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    protected $fillable = [
        'user_id',
        'template_id',
        'text_content',
        'status',
        'output_path',
        'cost',
        'error_message',
        'source_type',
        'quran_surah',
        'quran_ayah_from',
        'quran_ayah_to',
        'reciter',
        'arabic_text',
        'translation_text',
        'reference_text',
        'image_template_id',
    ];

    protected $casts = [
        'cost' => 'float',
    ];

    public function template()
    {
        return $this->belongsTo(VideoTemplate::class, 'template_id');
    }

    public function imageTemplate()
    {
        return $this->belongsTo(ImageTemplate::class, 'image_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
