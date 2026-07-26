<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SteadfastCourierService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;

    public function __construct(?string $apiKey = null, ?string $secretKey = null, ?string $baseUrl = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? config('steadfast.base_url'), '/');
        $this->apiKey = (string) $apiKey;
        $this->secretKey = (string) $secretKey;
    }

    protected function headers(): array
    {
        return ['Api-Key' => $this->apiKey, 'Secret-Key' => $this->secretKey, 'Accept' => 'application/json'];
    }

    public static function withConfig(string $apiKey, string $secretKey, ?string $baseUrl = null): static
    {
        return new static($apiKey, $secretKey, $baseUrl);
    }

    public function placeOrder(array $data): array
    {
        return Http::withHeaders($this->headers())->asJson()->timeout(30)->post($this->baseUrl.'/create_order', $data)->throw()->json() ?? [];
    }

    public function bulkCreateOrders(array $orders): array
    {
        return Http::withHeaders($this->headers())->asJson()->timeout(60)->post($this->baseUrl.'/create_order/bulk-order', ['data' => $orders])->throw()->json() ?? [];
    }

    public function checkStatusByConsignmentId(int $consignmentId): array
    {
        return Http::withHeaders($this->headers())->timeout(30)->get($this->baseUrl.'/status_by_cid/'.$consignmentId)->throw()->json() ?? [];
    }

    public function checkStatusByInvoiceId(string $invoice): array
    {
        return Http::withHeaders($this->headers())->timeout(30)->get($this->baseUrl.'/status_by_invoice/'.$invoice)->throw()->json() ?? [];
    }

    public function checkStatusByTrackingCode(string $trackingCode): array
    {
        return Http::withHeaders($this->headers())->timeout(30)->get($this->baseUrl.'/status_by_trackingcode/'.$trackingCode)->throw()->json() ?? [];
    }

    public function getCurrentBalance(): array
    {
        return Http::withHeaders($this->headers())->timeout(30)->get($this->baseUrl.'/get_balance')->throw()->json() ?? [];
    }
}
