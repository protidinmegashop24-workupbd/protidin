<?php

namespace App\Library;

use App\Models\Admin\DepositAccount;
use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Talks to the self-hosted ShopPay panel at shoppay.protidinmegashop.com.
 * This is NOT the open-source UddoktaPay project (different API shape
 * entirely) despite similar branding -- confirmed by reading the panel's
 * own published API docs. Real endpoints:
 *   POST {base}/api/payment/create  -- starts a payment, returns payment_url
 *   POST {base}/api/payment/verify  -- checks a transaction_id's status
 * Headers: API-KEY, SECRET-KEY, BRAND-KEY, Content-Type: application/json.
 * All configured via .env so no credentials live in code.
 */
class ShopPay
{
    protected static function baseUrl()
    {
        return rtrim((string) env('SHOPPAY_API_URL'), '/');
    }

    protected static function headers()
    {
        return [
            'Content-Type' => 'application/json',
            'API-KEY' => (string) env('SHOPPAY_API_KEY'),
            'SECRET-KEY' => (string) env('SHOPPAY_SECRET_KEY'),
            'BRAND-KEY' => (string) env('SHOPPAY_BRAND_KEY'),
        ];
    }

    /**
     * The single DepositAccount row used to tag every deposit that came
     * through ShopPay, so other code (e.g. the pending-deposit
     * re-verify loop) can tell a gateway deposit apart from a manually
     * typed bKash/Nagad transaction ID without guessing.
     */
    public static function depositAccountId()
    {
        return DepositAccount::firstOrCreate(
            ['name' => 'ShopPay (Instant)'],
            ['account_no' => 'shoppay-gateway', 'status' => 1]
        )->id;
    }

    /**
     * Starts a payment and returns the hosted payment page URL to
     * redirect the customer to.
     *
     * @param array $data cus_name, cus_email, amount, metadata, success_url,
     *                     cancel_url, webhook_url
     * @return string
     * @throws Exception when the panel is unreachable or rejects the request
     */
    public static function init_payment(array $data)
    {
        if (empty(self::baseUrl()) || empty(env('SHOPPAY_API_KEY'))) {
            throw new Exception('SHOPPAY_API_URL / SHOPPAY_API_KEY is not configured in .env.');
        }

        $response = Http::withHeaders(self::headers())
            ->post(self::baseUrl() . '/api/payment/create', $data);

        $result = $response->json();

        if (empty($result['status']) || empty($result['payment_url'])) {
            $message = is_array($result) && !empty($result['message'])
                ? $result['message']
                : 'Failed to initialize payment (HTTP ' . $response->status() . ').';
            throw new Exception($message);
        }

        return $result['payment_url'];
    }

    /**
     * Verifies a transaction and returns the panel's full response array
     * (status, cus_name, cus_email, amount, transaction_id, metadata,
     * payment_method). status is 'COMPLETED', 'PENDING', or 'ERROR'.
     *
     * @param string $transactionId
     * @return array
     */
    public static function verify_payment($transactionId)
    {
        if (empty($transactionId) || empty(self::baseUrl())) {
            return [];
        }

        $response = Http::withHeaders(self::headers())
            ->post(self::baseUrl() . '/api/payment/verify', [
                'transaction_id' => $transactionId,
            ]);

        $result = $response->json();

        return is_array($result) ? $result : [];
    }
}
