<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SubscribeController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()->withErrors([
            'email' => 'Only registered users can subscribe.',
        ]);
    }

    Subscriber::firstOrCreate(['user_id' => $user->id]);

    return redirect()->back()->with('success', '✅ Subscribed successfully!');
}

}
