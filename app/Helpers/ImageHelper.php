<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageHelper
{
    /**
     * @param string $path
     * @return bool
     */
    public static function optimizerImage(string $path): bool
    {
        if (Storage::exists($path) && self::isImagePath($path)) {
            ImageOptimizer::optimize(Storage::path($path));
            return true;
        }
        return false;
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    public static function isImageFile(UploadedFile $file): bool
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file->extension(), $allowedExtensions)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function isImagePath(string $path): bool
    {
        $allowedExtensions = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp', 'image/webp'];
        if (in_array(Storage::mimeType($path), $allowedExtensions)) {
            return true;
        }
        return false;
    }
}
