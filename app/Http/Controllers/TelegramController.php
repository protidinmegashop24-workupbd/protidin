<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramController extends Controller
{
    private const CHANNEL_USERNAME = '@earnsocials';
    private const GROUP_USERNAME = '@WorkUpB';
    private const CHANNEL_URL = 'https://t.me/earnsocials';
    private const GROUP_URL = 'https://t.me/WorkUpB';

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

        if ($callback = $request->input('callback_query')) {
            return $this->handleCallback($callback);
        }

        $message = $request->input('message');
        $chatId = $message['chat']['id'] ?? null;
        $chatType = $message['chat']['type'] ?? null;
        $text = trim((string) ($message['text'] ?? ''));

        // The bot is an admin in the promo group/channel, so Telegram also
        // forwards every message posted THERE to this webhook. Only reply to
        // a private 1-on-1 chat with the bot, never back into the group/channel.
        if (!$chatId || $chatType !== 'private') {
            return response()->json(['ok' => true]);
        }

        $refCode = null;
        if (Str::startsWith($text, '/start')) {
            $parts = explode(' ', $text, 2);
            $refCode = isset($parts[1]) ? trim($parts[1]) : null;
        }

        $this->sendJoinGate($chatId, $refCode);

        return response()->json(['ok' => true]);
    }

    private function sendJoinGate($chatId, $refCode)
    {
        $this->sendMessage(
            $chatId,
            "🎉 Welcome to Protidin Mega Earn!\n\n".
            "আপনি কি অনলাইনে কাজ করে আয় করতে চান? 🚀\n\n".
            "শুরু করার আগে নিচের চ্যানেল ও গ্রুপে জয়েন হয়ে নিন, তারপর ✅ বাটনে চাপুন।",
            [
                [['text' => '📢 Join Channel', 'url' => self::CHANNEL_URL]],
                [['text' => '👥 Join Group', 'url' => self::GROUP_URL]],
                [['text' => '✅ জয়েন করেছি, Continue', 'callback_data' => 'verify_join:' . ($refCode ?? '')]],
            ]
        );
    }

    private function handleCallback(array $callback)
    {
        $data = $callback['data'] ?? '';
        $callbackId = $callback['id'] ?? null;
        $chatId = $callback['message']['chat']['id'] ?? null;
        $userId = $callback['from']['id'] ?? null;

        if (!Str::startsWith($data, 'verify_join:') || !$chatId || !$userId) {
            return response()->json(['ok' => true]);
        }

        $refCode = substr($data, strlen('verify_join:'));
        $refCode = $refCode !== '' ? $refCode : null;

        $joinedChannel = $this->isMember(self::CHANNEL_USERNAME, $userId);
        $joinedGroup = $this->isMember(self::GROUP_USERNAME, $userId);

        if ($joinedChannel && $joinedGroup) {
            $this->answerCallback($callbackId, '✅ ধন্যবাদ! এখন রেজিস্ট্রেশন করুন।');

            $registerUrl = $refCode ? url('/register/' . $refCode) : url('/register');

            $this->sendMessage(
                $chatId,
                "🎉 চমৎকার! আপনি চ্যানেল ও গ্রুপে জয়েন করেছেন।\n\nএখন নিচের বাটনে ক্লিক করে ফ্রি অ্যাকাউন্ট খুলে আজই ইনকাম শুরু করুন।",
                [[['text' => '✅ Register Now', 'url' => $registerUrl]]]
            );
        } else {
            $missing = [];
            if (!$joinedChannel) {
                $missing[] = 'চ্যানেল';
            }
            if (!$joinedGroup) {
                $missing[] = 'গ্রুপ';
            }

            $this->answerCallback(
                $callbackId,
                '⚠️ আগে ' . implode(' ও ', $missing) . ' জয়েন করুন, তারপর আবার চেষ্টা করুন।',
                true
            );
        }

        return response()->json(['ok' => true]);
    }

    private function isMember($chatUsername, $userId)
    {
        $token = $this->botToken();
        if (!$token) {
            return false;
        }

        $response = Http::get("https://api.telegram.org/bot{$token}/getChatMember", [
            'chat_id' => $chatUsername,
            'user_id' => $userId,
        ]);

        $status = $response->json('result.status');

        return in_array($status, ['member', 'administrator', 'creator'], true);
    }

    private function answerCallback($callbackId, $text, $showAlert = false)
    {
        $token = $this->botToken();
        if (!$token || !$callbackId) {
            return;
        }

        Http::asForm()->post("https://api.telegram.org/bot{$token}/answerCallbackQuery", [
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => $showAlert,
        ]);
    }

    private function sendMessage($chatId, $text, array $buttonRows)
    {
        $token = $this->botToken();
        if (!$token) {
            return;
        }

        Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttonRows,
            ]),
        ]);
    }
}
