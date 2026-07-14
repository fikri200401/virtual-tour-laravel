<?php

namespace App\Http\Controllers;

use App\Services\VirtualTourUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AdminMediaController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $name = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
        $file->move(public_path('asset'), $name);

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])
            ->with('success', 'Berkas berhasil diunggah sebagai '.$name);
    }

    public function deleteImage(Request $request)
    {
        $validated = $request->validate(['image_path' => 'required|string']);
        $imagePath = $validated['image_path'];

        if ($imagePath !== 'asset/'.basename($imagePath) || ! File::isFile(public_path($imagePath))) {
            return redirect()->route('admin.dashboard', ['tab' => 'upload'])
                ->with('error', 'File tidak ditemukan atau tidak diizinkan.');
        }

        File::delete(public_path($imagePath));

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])
            ->with('success', 'Berkas berhasil dihapus.');
    }

    public function uploadTour(Request $request, VirtualTourUploadService $tourUploader)
    {
        $validated = $request->validate([
            'tour_file' => 'required|file|max:'.VirtualTourUploadService::MAX_UPLOAD_KB,
            'tour_name' => 'required|string|max:100',
        ]);

        $tourFile = $request->file('tour_file');
        if (! $tourUploader->supports($tourFile)) {
            return $this->redirectTourWithError('Format file harus ZIP, RAR, PNG, JPG, JPEG, atau WEBP.');
        }

        $tourSlug = Str::slug($validated['tour_name']);
        if ($tourSlug === '') {
            return $this->redirectTourWithError('Nama tour tidak valid.');
        }

        $tourDir = public_path('virtual-tours/'.$tourSlug);
        if (File::isDirectory($tourDir)) {
            return $this->redirectTourWithError('Tour dengan nama "'.$validated['tour_name'].'" sudah ada. Hapus terlebih dahulu jika ingin mengganti.');
        }

        try {
            $uploadType = $tourUploader->deploy($tourFile, $tourDir, $validated['tour_name']);
        } catch (RuntimeException $exception) {
            return $this->redirectTourWithError($exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return $this->redirectTourWithError('Tour gagal diproses. Periksa file lalu coba kembali.');
        }

        $message = $uploadType === 'image'
            ? 'Gambar panorama "'.$validated['tour_name'].'" berhasil dibuat menjadi virtual tour.'
            : 'Arsip tour "'.$validated['tour_name'].'" berhasil diunggah dan diekstrak.';

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', $message);
    }

    public function deleteTour(Request $request)
    {
        $validated = $request->validate(['tour_slug' => 'required|string|max:100']);
        $tourSlug = $validated['tour_slug'];

        if (! $this->isSafeSlug($tourSlug)) {
            return $this->redirectTourWithError('Nama tour tidak valid.');
        }

        $tourDir = public_path('virtual-tours/'.$tourSlug);
        if (! File::isDirectory($tourDir)) {
            return $this->redirectTourWithError('Tour tidak ditemukan.');
        }

        File::deleteDirectory($tourDir);

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])
            ->with('success', 'Tour "'.ucfirst($tourSlug).'" berhasil dihapus.');
    }

    private function isSafeSlug(string $slug): bool
    {
        return $slug !== '' && $slug === basename($slug) && ! str_contains($slug, '..');
    }

    private function redirectTourWithError(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', $message);
    }
}
