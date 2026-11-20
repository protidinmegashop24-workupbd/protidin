<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoMakerSetting extends Model
{
    protected $table = 'video_maker_settings';

    protected $fillable = [
        'google_tts_api_key',
    ];
}
