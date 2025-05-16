<?php

namespace App\FileLayouts;

use App\Models\Cases\Cases;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\DomCrawler\Crawler;

abstract class CaseDocumentAbstract implements CaseDocumentInterface
{
    /** @var string|false */
    protected string|bool $path;
    /** @var UploadedFile */
    protected UploadedFile $file;
    /** @var string */
    protected string $filename;
    /** @var string */
    protected string $disk;
    /** @var Crawler */
    protected Crawler $crawler;
    /** @var array */
    protected array $extract;
    /** @var Cases  */
    protected Cases $case;
    /** @var Authenticatable|null  */
    protected ?Authenticatable $userAuth;

    /**
     * @param UploadedFile $file
     * @param string $filename
     * @param Cases $case
     * @param string $disk
     */
    public function __construct(UploadedFile $file, string $filename, Cases $case, string $disk = 'local')
    {
        $this->file = $file;
        $this->filename = $filename;
        $this->disk = $disk;
        $this->path = $file->storeAs(config('file.file_path') . $file->extension(), $filename, $disk);
        $this->extract = [];
        $this->case = $case;
        $this->userAuth = Auth::user();
    }

    abstract public function store();

    abstract public function extract();

    abstract public function parameters();

    abstract public function checkAndDelete(Collection $parameter, string $alias);
}
