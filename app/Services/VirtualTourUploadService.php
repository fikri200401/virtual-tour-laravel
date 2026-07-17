<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

class VirtualTourUploadService
{
    public const MAX_UPLOAD_KB = 163840;

    public const SUPPORTED_EXTENSIONS = ['zip', 'rar', 'png', 'jpg', 'jpeg', 'webp'];

    private const MAX_EXTRACTED_FILES = 20000;

    private const MAX_EXTRACTED_BYTES = 1073741824;

    private const BLOCKED_EXTENSIONS = [
        'asp', 'aspx', 'cgi', 'htaccess', 'jsp', 'phar', 'php', 'php3',
        'php4', 'php5', 'phtml', 'pl', 'py', 'sh',
    ];

    public function supports(UploadedFile $file): bool
    {
        return in_array(strtolower($file->getClientOriginalExtension()), self::SUPPORTED_EXTENSIONS, true);
    }

    public function deploy(UploadedFile $file, string $tourDir, string $tourName): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            return match ($extension) {
                'png', 'jpg', 'jpeg', 'webp' => $this->deployPanoramaImage($file, $tourDir, $tourName, $extension),
                'zip', 'rar' => $this->deployArchive($file->getPathname(), $tourDir, $extension),
                default => throw new RuntimeException('Format file tidak didukung.'),
            };
        } catch (Throwable $exception) {
            if (File::isDirectory($tourDir)) {
                File::deleteDirectory($tourDir);
            }

            throw $exception;
        }
    }

    private function deployPanoramaImage(
        UploadedFile $file,
        string $tourDir,
        string $tourName,
        string $extension
    ): string {
        $imageInfo = @getimagesize($file->getPathname());
        $expectedType = match ($extension) {
            'png' => IMAGETYPE_PNG,
            'webp' => IMAGETYPE_WEBP,
            default => IMAGETYPE_JPEG,
        };

        if ($imageInfo === false || ($imageInfo[2] ?? null) !== $expectedType) {
            throw new RuntimeException('File gambar tidak valid atau ekstensi tidak sesuai dengan isi file.');
        }

        File::makeDirectory($tourDir, 0755, true, true);

        $imageName = match ($extension) {
            'png' => 'panorama.png',
            'webp' => 'panorama.webp',
            default => 'panorama.jpg',
        };
        $file->move($tourDir, $imageName);
        File::put($tourDir.DIRECTORY_SEPARATOR.'index.html', $this->panoramaViewerHtml($tourName, $imageName));

        return 'image';
    }

    private function deployArchive(string $archivePath, string $tourDir, string $extension): string
    {
        $stagingDir = storage_path('app/virtual-tour-temp/'.Str::uuid());
        File::makeDirectory($stagingDir, 0755, true, true);

        try {
            if ($extension === 'zip') {
                $this->extractZip($archivePath, $stagingDir);
            } else {
                $this->extractRar($archivePath, $stagingDir);
            }

            $this->validateExtractedTree($stagingDir);
            $tourRoot = $this->findTourRoot($stagingDir);

            File::makeDirectory($tourDir, 0755, true, true);
            if (! File::copyDirectory($tourRoot, $tourDir)) {
                throw new RuntimeException('File tour gagal dipindahkan ke folder publik.');
            }

            return 'archive';
        } finally {
            if (File::isDirectory($stagingDir)) {
                File::deleteDirectory($stagingDir);
            }
        }
    }

    private function extractZip(string $archivePath, string $stagingDir): void
    {
        $zip = new ZipArchive;
        if ($zip->open($archivePath) !== true) {
            throw new RuntimeException('File ZIP tidak valid atau rusak.');
        }

        try {
            $totalBytes = 0;

            if ($zip->numFiles > self::MAX_EXTRACTED_FILES) {
                throw new RuntimeException('Arsip berisi terlalu banyak file.');
            }

            for ($index = 0; $index < $zip->numFiles; $index++) {
                $entryName = $zip->getNameIndex($index);
                if ($entryName === false) {
                    throw new RuntimeException('Daftar file di dalam ZIP tidak dapat dibaca.');
                }

                $this->validateArchiveEntryName($entryName);

                $stat = $zip->statIndex($index);
                $totalBytes += (int) ($stat['size'] ?? 0);
                if ($totalBytes > self::MAX_EXTRACTED_BYTES) {
                    throw new RuntimeException('Ukuran hasil ekstraksi arsip melebihi batas 1 GB.');
                }

                $operatingSystem = 0;
                $attributes = 0;
                if ($zip->getExternalAttributesIndex($index, $operatingSystem, $attributes)) {
                    $fileType = ($attributes >> 16) & 0xF000;
                    if ($fileType === 0xA000) {
                        throw new RuntimeException('Arsip tidak boleh mengandung symbolic link.');
                    }
                }
            }

            if (! $zip->extractTo($stagingDir)) {
                throw new RuntimeException('File ZIP gagal diekstrak.');
            }
        } finally {
            $zip->close();
        }
    }

    private function extractRar(string $archivePath, string $stagingDir): void
    {
        if (class_exists('RarArchive')) {
            $this->extractRarWithExtension($archivePath, $stagingDir);

            return;
        }

        $tar = (new ExecutableFinder)->find('tar');
        if ($tar === null) {
            throw new RuntimeException('Server belum memiliki alat ekstraksi RAR (RAR extension atau bsdtar).');
        }

        $listProcess = new Process([$tar, '-tf', $archivePath]);
        $listProcess->setTimeout(120);
        $listProcess->run();

        if (! $listProcess->isSuccessful()) {
            throw new RuntimeException('File RAR tidak valid, rusak, terenkripsi, atau belum didukung oleh server.');
        }

        $entries = preg_split('/\r\n|\r|\n/', trim($listProcess->getOutput())) ?: [];
        if (count($entries) > self::MAX_EXTRACTED_FILES) {
            throw new RuntimeException('Arsip berisi terlalu banyak file.');
        }

        foreach ($entries as $entryName) {
            if ($entryName !== '') {
                $this->validateArchiveEntryName($entryName);
            }
        }

        $extractProcess = new Process([$tar, '-xf', $archivePath, '-C', $stagingDir]);
        $extractProcess->setTimeout(300);
        $extractProcess->run();

        if (! $extractProcess->isSuccessful()) {
            throw new RuntimeException('File RAR gagal diekstrak.');
        }
    }

    private function extractRarWithExtension(string $archivePath, string $stagingDir): void
    {
        $rar = \RarArchive::open($archivePath);
        if ($rar === false) {
            throw new RuntimeException('File RAR tidak valid atau rusak.');
        }

        try {
            $entries = $rar->getEntries();
            if ($entries === false || count($entries) > self::MAX_EXTRACTED_FILES) {
                throw new RuntimeException('Daftar file RAR tidak dapat dibaca atau terlalu banyak.');
            }

            foreach ($entries as $entry) {
                $this->validateArchiveEntryName($entry->getName());
            }

            foreach ($entries as $entry) {
                if (! $entry->extract($stagingDir)) {
                    throw new RuntimeException('File RAR gagal diekstrak.');
                }
            }
        } finally {
            $rar->close();
        }
    }

    private function validateArchiveEntryName(string $entryName): void
    {
        $normalized = str_replace('\\', '/', $entryName);

        if (
            $normalized === ''
            || str_contains($normalized, "\0")
            || str_starts_with($normalized, '/')
            || str_starts_with($normalized, '//')
            || preg_match('/^[A-Za-z]:\//', $normalized)
            || in_array('..', explode('/', $normalized), true)
        ) {
            throw new RuntimeException('Arsip mengandung path file yang tidak aman.');
        }

        $extension = strtolower(pathinfo(rtrim($normalized, '/'), PATHINFO_EXTENSION));
        if ($extension !== '' && in_array($extension, self::BLOCKED_EXTENSIONS, true)) {
            throw new RuntimeException('Arsip mengandung tipe file executable yang tidak diizinkan.');
        }

        $basename = strtolower(basename(rtrim($normalized, '/')));
        if ($basename === 'web.config') {
            throw new RuntimeException('Arsip mengandung file konfigurasi server yang tidak diizinkan.');
        }
    }

    private function validateExtractedTree(string $stagingDir): void
    {
        $root = realpath($stagingDir);
        if ($root === false) {
            throw new RuntimeException('Folder hasil ekstraksi tidak dapat dibaca.');
        }

        $rootPrefix = strtolower(rtrim(str_replace('\\', '/', $root), '/').'/');
        $fileCount = 0;
        $totalBytes = 0;
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($stagingDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isLink()) {
                throw new RuntimeException('Arsip tidak boleh mengandung symbolic link.');
            }

            $realPath = $item->getRealPath();
            $normalizedPath = $realPath === false ? '' : strtolower(str_replace('\\', '/', $realPath));
            if ($normalizedPath === '' || ! str_starts_with($normalizedPath, $rootPrefix)) {
                throw new RuntimeException('Hasil ekstraksi mengandung path yang tidak aman.');
            }

            if ($item->isFile()) {
                $fileCount++;
                $totalBytes += $item->getSize();

                if ($fileCount > self::MAX_EXTRACTED_FILES || $totalBytes > self::MAX_EXTRACTED_BYTES) {
                    throw new RuntimeException('Hasil ekstraksi melebihi batas keamanan.');
                }
            }
        }
    }

    private function findTourRoot(string $stagingDir): string
    {
        $indexFiles = collect(File::allFiles($stagingDir))
            ->filter(fn ($file) => in_array(strtolower($file->getFilename()), ['index.htm', 'index.html'], true))
            ->sortBy(fn ($file) => substr_count(str_replace('\\', '/', $file->getRelativePathname()), '/'));

        $indexFile = $indexFiles->first();
        if ($indexFile === null) {
            throw new RuntimeException('Arsip tidak mengandung index.htm atau index.html hasil export virtual tour.');
        }

        return $indexFile->getPath();
    }

    private function panoramaViewerHtml(string $tourName, string $imageName): string
    {
        $title = htmlspecialchars($tourName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $image = htmlspecialchars($imageName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{$title}</title>
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <style>
        html, body { width: 100%; height: 100%; margin: 0; overflow: hidden; background: #0f172a; }
        a-scene { width: 100%; height: 100%; }
        .hint { position: fixed; left: 50%; bottom: 18px; z-index: 10; transform: translateX(-50%); padding: 9px 14px; border-radius: 999px; color: #fff; background: rgba(15, 23, 42, .72); font: 13px/1.3 Arial, sans-serif; pointer-events: none; }
    </style>
</head>
<body>
    <div class="hint">Klik dan geser untuk melihat panorama 360&deg;</div>
    <a-scene embedded vr-mode-ui="enabled: true" loading-screen="dotsColor: #60a5fa; backgroundColor: #0f172a">
        <a-assets><img id="panorama-image" src="{$image}" alt="{$title}"></a-assets>
        <a-sky src="#panorama-image" rotation="0 -90 0"></a-sky>
        <a-camera look-controls="enabled: true" wasd-controls="enabled: false"></a-camera>
    </a-scene>
</body>
</html>
HTML;
    }
}
