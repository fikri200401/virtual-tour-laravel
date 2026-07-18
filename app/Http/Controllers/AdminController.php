<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Content;
use App\Models\Facility;
use App\Models\KritikSaran;
use App\Models\VisitorStat;
use App\Services\WebsiteContentService;
use App\Services\VirtualTourUploadService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    private const TELEGRAM_KEYS = ['telegram_bot_token', 'telegram_chat_id'];

    public function dashboard(VirtualTourUploadService $tourUploader)
    {
        $contentDefinitions = WebsiteContentService::definitions();
        $contentGroups = $this->contentGroups($contentDefinitions);
        $facilities = Facility::orderByDesc('created_at')->get();
        $users = Admin::orderByDesc('id')->get();
        $kritikSaran = KritikSaran::orderByDesc('created_at')->get();
        $telegramSettings = $this->telegramSettings();
        $stats = $this->visitorStats();
        $recentVisitors = VisitorStat::where('is_admin', false)
            ->latest('visit_time')
            ->limit(10)
            ->get();
        $images = $this->imagePaths();
        $deployedTour = $tourUploader->installedTour();

        return view('admin.dashboard', compact(
            'contentDefinitions',
            'contentGroups',
            'facilities',
            'users',
            'kritikSaran',
            'telegramSettings',
            'stats',
            'recentVisitors',
            'images',
            'deployedTour'
        ));
    }

    private function contentGroups(array $definitions): Collection
    {
        $positionByKey = array_flip(array_keys($definitions));
        $contentsBySection = Content::whereNotIn('content_key', self::TELEGRAM_KEYS)
            ->get()
            ->toBase()
            ->groupBy('section');
        $sections = WebsiteContentService::sections();

        $definedGroups = collect($sections)
            ->map(function (array $metadata, string $section) use ($contentsBySection, $positionByKey) {
                $items = $contentsBySection
                    ->get($section, collect())
                    ->sortBy(fn (Content $content) => $positionByKey[$content->content_key] ?? PHP_INT_MAX)
                    ->values();

                return [
                    ...$metadata,
                    'items' => $items,
                ];
            })
            ->filter(fn (array $group) => $group['items']->isNotEmpty());

        $additionalGroups = $contentsBySection
            ->except(array_keys($sections))
            ->map(fn (Collection $items, string $section) => [
                'label' => ucwords(str_replace('_', ' ', $section)),
                'description' => 'Konten tambahan pada bagian '.$section.'.',
                'items' => $items->values(),
            ]);

        return $definedGroups->merge($additionalGroups);
    }

    private function telegramSettings(): array
    {
        $settings = Content::whereIn('content_key', self::TELEGRAM_KEYS)
            ->pluck('content_value', 'content_key');

        return [
            'bot_token' => $settings->get('telegram_bot_token', config('services.telegram.bot_token', '')),
            'chat_id' => $settings->get('telegram_chat_id', config('services.telegram.chat_id', '')),
        ];
    }

    private function visitorStats(): array
    {
        $publicVisits = VisitorStat::where('is_admin', false);

        return [
            'total_visitors' => (clone $publicVisits)->distinct('ip_address')->count('ip_address'),
            'vr_visitors' => (clone $publicVisits)->where('page_visited', 'virtual-tour')->distinct('ip_address')->count('ip_address'),
            'kritik_count' => KritikSaran::count(),
            'today_visitors' => (clone $publicVisits)->where('visit_date', today()->toDateString())->distinct('ip_address')->count('ip_address'),
        ];
    }

    private function imagePaths(): array
    {
        $assetPath = public_path('asset');
        if (! File::isDirectory($assetPath)) {
            return [];
        }

        return collect(File::glob($assetPath.'/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE))
            ->map(fn (string $file) => 'asset/'.basename($file))
            ->values()
            ->all();
    }

}
