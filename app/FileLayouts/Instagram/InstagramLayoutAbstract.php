<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Instagram;

use App\Events\FacebookEvent;
use App\FileLayouts\Facebook\FacebookLayoutAbstract;
use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Models\Files\Facebook\Facebook;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class InstagramLayoutAbstract extends FacebookLayoutAbstract
{
    /**
     * @param int $slice
     * @return Collection
     */
    protected function homeLogInstagram(int $slice = 0): Collection
    {
        $this->facebook = $this->homeLog($slice)->all();

        if ($this->facebook['service'] !== 'Instagram') {
            collect();
        }

        $this->extractedDataHomeLog();

        return collect($this->facebook);
    }

}
