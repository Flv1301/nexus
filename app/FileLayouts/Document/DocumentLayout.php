<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Document;

use App\FileLayouts\CaseDocumentAbstract;
use App\Helpers\ImageHelper;
use App\Models\Files\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentLayout extends CaseDocumentAbstract
{
    /**
     * @return false|mixed
     */
    public function store(): mixed
    {
        try {
            ImageHelper::optimizerImage($this->path);
            return Document::create([
                'name' => Str::beforeLast($this->file->getClientOriginalName(), '.'),
                'extension' => $this->file->extension(),
                'path' => $this->path,
                'view' => 'documents'
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível anexar o documento', 'error');
            Storage::delete($this->path);
            return false;
        }
    }

    public function extract()
    {

    }

    public function parameters()
    {

    }

    public function checkAndDelete(Collection $parameter, string $alias)
    {

    }
}
