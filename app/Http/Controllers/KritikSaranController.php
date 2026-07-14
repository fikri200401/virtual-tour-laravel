<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\KritikSaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class KritikSaranController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'pesan' => 'required|string',
        ]);

        KritikSaran::create($validated);

        $settings = Content::whereIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->pluck('content_value', 'content_key');
        $token = $settings->get('telegram_bot_token') ?: config('services.telegram.bot_token');
        $chatId = $settings->get('telegram_chat_id') ?: config('services.telegram.chat_id');

        if ($token && $chatId) {
            $message = "KRITIK & SARAN BARU\n\nNama: {$validated['nama']}\nEmail/HP: {$validated['kontak']}\nPesan: {$validated['pesan']}";

            try {
                Http::get("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return redirect()->route('home')->with('success', 'Pesan berhasil dikirim.');
    }
}
