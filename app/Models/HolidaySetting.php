<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_start',
        'date_end',
        'is_active',
        'animation_type',
        'animation_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_start' => 'date',
        'date_end' => 'date',
        'animation_config' => 'array',
    ];

    const ANIMATION_SNOW = 'snow';
    const ANIMATION_SANTA = 'santa';
    const ANIMATION_EASTER = 'easter';
    const ANIMATION_HALLOWEEN = 'halloween';
    const ANIMATION_CHRISTMAS = 'christmas';
    const ANIMATION_NEW_YEAR = 'new_year';
}

