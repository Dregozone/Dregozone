<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/{apiKey}/contactMessages', function (string $apiKey) {
    if ($apiKey !== config('services.api.key')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $contactMessages = \App\Models\ContactMessage::query()
        ->whereNotIn('status', ['ignored', 'actioned', 'replied'])
        ->latest()
        ->get();

    return response()->json(['contactMessages' => $contactMessages]);
});
