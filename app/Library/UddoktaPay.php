<?php

namespace App\Library;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Talks to a self-hosted UddoktaPay panel (e.g. shoppay.protidinmegashop.com)
 * using its standard API: POST /api/checkout-v2 to start a payment, POST
 * /api/verify-payment to check its status. Configured entirely via .env
 * so the same code works against any UddoktaPay panel, not just one host.
 */
class UddoktaPay
{
    protected static function baseUrl()
    {
        return rtrim((string) env('UDDOKTAPAY_API_URL'), '/');
    }

    protected static function apiKey()
    {
        return (string) env('UDDOKTAPAY_API_KEY');
    }

    protected static function headers()
    {
        return [
            'RT-UDDOKTAPAY-API-KEY' => self::apiKey(),
            'Accept' => 'application/json',
        ];
    }

    /**
     * Starts a payment and returns the hosted payment page URL the user
     * should be redirected to.
     *
     * @param array $data full_name, email, amount, metadata, redirect_url,
     *                     return_type, cancel_url, webhook_url
     * @return string
     * @throws Exception when the panel is unreachable or rejects the request
     */
    public static function init_payment(array $data)
    {
        if (empty(self::baseUrl()) || empty(self::apiKey())) {
            throw new Exception('UDDOKTAPAY_API_URL / UDDOKTAPAY_API_KEY is not configured in .env.');
        }

        $response = Http::withHeaders(self::headers())
            ->post(self::baseUrl() . '/api/checkout-v2', $data);

        $result = $response->json();

        if (!$response->successful() || empty($result['payment_url'])) {
            $message = is_array($result) && !empty($result['message'])
                ? $result['message']
                : 'Failed to initialize payment (HTTP ' . $response->status() . ').';
            throw new Exception($message);
        }

        return $result['payment_url'];
    }

    /**
     * Verifies a payment by invoice ID and returns the panel's full
     * response array (status, amount, payment_method, sender_number,
     * transaction_id, invoice_id, ...).
     *
     * @param string $invoiceId
     * @return array
     */
    public static function verify_payment($invoiceId)
    {
        if (empty($invoiceId) || empty(self::baseUrl()) || empty(self::apiKey())) {
            return [];
        }

        $response = Http::withHeaders(self::headers())
            ->post(self::baseUrl() . '/api/verify-payment', [
                'invoice_id' => $invoiceId,
            ]);

        $result = $response->json();

        return is_array($result) ? $result : [];
    }
}
