<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'order',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'order' => 'integer',
    ];

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }
}

