<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KritikSaran;
use App\Models\Content;
use Illuminate\Support\Facades\Http;

class KritikSaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'pesan' => 'required|string',
        ]);

        KritikSaran::create([
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'pesan' => $request->pesan,
        ]);

        $telegramSettings = Content::whereIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->pluck('content_value', 'content_key');

        // Prefer admin settings from database, fallback to .env config.
        $token = $telegramSettings->get('telegram_bot_token') ?: config('services.telegram.bot_token');
        $chatId = $telegramSettings->get('telegram_chat_id') ?: config('services.telegram.chat_id');

        if ($token && $chatId) {
            $text = "📩 KRITIK & SARAN BARU\n\n👤 Nama: {$request->nama}\n📧 Email/HP: {$request->kontak}\n📝 Pesan: {$request->pesan}";
            try {
                Http::get("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $text,
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('home')->with('success', 'Pesan berhasil dikirim!');
    }
}
