<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'subject',
        'total_recipients',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function interactions()
    {
        return $this->hasMany(EmailInteraction::class, 'campaign_id');
    }

    public function opens()
    {
        return $this->interactions()->where('type', 'open');
    }

    public function clicks()
    {
        return $this->interactions()->where('type', 'click');
    }

    public function getOpenRateAttribute()
    {
        if ($this->total_recipients == 0) return 0;
        return round(($this->opens()->count() / $this->total_recipients) * 100, 2);
    }

    public function getClickRateAttribute()
    {
        if ($this->total_recipients == 0) return 0;
        return round(($this->clicks()->count() / $this->total_recipients) * 100, 2);
    }
}
