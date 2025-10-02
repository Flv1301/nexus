<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Models\Cases\Cases;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User\UserAccessDocument;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'nickname',
        'role',
        'registration',
        'cpf',
        'office',
        'birth_date',
        'unity_id',
        'sector_id',
        'email',
        'password',
        'status',
        'ddd',
        'telephone',
        'address',
        'number',
        'district',
        'city',
        'state',
        'uf',
        'code',
        'complement',
        'reference_point',
        'coordinator',
        'user_creator',
        'user_update',
        'code_controller',
        'ass_path'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => DateCast::class,
        'coordinator' => 'boolean',
        'status' => 'boolean'
    ];

    /**
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::upper($v)
        );
    }

    /**
     * @return Attribute
     */
    protected function nickname(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::upper($v)
        );
    }

    /**
     * @return Attribute
     */
    protected function email(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::lower($v)
        );
    }

    /**
     * @return Attribute
     */
    protected function cpf(): Attribute
    {
        return Attribute::set(
            fn($v) => Str::of($v)->replaceMatches('/[^0-9]++/', '')
        );
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
     * @return BelongsToMany
     */
    public function caseSharing(): BelongsToMany
    {
        return $this->belongsToMany(
            Cases::class,
            'case_users',
            'user_id',
            'case_id'
        );
    }

    /**
     * @return HasMany
     */
    public function cases(): HasMany
    {
        return $this->hasMany(
            Cases::class,
            'user_id',
            'id'
        );
    }

    /**
     * @return HasOne
     */
    public function sector(): HasOne
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
    }

    /**
     * @return HasOne
     */
    public function unity(): HasOne
    {
        return $this->hasOne(
            Unity::class,
            'id',
            'unity_id'
        );
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(
            UserAccessDocument::class,
            'user_id',
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
            ->useLogName('Usuario');
    }

    /**
     * Get the URL for the user's profile.
     *
     * @return string
     */
    public function adminlte_profile_url()
    {
        return 'profile';
    }

    /**
     * Get the user's description for the AdminLTE menu.
     *
     * @return string
     */
    public function adminlte_desc()
    {
        return $this->email;
    }
}
