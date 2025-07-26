<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::with(['interactions'])
            ->orderBy('sent_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'subject' => $campaign->subject,
                    'sent_at' => $campaign->sent_at->format('M d, Y H:i'),
                    'total_recipients' => $campaign->total_recipients,
                    'opens' => $campaign->opens()->count(),
                    'clicks' => $campaign->clicks()->count(),
                    'open_rate' => $campaign->open_rate,
                    'click_rate' => $campaign->click_rate,
                ];
            });

        return Inertia::render('Dashboard', [
            'campaigns' => $campaigns
        ]);
    }
}
