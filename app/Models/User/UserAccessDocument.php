<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/08/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\User;

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserAccessDocument extends Model
{
    use HasFactory;
    use LogsActivity;

    /** @var string[] */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'path',
        'agree',
        'analyst_id',
        'unity_id',
        'sector_id',
        'observation',
        'created_at',
        'updated_at'
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Documentação de Usuário');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function analyst(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'analyst_id');
    }

    /**
     * @return HasOne
     */
    public function unity(): HasOne
    {
        return $this->hasOne(Unity::class, 'id', 'unity_id');
    }

    /**
     * @return HasOne
     */
    public function sector(): HasOne
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
    }
}
