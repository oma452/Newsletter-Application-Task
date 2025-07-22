<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class DailyNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $articles;
    public $user;

    public function __construct($articles, User $user)
    {
        $this->articles = $articles;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('📰 Your Daily Egypt Newsletter')
                    ->markdown('emails.newsletter');
    }
}
