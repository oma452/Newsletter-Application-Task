<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailInteraction extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'type',
        'url'
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
