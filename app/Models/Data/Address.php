<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/01/2023
 * @copyright NIP CIBER-LAB @2023
 */
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
        'user_id',
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
