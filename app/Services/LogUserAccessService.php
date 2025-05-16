<?php

namespace App\Services;

use App\Models\User\UserAccessHistory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserAccessService
{
    /**
     * @param Request $request
     * @return bool
     */
    public function userAccessHistory(Request $request): bool
    {
        try {
            $ipPublic = $this->getPublicIp() ?? '';
            UserAccessHistory::create([
                'ip_public' => $ipPublic,
                'ip' => $request->ip(),
                'port' => $request->getPort(),
                'user_agent' => $request->header('User-Agent'),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'user_id' => Auth::id()
            ]);

            if ($ipPublic) {
                $this->ipInfo($ipPublic);
            }

            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }

    /**
     * @return string
     */
    public function getPublicIp(): string
    {
        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->get('https://api64.ipify.org');

            return $response->getBody()->getContents();
        } catch (GuzzleException $exception) {
            Log::error($exception->getMessage());

            return '';
        }
    }

    /**
     * @param string $ip
     * @return void
     */
    protected function ipInfo(string $ip): void
    {
        $ipInfo = new IPInfoService();
        $ipInfo->store([$ip]);
    }
}
