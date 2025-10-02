<?php

namespace App\Dto;

use App\Dto\Contract\AccessDataDtoInterface;

class AccessDataDto implements AccessDataDtoInterface
{
    public function __construct(
        public int     $user_id,
        public string  $ip_local,
        public string  $ip_global,
        public string  $agent,
        public ?string $device,
        public ?string $type_device,
        public ?string $platform,
        public ?string $browser,
        public ?string $latitude,
        public ?string $longitude,
    )
    {
    }

    /**
     * @param object $data
     * @return self
     */
    public static function makeFromRequest(object $data): self
    {
        return new self(
            user_id: $data->user_id,
            ip_local: $data->ip_local,
            ip_global: $data->ip_global,
            agent: $data->agent,
            device: $data->device ?? null,
            type_device: $data->type_device ?? null,
            platform: $data->platform ?? null,
            browser: $data->browser ?? null,
            latitude: $data->latitude ?? null,
            longitude: $data->longitude ?? null
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
