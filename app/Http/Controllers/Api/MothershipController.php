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
        $signature = $request->header('X-Mothership-Signature');
        
        if (!$token) {
            return response()->json(['message' => 'Missing Token'], 401);
        }

        $storedToken = Setting::where('key', 'mothership_token')->first()?->value;
        $storedSecret = Setting::where('key', 'mothership_secret')->first()?->value;

        if (!$storedToken || $token !== $storedToken) {
            return response()->json(['message' => 'Invalid Token'], 403);
        }

        // Verify HMAC Signature if secret is set
        if ($storedSecret && $signature) {
            $payload = json_encode($request->all());
            $computedSignature = hash_hmac('sha256', $payload, $storedSecret);

            if (!hash_equals($computedSignature, $signature)) {
                return response()->json(['message' => 'Invalid HMAC Signature'], 403);
            }
        } elseif ($storedSecret && !$signature) {
            return response()->json(['message' => 'Missing HMAC Signature'], 403);
        }

        $action = $request->input('action'); // 'suspend', 'activate', 'ping', or 'update_license'

        if ($action === 'ping') {
            return response()->json(['message' => 'Pong', 'status' => 'online']);
        }

        if ($action === 'update_license') {
            if ($request->has('plan')) {
                Setting::updateOrCreate(['key' => 'license_plan'], ['value' => $request->plan]);
            }
            if ($request->has('expired_at')) {
                Setting::updateOrCreate(['key' => 'license_expiry'], ['value' => $request->expired_at]);
            }
            return response()->json(['message' => 'License Data Updated']);
        }

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
