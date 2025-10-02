<?php

namespace App\Models\Data;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'address';

    /**
     * @var string[]
     */
    protected $fillable = [
        'address',
        'number',
        'district',
        'city',
        'state',
        'code',
        'uf',
        'complement',
        'reference_point',
        'observacao',
        'data_do_dado',
        'fonte_do_dado',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'data_do_dado' => \App\Casts\DateCast::class,
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Address');
    }
}
