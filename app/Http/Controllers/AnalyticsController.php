<?php

namespace App\Http\Controllers;

use App\Models\EmailInteraction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    /**
     * Track email opens using a 1x1 pixel image
     */
    public function trackOpen($campaignId, $userId)
    {
        // Record the open event (only once per user per campaign)
        EmailInteraction::firstOrCreate([
            'campaign_id' => $campaignId,
            'user_id' => $userId,
            'type' => 'open'
        ]);

        // Return a 1x1 transparent pixel
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        
        return response($pixel)
            ->header('Content-Type', 'image/gif')
            ->header('Content-Length', strlen($pixel))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Track link clicks and redirect to the actual URL
     */
    public function trackClick($campaignId, $userId, $encodedUrl)
    {
        $actualUrl = base64_decode($encodedUrl);
        
        // Record the click event
        EmailInteraction::create([
            'campaign_id' => $campaignId,
            'user_id' => $userId,
            'type' => 'click',
            'url' => $actualUrl
        ]);

        // Redirect to the actual article URL
        return redirect($actualUrl);
    }
}
