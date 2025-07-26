<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');


Route::get('/subscribe', fn () => Inertia::render('Subscribe'))->name('subscribe.form');

// Unsubscribe route (public)
Route::get('/unsubscribe/{id}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Toggle subscription status
    Route::post('/subscription/toggle', [SubscriptionController::class, 'toggle'])->name('subscription.toggle');
});

require __DIR__.'/auth.php';
