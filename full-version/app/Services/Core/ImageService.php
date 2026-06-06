<?php

namespace App\Services\Core;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * Handles image compression and optimization before storage.
 * Uses GD library (built-in PHP) for resizing and quality reduction.
 * If GD is not available, falls back to storing the original file unchanged.
 */
class ImageService
{
    /**
     * Default settings for image processing.
     */
    private const DEFAULTS = [
        'max_width'  => 1200,
        'max_height' => 1200,
        'quality'    => 80,    // JPEG quality (0-100)
    ];

    /**
     * Compress and resize an uploaded image in-place.
     * Returns the same UploadedFile path after compression.
     *
     * @param  UploadedFile  $file
     * @param  array{max_width?: int, max_height?: int, quality?: int}  $options
     * @return UploadedFile  The same file reference (modified in temp path)
     */
    public function compress(UploadedFile $file, array $options = []): UploadedFile
    {
        // Only process images that GD can handle
        if (!$this->canProcess($file)) {
            return $file;
        }

        $opts      = array_merge(self::DEFAULTS, $options);
        $path      = $file->getRealPath();
        $mime      = $file->getMimeType();

        try {
            [$srcImage, $origW, $origH] = $this->createSource($path, $mime);

            if (!$srcImage) {
                return $file;
            }

            [$newW, $newH] = $this->calculateDimensions($origW, $origH, $opts['max_width'], $opts['max_height']);

            if ($newW === $origW && $newH === $origH && $mime === 'image/jpeg') {
                // Only re-encode JPEG for quality reduction
                imagejpeg($srcImage, $path, $opts['quality']);
                imagedestroy($srcImage);
                return $file;
            }

            $dstImage = imagecreatetruecolor($newW, $newH);
            $this->preserveTransparency($dstImage, $srcImage, $mime);
            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

            $this->saveImage($dstImage, $path, $mime, $opts['quality']);

            imagedestroy($srcImage);
            imagedestroy($dstImage);

        } catch (\Throwable $e) {
            Log::warning('ImageService: compression failed, using original.', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
        }

        return $file;
    }

    private function canProcess(UploadedFile $file): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        return in_array($file->getMimeType(), [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ], true);
    }

    private function createSource(string $path, string $mime): array
    {
        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png'  => imagecreatefrompng($path),
            'image/gif'  => imagecreatefromgif($path),
            'image/webp' => imagecreatefromwebp($path),
            default      => false,
        };

        if (!$image) {
            return [false, 0, 0];
        }

        return [$image, imagesx($image), imagesy($image)];
    }

    private function calculateDimensions(int $w, int $h, int $maxW, int $maxH): array
    {
        if ($w <= $maxW && $h <= $maxH) {
            return [$w, $h];
        }

        $ratio  = min($maxW / $w, $maxH / $h);
        return [(int) round($w * $ratio), (int) round($h * $ratio)];
    }

    private function preserveTransparency(\GdImage $dst, \GdImage $src, string $mime): void
    {
        if ($mime === 'image/png' || $mime === 'image/gif') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, imagesx($dst), imagesy($dst), $transparent);
        }
    }

    private function saveImage(\GdImage $image, string $path, string $mime, int $quality): void
    {
        match ($mime) {
            'image/jpeg' => imagejpeg($image, $path, $quality),
            'image/png'  => imagepng($image, $path, (int) round((100 - $quality) / 11)),
            'image/gif'  => imagegif($image, $path),
            'image/webp' => imagewebp($image, $path, $quality),
            default      => null,
        };
    }
}
