<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendDailyNewsletter extends Command
{
    protected $signature = 'newsletter:send';
    protected $description = 'Send daily Egypt news to all users';

    public function handle(): void
    {
        $this->info('📨 Sending daily newsletter to users...');

        // 1. Try fetching Egypt news
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'country' => 'eg',
            'apiKey' => env('NEWS_API_KEY'),
        ]);

        if ($response->failed()) {
            $this->error('❌ Failed to fetch news for Egypt');
            return;
        }

        $articles = $response->json('articles') ?? [];

        // 2. Fallback to US news if Egypt has none
        if (empty($articles)) {
            $this->warn('⚠️ No Egypt news found. Trying US headlines...');

            $fallback = Http::get('https://newsapi.org/v2/top-headlines', [
                'country' => 'us',
                'apiKey' => env('NEWS_API_KEY'),
            ]);

            if ($fallback->failed()) {
                $this->error('❌ Failed to fetch fallback news');
                return;
            }

            $articles = $fallback->json('articles') ?? [];
        }

        // 3. Still no articles after fallback
        if (empty($articles)) {
            $this->warn('ℹ️ Still no articles available to send.');
            return;
        }

        $topArticle = $articles[0];

        // 4. Send email to users
        $users = User::all();

        foreach ($users as $user) {
            Mail::raw("📰 {$topArticle['title']}\n\n{$topArticle['description']}\n\n{$topArticle['url']}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('📰 Today\'s News Update');
            });

            $this->info("✅ Sent to: {$user->email}");
        }

        $this->info('🎉 Newsletter sent to all users successfully!');
    }
}
