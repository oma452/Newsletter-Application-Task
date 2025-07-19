<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\News;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletter extends Command
{
    protected $signature = 'newsletter:send';
    protected $description = 'Send daily Egypt news to all users';


public function handle()
{
    Log::info('✅ newsletter:send command triggered.');

    $this->info('📨 Sending daily newsletter to users...');
    $news = News::latest()->take(20)->get();
    $users = User::all();

    if ($news->isEmpty()) {
        $this->warn('⚠️ No news to send today.');
        return;
    }

    foreach ($users as $user) {
        Mail::to($user->email)->send(new \App\Mail\DailyNewsletter($news));
        $this->line("✅ Sent to: {$user->email}");
    }

    $this->info('🎉 Newsletter sent to all users successfully!');
}
}