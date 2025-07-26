<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\EmailCampaign;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletter extends Command
{
    protected $signature = 'newsletter:send {frequency=daily : Send to daily or weekly subscribers}';
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
        
        $frequency = $this->argument('frequency');
        
        // Get subscribed users with preferences for the specified frequency
        $users = User::where('is_subscribed', true)
            ->with('preferences')
            ->whereHas('preferences', function($query) use ($frequency) {
                $query->where('frequency', $frequency);
            })
            ->orWhere(function($query) use ($frequency) {
                // Include users without preferences if sending daily
                return $frequency === 'daily' && $query->where('is_subscribed', true)->doesntHave('preferences');
            })
            ->get();
        
        if ($users->isEmpty()) {
            $this->warn('⚠️ No subscribed users found.');
            return 0;
        }
        
        $this->info("📨 Sending {$frequency} personalized newsletter to " . $users->count() . ' subscribers...');
        
        // Create campaign record for analytics
        $campaign = EmailCampaign::create([
            'subject' => "📰 Your " . ucfirst($frequency) . " Egypt Newsletter",
            'total_recipients' => $users->count(),
            'sent_at' => now()
        ]);
        
        // Send personalized emails to each subscribed user
        $failedEmails = [];
        foreach ($users as $user) {
            try {
                // Filter articles based on user preferences
                $personalizedArticles = $this->filterArticlesForUser($filteredArticles, $user);
                
                if (empty($personalizedArticles)) {
                    $personalizedArticles = array_slice($filteredArticles, 0, 3); // Fallback to top 3
                }
                
                Mail::to($user->email)->send(new \App\Mail\DailyNewsletter($personalizedArticles, $user, $campaign));
                $this->line("✅ Sent to: {$user->email} ({" . count($personalizedArticles) . " articles})");
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

    private function filterArticlesForUser($articles, $user)
    {
        $preferences = $user->preferences;
        
        if (!$preferences) {
            return array_slice($articles, 0, 5); // Default to 5 articles
        }

        $filteredArticles = [];
        $categories = $preferences->categories ?? [];
        $keywords = $preferences->keywords ?? [];

        foreach ($articles as $article) {
            $title = strtolower($article['title']);
            $description = strtolower($article['description'] ?? '');
            $content = $title . ' ' . $description;
            
            $matchesCategory = false;
            $matchesKeyword = false;
            
            // Check category matches
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    if (str_contains($content, strtolower($category))) {
                        $matchesCategory = true;
                        break;
                    }
                }
            } else {
                $matchesCategory = true; // No category filter
            }
            
            // Check keyword matches
            if (!empty($keywords)) {
                foreach ($keywords as $keyword) {
                    if (str_contains($content, strtolower($keyword))) {
                        $matchesKeyword = true;
                        break;
                    }
                }
            } else {
                $matchesKeyword = true; // No keyword filter
            }
            
            if ($matchesCategory && $matchesKeyword) {
                $filteredArticles[] = $article;
            }
        }
        
        return array_slice($filteredArticles, 0, 10); // Max 10 articles per user
    }
}
