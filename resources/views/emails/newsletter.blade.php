<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Egypt News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #0275d8;
        }
        .article {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .article-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .article-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .article-meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .article-content {
            margin-bottom: 15px;
        }
        .read-more {
            display: inline-block;
            background-color: #0275d8;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📰 Daily Egypt News</h1>
        <p>Top stories about Egypt for {{ date('F j, Y') }}</p>
    </div>
    
    <!-- Tracking pixel for open tracking -->
    <img src="{{ url('/track/open/' . $campaign->id . '/' . $user->id) }}" 
         width="1" height="1" style="display:none;" alt="">

    @foreach($articles as $article)
    <div class="article">
        @if(!empty($article['urlToImage']))
        <img src="{{ $article['urlToImage'] }}" alt="{{ $article['title'] }}" class="article-image">
        @endif
        
        <h2 class="article-title">{{ $article['title'] }}</h2>
        
        <div class="article-meta">
            @if(!empty($article['author']))
            <span>By: {{ $article['author'] }}</span> | 
            @endif
            <span>Published: {{ $article['formatted_date'] }}</span>
        </div>
        
        <div class="article-content">
            {{ $article['description'] }}
        </div>
        
        <a href="{{ url('/track/click/' . $campaign->id . '/' . $user->id . '/' . base64_encode($article['url'])) }}" class="read-more">Read Full Article</a>
    </div>
    @endforeach

    <div class="footer">
        <p>You're receiving this email because you subscribed to our Egypt News newsletter.</p>
        <p>
            <a href="{{ url('/unsubscribe/' . $user->id) }}">Unsubscribe</a>
        </p>
    </div>
</body>
</html>