<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'categories' => 'array',
            'keywords' => 'array',
            'frequency' => 'in:daily,weekly'
        ]);

        $user = $request->user();
        
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'categories' => $request->categories ?? [],
                'keywords' => $request->keywords ?? [],
                'frequency' => $request->frequency ?? 'daily'
            ]
        );

        return back()->with('status', 'Newsletter preferences updated successfully.');
    }
}
