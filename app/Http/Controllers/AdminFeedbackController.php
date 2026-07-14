<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\KritikSaran;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'kritik_id' => 'required|integer|exists:tb_kritik_saran,id',
        ]);

        KritikSaran::findOrFail($validated['kritik_id'])->delete();

        return $this->redirectWithSuccess('Kritik dan saran berhasil dihapus.');
    }

    public function updateTelegram(Request $request)
    {
        $validated = $request->validate([
            'telegram_bot_token' => 'nullable|string|max:255',
            'telegram_chat_id' => 'nullable|string|max:100',
        ]);

        $this->saveSetting('telegram_bot_token', $validated['telegram_bot_token'] ?? '');
        $this->saveSetting('telegram_chat_id', $validated['telegram_chat_id'] ?? '');

        return $this->redirectWithSuccess('Pengaturan Telegram berhasil disimpan.');
    }

    private function saveSetting(string $key, string $value): void
    {
        Content::updateOrCreate(
            ['content_key' => $key],
            [
                'section' => 'integrations',
                'content_value' => $value,
                'content_type' => 'text',
            ]
        );
    }

    private function redirectWithSuccess(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'kritik-saran'])->with('success', $message);
    }
}
