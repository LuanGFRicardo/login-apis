<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FingerprintLog;

class FingerprintController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fingerprint' => 'required|string'
            ]);

            $user = Auth::user();

            FingerprintLog::create([
                'user_id' => $user ? $user->id : null,
                'fingerprint' => $request->fingerprint,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'language' => $request->input('language'),
                'screen_resolution' => json_encode($request->input('screen_resolution')),
                'timezone' => $request->input('timezone'),
                'platform' => $request->input('platform'),
                'webgl_vendor' => $request->input('webgl_vendor'),
                'webgl_renderer' => $request->input('webgl_renderer'),
                'device_memory' => $request->input('device_memory'),
                'hardware_concurrency' => $request->input('hardware_concurrency'),
                'ad_block' => $request->input('ad_block'),
            ]);

            return response()->json(['message' => 'Fingerprint saved.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failied to save fingerprint.', 'error' => $e->getMessage()], 500);
        }
    }
}
