<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Services\Socialmedias;

use App\Models\Files\Facebook\Facebook;
use App\Services\IPInfoService;

class FacebookIpService
{
    /**
     * @var array
     */
    protected array $ips;

    public function __construct()
    {
        $this->ips = [];
    }

    public function findIpAndStore(): void
    {
        $this->setIpsByFileAlias();
        if ($this->ips) {
            (new IPInfoService())->store($this->ips);
        }
    }

    /**
     * @return void
     */
    public function store(): void
    {
        (new IPInfoService())->store($this->ips);
    }

    /**
     * @param Facebook $facebook
     * @return $this
     */
    public function acesslogIpAddress(Facebook $facebook): FacebookIpService
    {
        $ips = [];
        foreach ($facebook->acesslogIpAddress ?? [] as $ip) {
            if ($ip->ip_address) {
                $ips[] = $ip->ip_address;
            }
        }
        if ($ips) {
            $this->ips = array_merge($this->ips, $ips);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getIps(): array
    {
        return $this->ips;
    }

    /**
     * @param Facebook $facebook
     * @return void
     */
    public function setIps(Facebook $facebook): void
    {
        $this->acesslogIpAddress($facebook);
    }
}
