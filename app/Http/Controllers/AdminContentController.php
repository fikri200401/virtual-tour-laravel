<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminContentController extends Controller
{
    private const TELEGRAM_KEYS = ['telegram_bot_token', 'telegram_chat_id'];

    public function updateSection(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:50',
            'contents' => 'required|array',
            'contents.*' => 'nullable|string|max:20000',
        ]);

        $ids = array_map('intval', array_keys($validated['contents']));
        $contents = Content::where('section', $validated['section'])
            ->whereNotIn('content_key', self::TELEGRAM_KEYS)
            ->whereIn('id', $ids)
            ->get();

        DB::transaction(function () use ($contents, $validated) {
            foreach ($contents as $content) {
                $content->update([
                    'content_value' => (string) ($validated['contents'][$content->id] ?? ''),
                ]);
            }
        });

        return redirect()->route('admin.dashboard', ['tab' => 'content'])
            ->with('success', 'Semua konten pada bagian ini berhasil disimpan.');
    }
}
