<?php

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpInfo extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'ipinfos';

    /**
     * @var string
     */
    protected $primaryKey = 'ip';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'ip',
        'ans',
        'city',
        'region',
        'country',
        'country_name',
        'loc',
        'org',
        'postal',
        'name',
        'domain',
        'route',
        'type',
        'timezone',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];
}
