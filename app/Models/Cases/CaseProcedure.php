<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Cases;

use App\Casts\DateCast;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CaseProcedure extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'case_procedures';

    /**
     * @var string[]
     */
    protected $fillable = [
        'case_id',
        'unity_id',
        'sector_id',
        'user_id',
        'request_user_id',
        'request_unity_id',
        'request_sector_id',
        'limit_date',
        'status',
        'request',
        'created_at',
        'updated_at'
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'limit_date' => DateCast::class
    ];

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

    /**
     * @return BelongsTo
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(Cases::class, 'case_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function requestUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'request_user_id');
    }

    /**
     * @return HasOne
     */
    public function requestUnity(): HasOne
    {
        return $this->hasOne(Unity::class, 'id', 'request_unity_id');
    }

    /**
     * @return HasOne
     */
    public function requestSector(): HasOne
    {
        return $this->hasOne(Sector::class, 'id', 'request_sector_id');
    }

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            CaseFile::class,
            'case_procedure_files',
            'case_procedure_id',
            'case_file_id'
        );
    }

    /**
     * @return HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(
            CaseProcedureResponse::class,
            'case_procedure_id',
            'id'
        );
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Tramitação de Caso');
    }
}
