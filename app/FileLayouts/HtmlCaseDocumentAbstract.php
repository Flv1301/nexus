<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts;

use App\Helpers\Arr;
use App\Models\Cases\CaseFile;
use App\Models\Cases\Cases;
use App\Models\Files\Whatsapp\Whatsapp;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

abstract class HtmlCaseDocumentAbstract extends CaseDocumentAbstract
{
    /**
     * @param UploadedFile $file
     * @param string $filename
     * @param Cases $case
     * @param string $disk
     * @throws FileNotFoundException
     */
    public function __construct(UploadedFile $file, string $filename, Cases $case, string $disk = 'tmp')
    {
        parent::__construct($file, $filename, $case, $disk);
        $this->crawler = new Crawler(Storage::disk($disk)->get($this->path));
    }

    /**
     * @return bool|$this
     * @throws FileNotFoundException
     */
    public function extract(): bool|static
    {
        try {
            if (!$this->extract) {
                $this->extract = $this->crawler->filter('#records')->each(function ($n) {
                    return $n->filter('.content-pane')->each(function ($n) {
                        return $n->html();
                    });
                });
            }

            return $this;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new FileNotFoundException('Não foi possível extrair dados do arquivo!');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function parameters(): Collection
    {
        try {
            $parameter = new Crawler($this->extract[0][0]);

            if (!$parameter->count()) {
                throw new FileNotFoundException('Não foi possível localizar os parametros!.');
            }

            $data = $parameter->filter('body > .div_table')->each(function ($node) {
                $parentText = $node->text();
                $childText = $node->filter('.div_table > div > div')->text();
                $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                return [$parentTextOnly => $childText];
            });

            if (empty($data)) {
                $data = $parameter->filter('body > div')->each(function ($node) {
                    $parentText = $node->text();
                    $childText = $node->filter('.t > div > div')->text();
                    $parentTextOnly = (string)Str::of(str_replace($childText, '', $parentText))->snake();

                    return [$parentTextOnly => $childText];
                });
            }

            $data = Arr::flatten($data);
            return collect($data);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw new FileNotFoundException('O arquivo está vazio.');
        }
    }

    /**
     * @param string $typeLayout
     * @return Collection
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function getParameters(string $typeLayout): Collection
    {
        $parameter = $this->extract()->parameters();
        $service = $parameter->get('service');

        if ($service && $service !== $typeLayout) {
            throw new Exception('Layout para o arquivo é inválido!');
        }

        return $parameter;
    }

    /**
     * @param Collection $parameter
     * @param string $alias
     * @return void
     */
    public function checkAndDelete(Collection $parameter, string $alias): void
    {
        $identifier = $parameter->get('account_identifier');
        $generated = $parameter->get('generated');

        if ($identifier && $generated) {
            $whatsapp = Whatsapp::where('account_identifier', $identifier)->where('generated', $generated)->pluck('id');

            if ($whatsapp->isNotEmpty()) {
                CaseFile::whereIn('file_id', $whatsapp)
                    ->where('file_alias', $alias)
                    ->where('user_id', $this->userAuth->id)
                    ->where('case_id', $this->case->id)
                    ->delete();

                Whatsapp::whereIn('id', $whatsapp)->delete();
            }
        }
    }

    /**
     * @param array $data
     * @param string $type
     * @return array
     */
    protected function divideInBlock(array $data, string $type): array
    {
        $blocks = [];
        $currentBlock = [];

        foreach ($data as $index => $element) {
            $hasType = isset($element[$type]);

            if ($hasType && !empty($currentBlock)) {
                $blocks[] = $currentBlock;
                $currentBlock = [];
            }
            $currentBlock = array_merge($currentBlock, $element);
        }

        if (!empty($currentBlock)) {
            $blocks[] = $currentBlock;
        }

        return $blocks;
    }
}
