<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MothershipController extends Controller
{
    public function sync(Request $request)
    {
        $token = $request->header('X-Mothership-Token');
        
        if (!$token) {
            return response()->json(['message' => 'Missing Token'], 401);
        }

        $storedToken = Setting::where('key', 'mothership_token')->first()?->value;

        if (!$storedToken || $token !== $storedToken) {
            return response()->json(['message' => 'Invalid Token'], 403);
        }

        $action = $request->input('action'); // 'suspend' or 'activate'

        if ($action === 'suspend') {
            Setting::updateOrCreate(['key' => 'is_suspended'], ['value' => '1']);
            return response()->json(['message' => 'Website Suspended']);
        }

        if ($action === 'activate') {
            Setting::updateOrCreate(['key' => 'is_suspended'], ['value' => '0']);
            return response()->json(['message' => 'Website Activated']);
        }

        return response()->json(['message' => 'Invalid Action'], 400);
    }
}
