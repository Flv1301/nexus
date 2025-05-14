<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Facebook;

use App\Events\FacebookEvent;
use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Models\Files\Facebook\Facebook;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class FacebookLayoutAbstract extends HtmlCaseDocumentAbstract
{
    /**
     * @var array
     */
    protected array $facebook = [];

    /**
     * @return void
     */
    public function extractedDataHomeLog(): void
    {
        $this->facebook['first_name'] = Str::of($this->facebook['name'])->match('/(?<=First)(.*?)(?=MiddleLast)/');
        $this->facebook['last_name'] = Str::of($this->facebook['name'])->match('/(?<=Last).*$/');
        unset($this->facebook['name']);
        $this->facebook['phone_numbers_verified'] = Str::contains($this->facebook['phone_numbers'], 'on') ? Str::after(
            $this->facebook['phone_numbers'],
            'on '
        ) : null;
        $this->facebook['phone_numbers'] = Str::before($this->facebook['phone_numbers'], ' ');
    }

    /**
     * @param int $slice
     * @return Collection
     */
    protected function homeLogFacebook(int $slice = 0): Collection
    {
        $this->facebook = $this->homeLog($slice)->all();
        $type = $this->facebook['service'] ?? '';

        if ($type !== 'Facebook') {
            collect();
        }

        $this->extractedDataHomeLog();

        return collect($this->facebook);
    }

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

    /**
     * @param $identifier
     * @param $generate
     * @return false
     */
    protected function verifyHome($identifier, $generate): bool
    {
        if ($identifier && $generate) {
            return Facebook::where('account_identifier', $identifier)->where('generated', $generate)->count();
        }
        return false;
    }

    /**
     * @param Facebook $facebook
     * @return void
     */
    protected function event(Facebook $facebook): void
    {
        FacebookEvent::dispatch($facebook);
    }
}
