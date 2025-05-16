<?php

namespace App\Models\Departament;

use App\Models\Cases\Cases;
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

class Sector extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'sectors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'unity_id',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function caseSharing(): BelongsToMany
    {
        return $this->belongsToMany(
            Cases::class,
            'case_sectors',
            'sector_id',
            'case_id'
        );
    }

    /**
     * @return HasMany
     */
    public function cases(): HasMany
    {
        return $this->hasMany(Cases::class, 'sector_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function unity(): HasOne
    {
        return $this->hasOne(Unity::class, 'id', 'unity_id');
    }

    /**
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::set(fn($v) => Str::upper($v));
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Setor');
    }
}
