<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use App\Casts\UpCaseTextCast;
use App\Models\Data\Address;
use App\Models\Data\Email;
use App\Models\Data\Image;
use App\Models\Data\Telephone;
use App\Models\SocialNetwork;
use App\Models\VCard\VCard;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Person extends Model
{
    use HasFactory;

    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'persons';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'nickname',
        'birth_date',
        'cpf',
        'rg',
        'voter_registration',
        'birth_city',
        'uf_birth_city',
        'sex',
        'father',
        'mother',
        'tatto',
        'user_id',
        'stuck',
        'detainee_registration',
        'detainee_date',
        'detainee_uf',
        'detainee_city',
        'occupation',
        'dead',
        'observation',
        'warrant',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'birth_date' => DateCast::class,
        'name' => UpCaseTextCast::class,
        'nickname' => UpCaseTextCast::class,
        'orcrim' => UpCaseTextCast::class,
        'orcrimOffice' => UpCaseTextCast::class,
        'orcrimOccupationArea' => UpCaseTextCast::class,
        'father' => UpCaseTextCast::class,
        'mother' => UpCaseTextCast::class,
        'occupation' => UpCaseTextCast::class,
        'detaineeDate' => DateCast::class,

    ];


    /**
     * @return BelongsToMany
     */
    public function address(): BelongsToMany
    {
        return $this->belongsToMany(
            Address::class,
            'person_address',
            'person_id',
            'address_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function telephones(): BelongsToMany
    {
        return $this->belongsToMany(
            Telephone::class,
            'person_telephones',
            'person_id',
            'telephone_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(
            Email::class,
            'person_emails',
            'person_id',
            'email_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function socials(): BelongsToMany
    {
        return $this->belongsToMany(
            SocialNetwork::class,
            'person_social_networks',
            'person_id',
            'social_network_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'person_images',
            'person_id',
            'image_id',
        );
    }

    /**
     * @return HasMany
     */
    public function vcards(): HasMany
    {
        return $this->hasMany(VCard::class, 'person_id', 'id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Pessoa');
    }
}
