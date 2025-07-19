<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class FetchEgyptNews extends Command
{
    // This is the command you will run in the terminal
    protected $signature = 'fetch:egypt-news';

    // Description for "php artisan list"
    protected $description = 'Fetch top news about Egypt and store them in the database';

    public function handle()
{
    $this->info('🔄 Fetching Egypt news from NewsAPI...');

    $response = Http::get('https://newsapi.org/v2/everything', [
        'q' => 'Egypt',
        'apiKey' => env('NEWS_API_KEY'),
        'pageSize' => 20,
        'sortBy' => 'publishedAt',
        'language' => 'en',
    ]);
// https://newsapi.org/v2/everything?q=Egypt&apiKey=d7bac98a7f5349de897d64c78c47a598&sortBy=publishedAt&language=en
    if ($response->failed()) {
        $this->error('❌ Failed to fetch news. Check your API key or internet.');
        return;
    }

    $articles = $response->json('articles');

    foreach ($articles as $article) {
        $text = strtolower($article['title'] . ' ' . $article['description']);
        if (!str_contains($text, 'egypt')) {
            continue;
        }

        News::create([
            'title' => $article['title'],
            'description' => $article['description'],
            'author' => $article['author'],
            'url' => $article['url'],
            'urlToImage' => $article['urlToImage'],
            'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
        ]);
    }

    // dd($articles); // Uncomment only if you're testing article structure

    $this->info('✅ News fetched and saved successfully!');
}
};
