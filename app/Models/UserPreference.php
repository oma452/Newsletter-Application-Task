<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'categories',
        'keywords',
        'frequency'
    ];

    protected $casts = [
        'categories' => 'array',
        'keywords' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
 