<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class FileHelper
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public static function filenameOriginalDate(UploadedFile $file): string
    {
        return Str::slug(
                date('YmdHi') . '-' . Str::before(
                    $file->getClientOriginalName(),
                    '.'
                )
            ) . '.' . $file->extension();
    }
}
