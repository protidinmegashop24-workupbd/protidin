<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Gives every visitor a long-lived, random device-fingerprint cookie on
 * their first request, independent of User-Agent parsing (which can
 * report the same physical phone differently between browsers/modes).
 * Registration uses this to detect/block the same device opening more
 * than one account.
 */
class EnsureDeviceFingerprint
{
    const COOKIE_NAME = 'device_fp';

    public function handle($request, Closure $next)
    {
        if (!$request->cookie(self::COOKIE_NAME)) {
            $token = Str::random(48);
            Cookie::queue(self::COOKIE_NAME, $token, 60 * 24 * 365 * 5);
            $request->cookies->set(self::COOKIE_NAME, $token);
        }

        return $next($request);
    }
}
