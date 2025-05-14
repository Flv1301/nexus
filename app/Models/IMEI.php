<?php

namespace App\Models;

use App\Models\Sisp\Bop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IMEI extends Model
{
    /**
     * @var string
     */
    protected $connection = "sisp";
    /**
     * @var string
     */
    protected $table = "boprel_imei";
    /**
     * @var string[]
     */
    protected $fillable = ['n_bop', 'imei'];
    /**
     * @var string
     */
    protected $primaryKey = 'imei';

    use HasFactory;
    use LogsActivity;


    /**
     * @return HasMany
     */
    public function bop(): HasMany
    {
        return $this->hasMany(Bop::class, 'n_bop', 'n_bop');
    }

    /**
     * @return HasMany
     */
    public function gi2(): HasMany
    {
        return $this->hasMany(Gi2::class, 'imei', 'imei');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('IMEI');
    }
}
