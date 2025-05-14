<?php

namespace App\Services\Socialmedias;

use App\Models\Files\Whatsapp\Whatsapp;
use App\Services\IPInfoService;

class WhatsappIpService
{

    /** @var Whatsapp */
    private Whatsapp $whatsapp;
    /** @var array */
    private array $ips;

    /**
     * @param Whatsapp $whatsapp
     */
    public function __construct(Whatsapp $whatsapp)
    {
        $this->whatsapp = $whatsapp;
        $this->ips = [];
    }

    /**
     * @return void
     */
    public function findIpAndStore(): void
    {
        $this->setIpsByFileAlias();
        if ($this->ips) {
            (new IPInfoService())->store($this->ips);
        }
    }

    /**
     * @return $this
     */
    public function ipsTicket(): WhatsappIpService
    {
        $this->extractCallsIp();
        $this->extractMessagesIp();
        return $this;
    }

    /**
     * @return $this
     */
    public function ipsAccessLog(): WhatsappIpService
    {
        $this->extractAcesslogIpAddress();
        return $this;
    }

    /**
     * @return void
     */
    public function setIpsByFileAlias(): void
    {
        switch ($this->whatsapp->view) {
            case 'whatsapp_ticket':
                $this->ipsTicket();
                break;
            case 'whatsapp_log':
                $this->ipsAccessLog();
                break;
        }
    }

    /**
     * @return void
     */
    private function extractCallsIp(): void
    {
        $this->ips = array_merge(
            $this->ips,
            $this->whatsapp->calls->flatMap(function ($call) {
                return $call->events->pluck('from_ip')->filter();
            })->toArray()
        );
    }

    /**
     * @return void
     */
    private function extractMessagesIp(): void
    {
        $this->ips = array_merge($this->ips, $this->whatsapp->messages->pluck('sender_ip')->filter()->toArray());
    }

    /**
     * @return void
     */
    private function extractAcesslogIpAddress(): void
    {
        $this->ips = array_merge(
            $this->ips,
            $this->whatsapp->acesslogIpAddress->pluck('ip_address')->filter()->toArray()
        );
    }

    /**
     * @return array
     */
    public function getIps(): array
    {
        return $this->ips;
    }
}
