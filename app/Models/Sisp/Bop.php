<?php

namespace App\Models\Sisp;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Description of Bop
 *
 * @author 55919
 */
class Bop extends Model
{
    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "bop";
    /**
     * @var string
     */
    protected $primaryKey = 'bop_bop_key';

    use LogsActivity;

    /**
     * @return HasOne
     */
    public function boprel(): HasOne
    {
        return $this->hasOne(BopRel::class, 'boprel_bop_key', 'bop_bop_key');
    }

    /**
     * @return HasMany
     */
    public function bopenv(): HasMany
    {
        return $this->hasMany(BopEnv::class, 'bopenv_bop_key', 'bop_bop_key');
    }

    /**
     * @param $key
     * @return Builder|Model|object|null
     */
    public static function getBopByKey($key)
    {
        return Bop::query()->where('bop_bop_key', '=', $key)->first();
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('BOP');
    }
}
