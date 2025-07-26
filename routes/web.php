<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PreferencesController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');


Route::get('/subscribe', fn () => Inertia::render('Subscribe'))->name('subscribe.form');

// Unsubscribe route (public)
Route::get('/unsubscribe/{id}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

// Email tracking routes (public)
Route::get('/track/open/{campaign}/{user}', [AnalyticsController::class, 'trackOpen'])->name('track.open');
Route::get('/track/click/{campaign}/{user}/{url}', [AnalyticsController::class, 'trackClick'])->name('track.click');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Toggle subscription status
    Route::post('/subscription/toggle', [SubscriptionController::class, 'toggle'])->name('subscription.toggle');
    
    // Update newsletter preferences
    Route::post('/preferences', [PreferencesController::class, 'update'])->name('preferences.update');
});

require __DIR__.'/auth.php';
