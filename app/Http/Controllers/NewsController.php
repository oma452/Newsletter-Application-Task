<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{ 
    public function index()
    {
        $apiKey = config('services.newsapi.key');

        // Use the everything endpoint to get news related to Egypt
        $response = Http::get("https://newsapi.org/v2/everything", [
            'q' => 'Egypt', // Search for Egypt-related news
            'apiKey' => $apiKey,
            'pageSize' => 20, // Number of articles to return
            'language' => 'en', // Filter for English articles
        ]);

        // Check if the response is successful
        if ($response->failed()) {
            Log::error('NewsAPI request failed: ' . $response->body());
            $articles = []; // Return an empty array if the request fails
        } else {
            $articles = $response->json('articles') ?? []; // Get articles from the response
        }

        return Inertia::render('News/Index', [
            'news' => $articles, // Pass the articles to the view
        ]);
    }
}
