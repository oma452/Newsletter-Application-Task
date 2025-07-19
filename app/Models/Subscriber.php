<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Subscriber extends Model
{

    protected $fillable = ['email', 'user_id'];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
