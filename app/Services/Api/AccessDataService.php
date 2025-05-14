<?php

namespace App\Services\Api;

use App\Dto\AccessDataDto;
use App\Repositories\Eloquent\AccessDataRepositoryEloquentORM;
use App\Services\Api\Public\Ipify;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use stdClass;

class AccessDataService
{
    public function __construct(protected AccessDataRepositoryEloquentORM $service)
    {
    }

    public function identifyAccessUser(Request $request, User $user): void
    {
        $data = new stdClass();
        $agent = new Agent();
        $data->user_id = $user->id;
        $data->ip_local = $request->ip();
        $data->ip_global = Ipify::publicIP();
        $data->agent = $request->header('User-Agent');
        $data->device = $agent->device();
        $data->type_device = $agent->deviceType();
        $data->platform = $agent->platform();
        $data->browser = $agent->browser();
        $dto = AccessDataDto::makeFromRequest($data);
        $this->service->create($dto);
    }
}
