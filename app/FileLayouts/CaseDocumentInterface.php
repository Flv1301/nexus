<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts;

use App\Models\Cases\Cases;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface CaseDocumentInterface
{
    public function __construct(UploadedFile $file, string $filename, Cases $case, string $disk);

    public function store();

    public function extract();

    public function parameters();

    public function checkAndDelete(Collection $parameter, string $alias);
}
