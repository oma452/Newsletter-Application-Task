<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailyNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $news;

    public function __construct($news)
    {
        $this->news = $news;
    }

    public function build()
    {
        return $this->subject('📰 Your Daily Egypt Newsletter')
                    ->markdown('emails.newsletter');
    }
}
