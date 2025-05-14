<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Whatsapp;

use App\Events\WhatsappEvent;
use App\Models\Files\Whatsapp\Whatsapp;
use Illuminate\Support\Facades\Log;

class WhatsappHtmlAccessLogLayoutOld extends WhatsappLayoutAbstract
{
    /**
     * @return Whatsapp|false
     */
    public function store(): bool|Whatsapp
    {
        try {
            $homeLogWhats = $this->homeLogWhatsapp(3)->all();
            $identifier = $homeLogWhats['account_identifier'] ?? '';
            $generated = $homeLogWhats['generated'] ?? '';

            if (!$homeLogWhats || $this->verifyHome($identifier, $generated)) {
                return false;
            }

            $whatsapp = new Whatsapp($homeLogWhats);
            $whatsapp->name = 'LOG DE ACESSO WHATSAPP';
            $whatsapp->extension = 'html';
            $whatsapp->view = 'whatsapp_access_log';
            $whatsapp->save();
            $ips = $this->ips()->all();

            if ($ips) {
                $whatsapp->acesslogIpAddress()->createMany($ips);
            }

            WhatsappEvent::dispatch($whatsapp);

            return $whatsapp;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if (isset($whatsapp->id)) {
                $whatsapp->delete();
            }

            return false;
        }
    }
}
