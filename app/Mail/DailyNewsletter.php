<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\EmailCampaign;

class DailyNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $articles;
    public $user;
    public $campaign;

    public function __construct($articles, User $user, EmailCampaign $campaign)
    {
        $this->articles = $articles;
        $this->user = $user;
        $this->campaign = $campaign;
    }

    public function build()
    {
        return $this->subject('📰 Your Daily Egypt Newsletter')
                    ->markdown('emails.newsletter');
    }
}
