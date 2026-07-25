<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\KritikSaran;
use App\Services\FeedbackCaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Throwable;

class KritikSaranController extends Controller
{
    public function captcha(Request $request, FeedbackCaptchaService $captcha)
    {
        return response($captcha->issue($request->session()), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function store(Request $request, FeedbackCaptchaService $captcha)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'pesan' => 'required|string',
            'captcha_answer' => 'required|string|max:10',
        ], [
            'captcha_answer.required' => 'Kode CAPTCHA wajib diisi.',
        ]);

        if (! $captcha->verify($request->session(), $validated['captcha_answer'])) {
            throw ValidationException::withMessages([
                'captcha_answer' => 'Kode CAPTCHA tidak sesuai. Silakan coba lagi.',
            ]);
        }

        unset($validated['captcha_answer']);
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
