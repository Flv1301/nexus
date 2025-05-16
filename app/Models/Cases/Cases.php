<?php

namespace App\Models\Cases;

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\Person\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cases extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'cases';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string[]
     */
    protected $fillable = [
        'identifier',
        'name',
        'subject',
        'process',
        'status',
        'resume',
        'user_id',
        'unity_id',
        'sector_id',
        'created_at',
        'updated_at'
    ];

    /**
     * @return Attribute
     */
    public function name(): Attribute
    {
        return Attribute::set(fn($v) => Str::upper($v));
    }

    /**
     * @return Attribute
     */
    public function subject(): Attribute
    {
        return Attribute::set(fn($v) => Str::upper($v));
    }

    /**
     * @var string[]
     */
    protected $casts = [
        'date_start' => 'date:Y-m-d',
        'date_end' => 'date:Y-m-d',
    ];

    /**
     * @return HasMany
     */
    public function procedures(): HasMany
    {
        return $this->hasMany(
            CaseProcedure::class,
            'case_id',
            'id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'case_users',
            'case_id',
            'user_id'
        );
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(CaseFile::class, 'case_id');
    }

    /**
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'case_persons',
            'case_id',
            'person_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function unitys(): BelongsToMany
    {
        return $this->belongsToMany(
            Unity::class,
            'case_unitys',
            'case_id',
            'unity_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(
            Sector::class,
            'case_sectors',
            'case_id',
            'sector_id'
        );
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
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Caso');
    }
}
