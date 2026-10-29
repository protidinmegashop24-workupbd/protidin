<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;

/**
 * Client for the standard "SMM Panel API" protocol -- a single POST
 * endpoint shared by almost every SMM reseller panel (smmmain.com,
 * JustAnotherPanel, NapiPanel included), keyed by an api `key` and an
 * `action` field: action=services lists sellable services, action=add
 * places an order, action=status checks one. No two of these 3 providers'
 * exact API URLs have been confirmed from their own dashboards yet -- that
 * URL lives in the admin-editable SmmProvider::api_url field (set from
 * Admin > SMM Providers) instead of being hardcoded here, the same way
 * Offer Wall's widget_url_template is admin-editable instead of guessed.
 */
class SmmPanel
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->apiKey = $apiKey;
    }

    /**
     * @return array List of ['service' => ..., 'name' => ..., 'category' => ..., 'rate' => ..., 'min' => ..., 'max' => ...]
     */
    public function services(): array
    {
        $response = Http::asForm()->timeout(20)->post($this->apiUrl, [
            'key' => $this->apiKey,
            'action' => 'services',
        ]);

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * @return array Provider's raw response, normally ['order' => <id>] or ['error' => '...']
     */
    public function addOrder($serviceId, string $link, int $quantity): array
    {
        $response = Http::asForm()->timeout(20)->post($this->apiUrl, [
            'key' => $this->apiKey,
            'action' => 'add',
            'service' => $serviceId,
            'link' => $link,
            'quantity' => $quantity,
        ]);

        $data = $response->json();

        return is_array($data) ? $data : ['error' => 'Invalid response from provider.'];
    }

    /**
     * @return array Provider's raw response, normally ['status' => ..., 'charge' => ..., 'start_count' => ..., 'remains' => ...]
     */
    public function status($orderId): array
    {
        $response = Http::asForm()->timeout(20)->post($this->apiUrl, [
            'key' => $this->apiKey,
            'action' => 'status',
            'order' => $orderId,
        ]);

        $data = $response->json();

        return is_array($data) ? $data : ['error' => 'Invalid response from provider.'];
    }
}
