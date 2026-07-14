<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Services\VirtualTourUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AdminFacilityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        Facility::create($validated);

        return $this->redirectWithSuccess('Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|integer|exists:tb_facilities,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        Facility::findOrFail($validated['facility_id'])->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $validated['image'],
        ]);

        return $this->redirectWithSuccess('Fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|integer|exists:tb_facilities,id',
        ]);

        $facility = Facility::findOrFail($validated['facility_id']);
        $tourSlug = $facility->virtual_tour_slug;
        $facility->delete();
        $this->removeTourDirectory($tourSlug);

        return $this->redirectWithSuccess('Fasilitas berhasil dihapus.');
    }

    public function uploadTour(Request $request, VirtualTourUploadService $tourUploader)
    {
        $validated = $request->validate([
            'facility_id' => 'required|integer|exists:tb_facilities,id',
            'tour_file' => 'required|file|max:'.VirtualTourUploadService::MAX_UPLOAD_KB,
        ]);

        $facility = Facility::findOrFail($validated['facility_id']);
        $tourFile = $request->file('tour_file');
        if (! $tourUploader->supports($tourFile)) {
            return $this->redirectWithError('Format virtual tour fasilitas harus ZIP, RAR, PNG, JPG, atau JPEG.');
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
                $this->removeTourDirectory($tourSlug);

                throw $exception;
            }

            $this->removeTourDirectory($oldTourSlug);
        } catch (RuntimeException $exception) {
            return $this->redirectWithError($exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return $this->redirectWithError('Virtual tour fasilitas gagal diproses. Periksa file lalu coba kembali.');
        }

        $message = $uploadType === 'image'
            ? 'Gambar panorama untuk fasilitas "'.$facility->name.'" berhasil dibuat menjadi virtual tour.'
            : 'Arsip virtual tour untuk fasilitas "'.$facility->name.'" berhasil diunggah dan diekstrak.';

        return $this->redirectWithSuccess($message);
    }

    public function deleteTour(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|integer|exists:tb_facilities,id',
        ]);

        $facility = Facility::findOrFail($validated['facility_id']);
        if (! $facility->virtual_tour_slug) {
            return $this->redirectWithError('Fasilitas ini belum memiliki virtual tour.');
        }

        $tourSlug = $facility->virtual_tour_slug;
        $facility->update(['virtual_tour_slug' => null]);
        $this->removeTourDirectory($tourSlug);

        return $this->redirectWithSuccess('Virtual tour fasilitas "'.$facility->name.'" berhasil dihapus.');
    }

    private function removeTourDirectory(?string $tourSlug): void
    {
        if (! $this->isSafeSlug($tourSlug)) {
            return;
        }

        $tourDir = public_path('facility-tours/'.$tourSlug);
        if (File::isDirectory($tourDir)) {
            File::deleteDirectory($tourDir);
        }
    }

    private function isSafeSlug(?string $slug): bool
    {
        return is_string($slug)
            && $slug !== ''
            && $slug === basename($slug)
            && ! str_contains($slug, '..');
    }

    private function redirectWithSuccess(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', $message);
    }

    private function redirectWithError(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('error', $message);
    }
}
