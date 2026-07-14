<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Content;
use App\Models\Facility;
use App\Models\KritikSaran;
use App\Models\VisitorStat;
use App\Services\VirtualTourUploadService;
use App\Services\WebsiteContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $contents = Content::whereNotIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->orderBy('section')
            ->orderBy('content_key')
            ->get();
        $contentDefinitions = WebsiteContentService::definitions();
        $contentGroups = collect(WebsiteContentService::sections())
            ->map(function (array $metadata, string $section) use ($contents, $contentDefinitions) {
                $items = $contents
                    ->where('section', $section)
                    ->sortBy(function (Content $content) use ($contentDefinitions) {
                        $position = array_search($content->content_key, array_keys($contentDefinitions), true);

                        return $position === false ? PHP_INT_MAX : $position;
                    })
                    ->values();

                return [
                    'label' => $metadata['label'],
                    'description' => $metadata['description'],
                    'items' => $items,
                ];
            })
            ->filter(fn (array $group) => $group['items']->isNotEmpty());

        $knownContentIds = $contentGroups->pluck('items')->flatten()->pluck('id');
        $otherContents = $contents->whereNotIn('id', $knownContentIds)->values();
        if ($otherContents->isNotEmpty()) {
            $contentGroups->put('other', [
                'label' => 'Konten Lainnya',
                'description' => 'Field tambahan yang belum dikelompokkan ke halaman tertentu.',
                'items' => $otherContents,
            ]);
        }
        $facilities = Facility::orderByDesc('created_at')->get();
        $users = Admin::orderByDesc('id')->get();
        $kritikSaran = KritikSaran::orderByDesc('created_at')->get();
        $telegramSettingsFromDb = Content::whereIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->pluck('content_value', 'content_key');

        $telegramSettings = [
            'bot_token' => $telegramSettingsFromDb->get('telegram_bot_token', config('services.telegram.bot_token', '')),
            'chat_id' => $telegramSettingsFromDb->get('telegram_chat_id', config('services.telegram.chat_id', '')),
        ];

        // Dashboard statistics
        $stats = [
            'total_visitors' => VisitorStat::where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
            'vr_visitors' => VisitorStat::where('page_visited', 'virtual-tour')->where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
            'kritik_count' => KritikSaran::count(),
            'today_visitors' => VisitorStat::where('visit_date', now()->toDateString())->where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
        ];

        $recentVisitors = VisitorStat::where('is_admin', 0)
            ->orderByDesc('visit_time')
            ->limit(10)
            ->get();

        // Get images from asset folder
        $images = [];
        $assetPath = public_path('asset');
        if (File::isDirectory($assetPath)) {
            $files = File::glob($assetPath.'/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($files as $file) {
                $images[] = 'asset/'.basename($file);
            }
        }

        return view('admin.dashboard', compact(
            'contents', 'facilities', 'users',
            'kritikSaran', 'stats', 'recentVisitors', 'images', 'telegramSettings',
            'contentDefinitions', 'contentGroups'
        ));
    }

    public function updateContent(Request $request)
    {
        $request->validate(['content_id' => 'required|integer', 'content_value' => 'required|string']);
        Content::where('id', $request->content_id)->update(['content_value' => $request->content_value]);

        return redirect()->route('admin.dashboard', ['tab' => 'content'])->with('success', 'Konten berhasil diupdate!');
    }

    public function updateContentSection(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:50',
            'contents' => 'required|array',
            'contents.*' => 'nullable|string|max:20000',
        ]);

        $contentIds = array_map('intval', array_keys($validated['contents']));
        $allowedContents = Content::where('section', $validated['section'])
            ->whereNotIn('content_key', ['telegram_bot_token', 'telegram_chat_id'])
            ->whereIn('id', $contentIds)
            ->get();

        DB::transaction(function () use ($allowedContents, $validated) {
            foreach ($allowedContents as $content) {
                $content->update([
                    'content_value' => (string) ($validated['contents'][$content->id] ?? ''),
                ]);
            }
        });

        return redirect()->route('admin.dashboard', ['tab' => 'content'])
            ->with('success', 'Semua konten pada bagian ini berhasil disimpan!');
    }

    public function addFacility(Request $request)
    {
        $request->validate(['name' => 'required|string', 'description' => 'required|string', 'image' => 'required|string']);
        Facility::create($request->only('name', 'description', 'image'));

        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function updateFacility(Request $request)
    {
        $request->validate(['facility_id' => 'required|integer', 'name' => 'required|string', 'description' => 'required|string', 'image' => 'required|string']);
        Facility::where('id', $request->facility_id)->update($request->only('name', 'description', 'image'));

        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil diupdate!');
    }

    public function deleteFacility(Request $request)
    {
        $request->validate(['facility_id' => 'required|integer|exists:tb_facilities,id']);

        $facility = Facility::findOrFail($request->facility_id);
        $tourSlug = $facility->virtual_tour_slug;
        $facility->delete();
        $this->removeFacilityTourDirectory($tourSlug);

        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil dihapus!');
    }

    public function uploadFacilityTour(Request $request, VirtualTourUploadService $tourUploader)
    {
        $request->validate([
            'facility_id' => 'required|integer|exists:tb_facilities,id',
            'tour_file' => 'required|file|max:40960', // max 40MB (current PHP upload limit)
        ]);

        $facility = Facility::findOrFail($request->facility_id);
        $tourFile = $request->file('tour_file');
        $extension = strtolower($tourFile->getClientOriginalExtension());

        if (! in_array($extension, ['zip', 'rar', 'png', 'jpg', 'jpeg'], true)) {
            return redirect()->route('admin.dashboard', ['tab' => 'facilities'])
                ->with('error', 'Format virtual tour fasilitas harus ZIP, RAR, PNG, JPG, atau JPEG.');
        }

        $nameSlug = Str::slug($facility->name) ?: 'tour';
        $tourSlug = sprintf('facility-%d-%s-%s', $facility->id, $nameSlug, Str::lower(Str::random(8)));
        $tourDir = public_path('facility-tours/'.$tourSlug);
        $oldTourSlug = $facility->virtual_tour_slug;

        try {
            $uploadType = $tourUploader->deploy($tourFile, $tourDir, $facility->name);

            try {
                $facility->update(['virtual_tour_slug' => $tourSlug]);
            } catch (Throwable $exception) {
                $this->removeFacilityTourDirectory($tourSlug);

                throw $exception;
            }

            $this->removeFacilityTourDirectory($oldTourSlug);
        } catch (RuntimeException $exception) {
            return redirect()->route('admin.dashboard', ['tab' => 'facilities'])
                ->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('admin.dashboard', ['tab' => 'facilities'])
                ->with('error', 'Virtual tour fasilitas gagal diproses. Periksa file lalu coba kembali.');
        }

        $message = $uploadType === 'image'
            ? 'Gambar panorama untuk fasilitas "'.$facility->name.'" berhasil dibuat menjadi virtual tour!'
            : 'Arsip virtual tour untuk fasilitas "'.$facility->name.'" berhasil diupload dan diekstrak!';

        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', $message);
    }

    public function deleteFacilityTour(Request $request)
    {
        $request->validate(['facility_id' => 'required|integer|exists:tb_facilities,id']);

        $facility = Facility::findOrFail($request->facility_id);
        $tourSlug = $facility->virtual_tour_slug;

        if (! $tourSlug) {
            return redirect()->route('admin.dashboard', ['tab' => 'facilities'])
                ->with('error', 'Fasilitas ini belum memiliki virtual tour.');
        }

        $facility->update(['virtual_tour_slug' => null]);
        $this->removeFacilityTourDirectory($tourSlug);

        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])
            ->with('success', 'Virtual tour fasilitas "'.$facility->name.'" berhasil dihapus.');
    }

    public function addUser(Request $request)
    {
        $request->validate(['username' => 'required|string|max:50', 'password' => 'required|string|min:4']);
        Admin::create(['username' => $request->username, 'password' => Hash::make($request->password)]);

        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request)
    {
        $request->validate(['user_id' => 'required|integer', 'username' => 'required|string|max:50']);
        $data = ['username' => $request->username];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        Admin::where('id', $request->user_id)->update($data);

        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        if ($request->user_id == $request->session()->get('admin_id')) {
            return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('error', 'Tidak dapat menghapus akun yang sedang digunakan!');
        }
        Admin::where('id', $request->user_id)->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil dihapus!');
    }

    public function deleteKritikSaran(Request $request)
    {
        $request->validate(['kritik_id' => 'required|integer']);
        KritikSaran::where('id', $request->kritik_id)->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'kritik-saran'])->with('success', 'Kritik & Saran berhasil dihapus!');
    }

    public function updateTelegramSettings(Request $request)
    {
        $request->validate([
            'telegram_bot_token' => 'nullable|string|max:255',
            'telegram_chat_id' => 'nullable|string|max:100',
        ]);

        Content::updateOrCreate(
            ['content_key' => 'telegram_bot_token'],
            [
                'section' => 'integrations',
                'content_value' => $request->telegram_bot_token ?? '',
                'content_type' => 'text',
            ]
        );

        Content::updateOrCreate(
            ['content_key' => 'telegram_chat_id'],
            [
                'section' => 'integrations',
                'content_value' => $request->telegram_chat_id ?? '',
                'content_type' => 'text',
            ]
        );

        return redirect()->route('admin.dashboard', ['tab' => 'kritik-saran'])->with('success', 'Pengaturan Telegram berhasil disimpan!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $uniqueName = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('asset'), $uniqueName);

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('success', 'File berhasil diupload sebagai '.$uniqueName);
    }

    public function deleteImage(Request $request)
    {
        $request->validate(['image_path' => 'required|string']);
        $imagePath = $request->image_path;

        if (str_starts_with($imagePath, 'asset/') && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));

            return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('success', 'File berhasil dihapus.');
        }

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('error', 'File tidak ditemukan atau tidak diizinkan.');
    }

    public function uploadTour(Request $request, VirtualTourUploadService $tourUploader)
    {
        $request->validate([
            'tour_file' => 'required|file|max:40960', // max 40MB (current PHP upload limit)
            'tour_name' => 'required|string|max:100',
        ]);

        $tourFile = $request->file('tour_file');
        $extension = strtolower($tourFile->getClientOriginalExtension());
        if (! in_array($extension, ['zip', 'rar', 'png', 'jpg', 'jpeg'], true)) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])
                ->with('error', 'Format file harus ZIP, RAR, PNG, JPG, atau JPEG.');
        }

        $tourSlug = Str::slug($request->tour_name);

        if (empty($tourSlug)) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', 'Nama tour tidak valid.');
        }

        $tourDir = public_path('virtual-tours/'.$tourSlug);

        // Check if tour already exists
        if (File::isDirectory($tourDir)) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', 'Tour dengan nama "'.$request->tour_name.'" sudah ada. Hapus terlebih dahulu jika ingin mengganti.');
        }

        try {
            $uploadType = $tourUploader->deploy($tourFile, $tourDir, $request->tour_name);
        } catch (RuntimeException $exception) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])
                ->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])
                ->with('error', 'Tour gagal diproses. Periksa file lalu coba kembali.');
        }

        $message = $uploadType === 'image'
            ? 'Gambar panorama "'.$request->tour_name.'" berhasil dibuat menjadi virtual tour!'
            : 'Arsip tour "'.$request->tour_name.'" berhasil diupload dan diekstrak!';

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', $message);
    }

    public function deleteTour(Request $request)
    {
        $request->validate(['tour_slug' => 'required|string|max:100']);

        $tourSlug = $request->tour_slug;

        // Security: only allow simple slug names (no path traversal)
        if ($tourSlug !== basename($tourSlug) || str_contains($tourSlug, '..')) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', 'Nama tour tidak valid.');
        }

        $tourDir = public_path('virtual-tours/'.$tourSlug);

        if (! File::isDirectory($tourDir)) {
            return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', 'Tour tidak ditemukan.');
        }

        File::deleteDirectory($tourDir);

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Tour "'.ucfirst($tourSlug).'" berhasil dihapus.');
    }

    private function removeFacilityTourDirectory(?string $tourSlug): void
    {
        if (
            ! is_string($tourSlug)
            || $tourSlug === ''
            || $tourSlug !== basename($tourSlug)
            || str_contains($tourSlug, '..')
        ) {
            return;
        }

        $tourDir = public_path('facility-tours/'.$tourSlug);
        if (File::isDirectory($tourDir)) {
            File::deleteDirectory($tourDir);
        }
    }
}
