<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Facebook;

use App\Events\FacebookEvent;
use App\Models\Files\Facebook\Facebook;
use Illuminate\Support\Facades\Log;

class FacebookHtmlAccessLogLayoutOld extends FacebookLayoutAbstract
{
    /**
     * @return bool|Facebook
     */
    public function store(): bool|Facebook
    {
        try {
            $facebookHomeLog = $this->homeLogFacebook(3)->all();
            $identifier = $facebookHomeLog['account_identifier'] ?? '';
            $generated = $facebookHomeLog['generated'] ?? '';

            if (!$facebookHomeLog || $this->verifyHome($identifier, $generated)) {
                return false;
            }

            $facebook = new Facebook($facebookHomeLog);
            $facebook->name = 'LOG DE ACESSO FACEBOOK';
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
            Log::error($exception->getMessage());

            if (isset($facebook)) {
                ($facebook)->delete();
            }

            return false;
        }
    }
}
