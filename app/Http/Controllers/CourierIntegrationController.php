<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Settings;
use App\Services\SteadfastCourierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Throwable;

class CourierIntegrationController extends Controller
{
    private const FRAUD_CHECK_URL = 'https://api.bdcourier.com/courier-check';
    private const STEADFAST_URL = 'https://portal.packzy.com/api/v1';

    public function index()
    {
        abort_unless(auth()->user()?->user_type === 'admin', 403);
        $settings = Settings::firstOrFail();

        return view('courier-integrations.index', [
            'settings' => $settings,
            'hasApiKey' => filled($settings->fraud_checker_api_key),
            'defaultUrl' => self::FRAUD_CHECK_URL,
            'steadfastDefaultUrl' => self::STEADFAST_URL,
            'hasSteadfastCredentials' => filled($settings->steadfast_api_key) && filled($settings->steadfast_secret_key),
            'hasSteadfastWebhookToken' => filled($settings->steadfast_bearer_token),
        ]);
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()?->user_type === 'admin', 403);
        $settings = Settings::firstOrFail();
        $validated = $request->validate([
            'fraud_checker_base_url' => ['required', 'url', 'in:'.self::FRAUD_CHECK_URL],
            'fraud_checker_api_key' => [filled($settings->fraud_checker_api_key) ? 'nullable' : 'required', 'string', 'max:2000'],
        ]);

        $settings->fraud_checker_base_url = $validated['fraud_checker_base_url'];
        if (filled($validated['fraud_checker_api_key'] ?? null)) {
            $settings->fraud_checker_api_key = Crypt::encryptString($validated['fraud_checker_api_key']);
        }
        $settings->save();

