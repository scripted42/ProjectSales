<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Client;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/verify', function (Request $request) {
    $domain = $request->query('domain');
    $client = Client::where('domain', $domain)->first();

    if (!$client) {
        return response()->json(['status' => 'error', 'message' => 'Domain not registered'], 404);
    }

    return response()->json([
        'status' => 'success',
        'plan' => $client->plan,
        'expired_at' => $client->expired_at,
        'client_status' => $client->status,
    ]);
});

