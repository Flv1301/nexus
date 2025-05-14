<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 23/03/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Services;

use App\Models\UploadFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UploadFileService
{
    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public static function store(UploadedFile $file): mixed
    {
        try {
            return UploadFile::create([
                'original_name' => $file->getClientOriginalName(),
                'filename' => $file->getFilename(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getExtension(),
                'hash_name' => md5($file->getClientOriginalName()),
                'user_id' => Auth::id()
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public static function verify(UploadedFile $file): mixed
    {
        return UploadFile::where('original_name', $file->getClientOriginalName())
            ->where('hash_name', md5($file->getClientOriginalName()))->count();
    }

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public static function getForFile(UploadedFile $file): mixed
    {
        return UploadFile::where('user_id', Auth::id())->where('original_name', $file->getClientOriginalName())
            ->where('hash_name', md5($file->getClientOriginalName()));
    }
}
