<?php

namespace App\Models\Departament;

use App\Models\Cases\Cases;
use App\Models\Data\Address;
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

class Unity extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'unitys';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'ddd',
        'telephone',
        'address_id'
    ];

    /**
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::set(fn($v) => Str::upper($v));
    }

    /**
     * @return Attribute
     */
    protected function telephone(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::of($v)->replaceMatches('/[^0-9]++/', '')
        );
    }

    /**
     * @return HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    /**
     * @return BelongsToMany
     */
    public function caseSharing(): BelongsToMany
    {
        return $this->belongsToMany(
            Cases::class,
            'case_unitys',
            'unity_id',
            'case_id'
        );
    }

    /**
     * @return HasMany
     */
    public function cases(): HasMany
    {
        return $this->hasMany(Cases::class, 'unity_id', 'id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Unidade');
    }
}
