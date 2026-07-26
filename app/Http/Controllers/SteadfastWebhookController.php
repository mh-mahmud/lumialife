<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

class SteadfastWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $token = trim(preg_replace('/^Bearer\s+/i', '', (string) $request->header('Authorization')));
        $settings = Settings::first();
        try {
            $savedToken = $settings?->steadfast_bearer_token ? Crypt::decryptString($settings->steadfast_bearer_token) : '';
        } catch (Throwable) {
            $savedToken = '';
        }

        if (! $token || ! $savedToken || ! hash_equals($savedToken, $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'consignment_id' => ['required'],
            'invoice' => ['required', 'string'],
            'status' => ['required', 'string'],
            'tracking_code' => ['nullable', 'string'],
        ]);

        $order = Order::where('custom_order_id', $validated['invoice'])
            ->orWhere('steadfast_consignment_id', $validated['consignment_id'])
            ->first();
        if (! $order) return response()->json(['error' => 'Order not found'], 404);

        $order->update([
            'steadfast_consignment_id' => $validated['consignment_id'],
            'steadfast_tracking_code' => $validated['tracking_code'] ?? $order->steadfast_tracking_code,
            'steadfast_status' => $validated['status'],
            'steadfast_response' => $request->all(),
        ]);

        Log::info('SteadFast webhook received', ['order_id' => $order->id, 'invoice' => $validated['invoice'], 'status' => $validated['status']]);
        return response()->json(['status' => 'success']);
    }
}
