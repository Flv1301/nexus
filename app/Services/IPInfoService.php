<?php

namespace App\Services;

use App\APIs\IPInfoApi;
use App\Helpers\Arr;
use App\Models\Utils\IpInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ipinfo\ipinfo\Details;

class IPInfoService
{
    /**
     * @var IPInfoApi
     */
    private IPInfoApi $ipApi;

    public function __construct()
    {
        $this->ipApi = new IPInfoApi();
        return $this;
    }

    /**
     * @param $ips
     * @return Collection
     */
    public function ipFind7Days($ips): Collection
    {
        return DB::table('ipinfos')->whereIn('ip', $ips)
            ->where('updated_at', '<=', now()->addDays(7))
            ->pluck('ip');
    }

    /**
     * @param array $data
     * @return void
     */
    public function updateOrCreateBatch(array $data): void
    {
        try {
            foreach ($data as $value) {
                if (is_array($value) && array_key_exists('ip', $value)) {
                    $value = Arr::flatten($value);
                    IpInfo::updateOrCreate(['ip' => $value['ip']], $value);
                } else {
                    Log::warning('IPInfoService: Valor ignorado - não é array ou não contém chave "ip"', [
                        'value' => $value,
                        'type' => gettype($value)
                    ]);
                }
            }
        } catch (\Exception $exception) {
            Log::error('IPInfoService: Erro em updateOrCreateBatch - ' . $exception->getMessage(), [
                'exception' => $exception,
                'data_sample' => array_slice($data, 0, 3)
            ]);
        }
    }

    /**
     * @param array $ips
     * @return array
     */
    public function findIpBatch(array $ips): array
    {
        if ($ips) {
            return $this->ipApi->IPBatch($ips);
        }

        return [];
    }

    /**
     * @param string $ip
     * @return Details|array
     */
    public function findIP(string $ip): Details|array
    {
        if ($ip) {
            return $this->ipApi->IP($ip);
        }

        return [];
    }

    /**
     * Verifica o período de 7 dias, consulta na API e atualiza ou gera novo dado.
     *
     * @param array $ips
     * @return void
     */
    public function store(array $ips): void
    {
        if ($ips) {
            $ips = array_unique($ips);
            $ip7d = self::ipFind7Days($ips);

            if ($ip7d->count()) {
                $ips = array_diff($ips, $ip7d->all());
            }

            if (count($ips) > 100) {
                foreach (Arr::divideArray100($ips) as $ips) {
                    $data = self::findIpBatch($ips);
                    self::updateOrCreateBatch($data);
                }
            } else {
                $data = self::findIpBatch($ips);
                self::updateOrCreateBatch($data);
            }
        }
    }
}
