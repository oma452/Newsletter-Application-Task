<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function unsubscribe($id)
    {
        $user = User::findOrFail($id);
        $user->is_subscribed = false;
        $user->save();
        
        return Inertia::render('Subscription/Unsubscribed', [
            'email' => $user->email
        ]);
    }
    
    public function toggle(Request $request)
    {
        $user = $request->user();
        $user->is_subscribed = !$user->is_subscribed;
        $user->save();
        
        return back()->with('status', $user->is_subscribed ? 
            'You are now subscribed to the newsletter.' : 
            'You have unsubscribed from the newsletter.');
    }
}