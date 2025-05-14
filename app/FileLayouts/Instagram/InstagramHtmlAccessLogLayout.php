<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Instagram;

use App\Events\FacebookEvent;
use App\FileLayouts\Facebook\FacebookLayoutAbstract;
use App\Models\Files\Facebook\Facebook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstagramHtmlAccessLogLayout extends FacebookLayoutAbstract
{
    /**
     * @return bool|Facebook
     */
    public function store(): bool|Facebook
    {
        try {
            $instagramHomeLog = $this->homeLogInstagram(3)->all();
            $identifier = $instagramHomeLog['account_identifier'] ?? '';
            $generated = $instagramHomeLog['generated'] ?? '';

            if (!$instagramHomeLog || $this->verifyHome($identifier, $generated)) {
                return false;
            }

            $facebook = new Facebook($instagramHomeLog);
            $facebook->name = 'LOG DE ACESSO INSTAGRAM';
            $facebook->extension = 'html';
            $facebook->view = 'facebook_access_log';
            $facebook->save();
            $ips = $this->ips()->all();

            if ($ips) {
                $facebook->acesslogIpAddress()->createMany($ips);
            }

            FacebookEvent::dispatch($facebook);
            return $facebook;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception->getMessage());
            Storage::delete($this->path);

            if (isset($facebook)) {
                ($facebook)->delete();
            }

            return false;
        }
    }
}
