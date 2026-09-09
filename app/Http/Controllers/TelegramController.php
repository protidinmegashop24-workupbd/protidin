<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramController extends Controller
{
    private function webhookSecret()
    {
        return env('TELEGRAM_WEBHOOK_SECRET');
    }

    private function botToken()
    {
        return env('TELEGRAM_BOT_TOKEN');
    }

    public function webhook(Request $request, $secret)
    {
        $expected = $this->webhookSecret();
        if (!$expected || !hash_equals($expected, (string) $secret)) {
            abort(403);
        }

        $message = $request->input('message');
        $chatId = $message['chat']['id'] ?? null;
        $text = trim((string) ($message['text'] ?? ''));

        if (!$chatId) {
            return response()->json(['ok' => true]);
        }

        if (Str::startsWith($text, '/start')) {
            $parts = explode(' ', $text, 2);
            $refCode = isset($parts[1]) ? trim($parts[1]) : null;

            $registerUrl = $refCode
                ? url('/register/' . $refCode)
                : url('/register');

            $this->sendMessage(
                $chatId,
                "🎉 Welcome to Protidin Mega Earn!\n\n".
                "আপনি কি অনলাইনে কাজ করে আয় করতে চান? 🚀\n\n".
                "Protidin Mega Earn-এর সাথে যুক্ত হয়ে বিভিন্ন earning opportunity, online work এবং useful resources সম্পর্কে জানুন।\n\n".
                "👇 এখনই শুরু করুন!",
                $registerUrl
            );
        } else {
            $this->sendMessage(
                $chatId,
                'অ্যাকাউন্ট খুলতে /start লিখে পাঠান, অথবা নিচের বাটনে ক্লিক করুন।',
                url('/register')
            );
        }

        return response()->json(['ok' => true]);
    }

    private function sendMessage($chatId, $text, $url)
    {
        $token = $this->botToken();
        if (!$token) {
            return;
        }

        Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => '✅ Register Now', 'url' => $url],
                ]],
            ]),
        ]);
    }
}
