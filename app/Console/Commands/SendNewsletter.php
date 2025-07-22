<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletter extends Command
{
    protected $signature = 'newsletter:send';
    protected $description = 'Send daily Egypt news to subscribed users';

    public function handle()
    {
        Log::info('✅ newsletter:send command triggered.');
        $this->info('🔄 Fetching Egypt news from NewsAPI...');

        $maxRetries = 3;
        $retryCount = 0;
        $response = null;
        
        while ($retryCount < $maxRetries) {
            try {
                $response = Http::timeout(15)->get('https://newsapi.org/v2/everything', [
                    'q' => 'Egypt',
                    'apiKey' => env('NEWS_API_KEY'),
                    'pageSize' => 20,
                    'sortBy' => 'publishedAt',
                    'language' => 'en',
                ]);
                
                if (!$response->failed()) {
                    break; // Success, exit the retry loop
                }
                
                $retryCount++;
                $this->warn("⚠️ API request failed. Retrying {$retryCount}/{$maxRetries}...");
                sleep(2); // Wait 2 seconds before retrying
                
            } catch (\Exception $e) {
                $retryCount++;
                Log::error("API request exception: {$e->getMessage()}");
                $this->warn("⚠️ API request error. Retrying {$retryCount}/{$maxRetries}...");
                sleep(2); // Wait 2 seconds before retrying
            }
        }
        
        if (!$response || $response->failed()) {
            $this->error('❌ Failed to fetch news after multiple attempts. Check your API key or internet connection.');
            Log::error('NewsAPI request failed: ' . ($response ? $response->body() : 'No response'));
            return 1;
        }

        $articles = $response->json('articles');
        $filteredArticles = [];

        foreach ($articles as $article) {
            $text = strtolower($article['title'] . ' ' . ($article['description'] ?? ''));
            if (!str_contains($text, 'egypt')) {
                continue;
            }
            
            // Format the date for display
            $article['formatted_date'] = date('F j, Y', strtotime($article['publishedAt']));
            $filteredArticles[] = $article;
        }

        if (empty($filteredArticles)) {
            $this->warn('⚠️ No Egypt-related news found today.');
            return 0;
        }

        $this->info('Found ' . count($filteredArticles) . ' articles about Egypt.');
        
        // Get subscribed users only
        $users = User::where('is_subscribed', true)->get();
        
        if ($users->isEmpty()) {
            $this->warn('⚠️ No subscribed users found.');
            return 0;
        }
        
        $this->info('📨 Sending newsletter to ' . $users->count() . ' subscribers...');
        
        // Send emails to each subscribed user
        $failedEmails = [];
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new \App\Mail\DailyNewsletter($filteredArticles, $user));
                $this->line("✅ Sent to: {$user->email}");
            } catch (\Exception $e) {
                $failedEmails[] = $user->email;
                Log::error("Failed to send newsletter to {$user->email}: {$e->getMessage()}");
                $this->error("❌ Failed to send to: {$user->email}");
            }
        }
        
        if (!empty($failedEmails)) {
            $this->warn("⚠️ Failed to send newsletter to " . count($failedEmails) . " users.");
        }

        $this->info('🎉 Newsletter sent to all subscribers successfully!');
        return 0;
    }
}
