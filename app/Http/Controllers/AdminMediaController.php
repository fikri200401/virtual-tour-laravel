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
        $request->validate([
            'tour_file' => 'required|file|max:'.VirtualTourUploadService::MAX_UPLOAD_KB,
        ]);

        $tourFile = $request->file('tour_file');
        if (! $tourUploader->supports($tourFile)) {
            return $this->redirectTourWithError('Format file harus ZIP, RAR, PNG, JPG, JPEG, atau WEBP.');
        }

        try {
            $uploadType = $tourUploader->deployMainTour($tourFile);
        } catch (RuntimeException $exception) {
            return $this->redirectTourWithError($exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return $this->redirectTourWithError('Tour gagal diproses. Periksa file lalu coba kembali.');
        }

        $message = $uploadType === 'image'
            ? 'Gambar panorama berhasil dipasang sebagai virtual tour utama.'
            : 'Arsip berhasil dipasang sebagai virtual tour utama.';

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', $message);
    }

    public function deleteTour(VirtualTourUploadService $tourUploader)
    {
        if (! $tourUploader->deleteInstalledTours()) {
            return $this->redirectTourWithError('Tour tidak ditemukan.');
        }

        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])
            ->with('success', 'Virtual tour utama berhasil dihapus.');
    }

    private function redirectTourWithError(string $message)
    {
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('error', $message);
    }
}
