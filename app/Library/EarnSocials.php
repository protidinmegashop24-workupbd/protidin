<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;

/**
 * Verifies a user actually posted on earnsocials.com (a WordPress +
 * BuddyPress site the site owner runs) before paying the Facebook-share
 * bonus's replacement. Unlike a Facebook share -- which cannot be verified
 * server-side at all -- BuddyPress exposes a real REST API we can query.
 *
 * Flow: the user is shown a unique per-day code, asked to post it as a
 * status update on earnsocials.com, then we search the BuddyPress Activity
 * REST API for that exact code. Auth is a WordPress Application Password
 * (Users -> Profile -> Application Passwords in wp-admin), sent as HTTP
 * Basic Auth -- this is WordPress core's own built-in auth method, nothing
 * custom on their end was needed.
 */
class EarnSocials
{
    protected static function baseUrl()
    {
        return rtrim((string) env('EARNSOCIALS_API_URL'), '/');
    }

    protected static function configured()
    {
        return !empty(self::baseUrl())
            && !empty(env('EARNSOCIALS_API_USERNAME'))
            && !empty(env('EARNSOCIALS_API_APP_PASSWORD'));
    }

    /**
     * True if a public activity post containing the exact code can be
     * found via the BuddyPress REST API.
     *
     * @param string $code
     * @return bool
     */
    public static function codeWasPosted($code)
    {
        if (empty($code) || !self::configured()) {
            return false;
        }

        $response = Http::withBasicAuth(
            env('EARNSOCIALS_API_USERNAME'),
            env('EARNSOCIALS_API_APP_PASSWORD')
        )->get(self::baseUrl() . '/wp-json/buddypress/v1/activity', [
            'search' => $code,
            'per_page' => 10,
        ]);

        if (!$response->successful()) {
            return false;
        }

        $items = $response->json();
        if (!is_array($items)) {
            return false;
        }

        foreach ($items as $item) {
            $content = $item['content'] ?? '';
            if (is_array($content)) {
                $content = $content['rendered'] ?? ($content['raw'] ?? '');
            }
            if (is_string($content) && str_contains($content, $code)) {
                return true;
            }
        }

        return false;
    }
}
