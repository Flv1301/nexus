<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\FileLayouts\Whatsapp;

use App\Events\WhatsappEvent;
use App\FileLayouts\HtmlCaseDocumentAbstract;
use App\Models\Files\Whatsapp\Whatsapp;
use Illuminate\Support\Collection;

abstract class WhatsappLayoutAbstract_old extends HtmlCaseDocumentAbstract
{

    abstract public function messages();
    abstract public function calls();

    /**
     * @param string $identifier
     * @param string $generate
     * @return bool
     */
    protected function verifyParameters(string $identifier, string $generate): bool
    {
        if ($identifier && $generate) {
            return Whatsapp::where('account_identifier', $identifier)
                ->where('generated', $generate)
                ->whereNull('deleted_at')
                ->count();
        }
        return false;
    }

    /**
     * @param Whatsapp $whats
     * @return void
     */
    protected function event(Whatsapp $whats): void
    {
        WhatsappEvent::dispatch($whats);
    }
}
