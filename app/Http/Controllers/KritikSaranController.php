<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KritikSaran;
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

        // Telegram notification (optional - configure in .env)
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if ($token && $chatId) {
            $text = "📩 KRITIK & SARAN BARU\n\n👤 Nama: {$request->nama}\n📧 Email/HP: {$request->kontak}\n📝 Pesan: {$request->pesan}";
            Http::get("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        }

        return redirect()->route('home')->with('success', 'Pesan berhasil dikirim!');
    }
}
