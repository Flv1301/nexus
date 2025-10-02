<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\APIs;

use Illuminate\Support\Facades\Log;
use ipinfo\ipinfo\Details;
use ipinfo\ipinfo\IPinfo;
use ipinfo\ipinfo\IPinfoException;

class IPInfoApi
{
    /**
     * @var IPinfo
     */
    private static IPinfo $api;

    public function __construct()
    {
        if (!isset(IPInfoApi::$api)) {
            IPInfoApi::$api = new IPinfo(env('IPINFO_KEY'));
        }

        return $this;
    }

    /**
     * @return IPinfo
     */
    public static function instance(): IPinfo
    {
        if (!isset(self::$api)) {
            self::$api = new IPinfo(env('IPINFO_KEY'));
        }

        return self::$api;
    }

    /**
     * @param array $ips
     * @return array
     */
    public function IPBatch(array $ips): array
    {
        return IPInfoApi::$api->getBatchDetails($ips);
    }

    /**
     * @param string $ip
     * @return array|Details
     */
    public function IP(string $ip): Details|array
    {
        try {
            return IPInfoApi::$api->getDetails($ip);
        } catch (IPinfoException $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }
}
