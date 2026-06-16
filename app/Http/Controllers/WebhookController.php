<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $expectedKey = (string) config('app.webhook.api_key', '');
        $authorization = (string) $request->header('Authorization', '');
        $bearer = '';

        if (stripos($authorization, 'Bearer ') === 0) {
            $bearer = trim(substr($authorization, 7));
        }

        if ($expectedKey !== '' && $bearer !== $expectedKey) {
            Log::warning('Inbound webhook rejected: invalid bearer token', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $payload = $request->all();
        $event = is_string($payload['event'] ?? null) ? $payload['event'] : null;
        $value = $payload['value'] ?? null;

        Log::info('Inbound webhook received', [
            'event' => $event,
            'value' => $value,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
        ]);
    }
}