        return redirect()->back()->with('success', 'Fraud Checker configuration saved successfully.');
    }

    public function check(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $phone = preg_replace('/\D+/', '', (string) $order->order_phone_number);
        if (strlen($phone) === 13 && str_starts_with($phone, '880')) {
            $phone = '0'.substr($phone, 3);
        }
        if (! $phone) {
            return response()->json(['message' => 'This order does not have a valid phone number.'], 422);
        }

        $settings = Settings::first();
        if (! $settings || blank($settings->fraud_checker_api_key)) {
            return response()->json(['message' => 'Fraud Checker API key is not configured.'], 422);
        }

        try {
            $apiKey = Crypt::decryptString($settings->fraud_checker_api_key);
            $url = $settings->fraud_checker_base_url ?: self::FRAUD_CHECK_URL;
            if ($url !== self::FRAUD_CHECK_URL) {
                return response()->json(['message' => 'The configured Fraud Checker URL is not allowed.'], 422);
            }

            $response = Http::acceptJson()
                ->asJson()
                ->withToken($apiKey)
                ->timeout(30)
                ->post($url, ['phone' => $phone]);

            if (! $response->successful()) {
                return response()->json([
                    'message' => $response->json('message') ?: 'Fraud Checker service returned an error.',
                ], $response->status() >= 400 && $response->status() < 500 ? $response->status() : 502);
            }

            return response()->json($response->json());
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'Unable to connect to the Fraud Checker service.'], 502);
        }
    }

    public function updateSteadfast(Request $request)
    {
        abort_unless(auth()->user()?->user_type === 'admin', 403);
        $settings = Settings::firstOrFail();
        $validated = $request->validate([
            'steadfast_base_url' => ['required', 'url', function ($attribute, $value, $fail) {
                $path = rtrim((string) parse_url($value, PHP_URL_PATH), '/');
                if (parse_url($value, PHP_URL_SCHEME) !== 'https' || parse_url($value, PHP_URL_HOST) !== 'portal.packzy.com' || $path !== '/api/v1') $fail('Use the official SteadFast URL: '.self::STEADFAST_URL);
            }],
            'steadfast_api_key' => [filled($settings->steadfast_api_key) ? 'nullable' : 'required', 'nullable', 'string', 'max:2000'],
            'steadfast_secret_key' => [filled($settings->steadfast_secret_key) ? 'nullable' : 'required', 'nullable', 'string', 'max:2000'],
            'steadfast_bearer_token' => [filled($settings->steadfast_bearer_token) ? 'nullable' : 'required', 'nullable', 'string', 'max:2000'],
            'steadfast_active' => ['nullable', 'boolean'],
        ]);

        $settings->steadfast_base_url = rtrim($validated['steadfast_base_url'], '/');
        $settings->steadfast_active = $request->boolean('steadfast_active');
        foreach (['steadfast_api_key', 'steadfast_secret_key', 'steadfast_bearer_token'] as $field) {
            if (filled($validated[$field] ?? null)) $settings->{$field} = Crypt::encryptString($validated[$field]);
        }
        $settings->save();
        return redirect()->back()->with('success', 'SteadFast credentials updated successfully.');
    }

    public function deleteSteadfast()
    {
        abort_unless(auth()->user()?->user_type === 'admin', 403);
        $settings = Settings::firstOrFail();
        $settings->steadfast_api_key = null;
        $settings->steadfast_secret_key = null;
        $settings->steadfast_bearer_token = null;
        $settings->steadfast_active = false;
        $settings->save();
        return redirect()->back()->with('success', 'SteadFast credentials deleted successfully.');
    }

    public function steadfastBalance()
    {
        try {
            return response()->json($this->steadfastService()->getCurrentBalance());
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => $this->steadfastError($exception)], 502);
        }
    }

    public function placeSteadfastOrder($id)
    {
        $order = Order::with('billingAddress')->findOrFail($id);
        if ($order->steadfast_consignment_id) return response()->json(['message' => 'This order has already been submitted to SteadFast.'], 422);
        try {
            $response = $this->steadfastService()->placeOrder($this->steadfastOrderPayload($order));
            $this->updateOrderFromSteadfast($order, $response);
            if (! $this->steadfastSuccess($response)) return response()->json(['message' => data_get($response, 'message', 'SteadFast rejected the order.'), 'response' => $response], 422);
            return response()->json(['message' => 'Order sent to SteadFast successfully.', 'data' => $response, 'status' => $order->fresh()->steadfast_status]);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => $this->steadfastError($exception)], 502);
        }
    }

    public function bulkSend(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'in:steadfast'],
            'order_ids' => ['required', 'array', 'min:1', 'max:100'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
        ]);

        $orders = Order::with('billingAddress')
            ->whereIn('id', $validated['order_ids'])
            ->whereNull('steadfast_consignment_id')
            ->get();
        if ($orders->isEmpty()) return response()->json(['message' => 'Selected orders are already submitted or unavailable.'], 422);

        try {
            $response = $this->steadfastService()->bulkCreateOrders($orders->map(fn (Order $order) => $this->steadfastOrderPayload($order))->all());
            $entries = data_get($response, 'data', data_get($response, 'consignments', []));
            if (array_is_list($response)) $entries = $response;
            if (! is_array($entries)) $entries = [];

            $updated = 0;
            $submittedOrderIds = [];
            foreach ($entries as $entry) {
                if (! is_array($entry)) continue;
                $invoice = $entry['invoice'] ?? data_get($entry, 'consignment.invoice');
                $order = $orders->firstWhere('custom_order_id', $invoice);
                if (! $order) continue;
                $this->updateOrderFromSteadfast($order, isset($entry['consignment']) ? $entry : ['status' => $response['status'] ?? 200, 'consignment' => $entry]);
                $updated++;
                $submittedOrderIds[] = $order->id;
            }

            return response()->json([
                'message' => $updated > 0 ? $updated.' order(s) sent to SteadFast successfully.' : 'Bulk request completed. Please check individual courier statuses.',
                'submitted' => $updated,
                'requested' => $orders->count(),
                'submitted_order_ids' => $submittedOrderIds,
                'data' => $response,
            ]);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => $this->steadfastError($exception)], 502);
        }
    }

    public function checkSteadfastStatus($id)
    {
        $order = Order::findOrFail($id);
        try {
            $service = $this->steadfastService();
            $response = $order->steadfast_tracking_code
                ? $service->checkStatusByTrackingCode($order->steadfast_tracking_code)
                : ($order->steadfast_consignment_id
                    ? $service->checkStatusByConsignmentId((int) $order->steadfast_consignment_id)
                    : $service->checkStatusByInvoiceId($order->custom_order_id));
            $status = data_get($response, 'delivery_status', data_get($response, 'status'));
            $order->update(['steadfast_status' => is_scalar($status) ? (string) $status : $order->steadfast_status, 'steadfast_response' => $response]);
            return response()->json(['message' => 'SteadFast status checked successfully.', 'status' => $order->fresh()->steadfast_status ?: 'Unknown', 'data' => $response]);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => $this->steadfastError($exception)], 502);
        }
    }

    private function steadfastService(): SteadfastCourierService
    {
        $settings = Settings::firstOrFail();
        if (! $settings->steadfast_active || ! $settings->steadfast_api_key || ! $settings->steadfast_secret_key) throw new \RuntimeException('SteadFast is inactive or credentials are not configured.');
        return SteadfastCourierService::withConfig(Crypt::decryptString($settings->steadfast_api_key), Crypt::decryptString($settings->steadfast_secret_key), $settings->steadfast_base_url ?: self::STEADFAST_URL);
    }

    private function updateOrderFromSteadfast(Order $order, array $response): void
    {
        $payload = is_array($response['consignment'] ?? null) ? $response['consignment'] : $response;
        $order->update([
            'steadfast_consignment_id' => $payload['consignment_id'] ?? $order->steadfast_consignment_id,
            'steadfast_tracking_code' => $payload['tracking_code'] ?? $order->steadfast_tracking_code,
            'steadfast_status' => $payload['status'] ?? ($this->steadfastSuccess($response) ? 'success' : data_get($response, 'status')),
            'steadfast_response' => $response,
        ]);
    }

    private function steadfastOrderPayload(Order $order): array
    {
        $billing = $order->billingAddress;
        return [
            'invoice' => $order->custom_order_id,
            'recipient_name' => trim(($billing?->first_name ?? '').' '.($billing?->last_name ?? '')) ?: 'Customer',
            'recipient_phone' => $order->order_phone_number,
            'recipient_address' => collect([$billing?->shipping_address, $billing?->shipping_address_2, $billing?->city, $billing?->state, $billing?->zip])->filter()->implode(', '),
            'cod_amount' => max(0, (float) $order->final_price - (float) $order->pay_amount),
            'note' => $order->order_note,
        ];
    }

    private function steadfastSuccess(array $response): bool
    {
        return data_get($response, 'status') === 'success' || (int) data_get($response, 'status') === 200 || isset($response['consignment']);
    }

    private function steadfastError(Throwable $exception): string
    {
        return str_starts_with($exception->getMessage(), 'SteadFast is inactive')
            ? $exception->getMessage()
            : 'Unable to communicate with SteadFast. Please verify credentials and server network.';
    }
}
