<?php
use App\Http\Controllers\Api\SubscribeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NewsController;


Route::get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});
// Route::get('/news', [NewsController::class, 'index']);

//Route::post('/subscribe', [SubscribeController::class, 'store']);
