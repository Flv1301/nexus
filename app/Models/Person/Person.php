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
use App\Models\Person\Vehicle;
use App\Models\Person\VinculoOrcrim;
use App\Models\Person\Pcpa;
use App\Models\Person\Tj;
use App\Models\Person\Arma;
use App\Models\Person\Rais;
use App\Models\Person\Bancario;
use App\Models\Person\Doc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'spouse_name',
        'spouse_cpf',
        'tatto',
        'conselho_de_classe',
        'user_id',
        'stuck',
        'detainee_registration',
        'detainee_date',
        'detainee_uf',
        'detainee_city',
        'cela',
        'occupation',
        'dead',
        'observation',
        'warrant',
        'evadido',
        'active_orcrim',
        'orcrim',
        'orcrim_office',
        'orcrim_occupation_area',
        'orcrim_matricula',
        'orcrim_padrinho',
        'vulgo_padrinho',
        'data_ingresso',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'birth_date' => DateCast::class,
        'detainee_date' => DateCast::class,
        'data_ingresso' => DateCast::class,
        'name' => UpCaseTextCast::class,
        'nickname' => UpCaseTextCast::class,
        'father' => UpCaseTextCast::class,
        'mother' => UpCaseTextCast::class,
        'occupation' => UpCaseTextCast::class,
        'orcrim' => UpCaseTextCast::class,
        'orcrimOffice' => UpCaseTextCast::class,
        'orcrimOccupationArea' => UpCaseTextCast::class,
        'dead' => 'boolean',
        'warrant' => 'boolean',
        'evadido' => 'boolean',
        'active_orcrim' => 'boolean',
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
     * @return HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(PersonCompany::class);
    }

    /**
     * @return HasMany
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * @return HasMany
     */
    public function vinculoOrcrims(): HasMany
    {
        return $this->hasMany(VinculoOrcrim::class);
    }

    /**
     * @return HasMany
     */
    public function pcpas(): HasMany
    {
        return $this->hasMany(Pcpa::class);
    }

    /**
     * @return HasMany
     */
    public function tjs(): HasMany
    {
        return $this->hasMany(Tj::class);
    }

    /**
     * @return HasMany
     */
    public function armas(): HasMany
    {
        return $this->hasMany(Arma::class);
    }

    /**
     * @return HasMany
     */
    public function rais(): HasMany
    {
        return $this->hasMany(Rais::class);
    }

    /**
     * @return HasMany
     */
    public function bancarios(): HasMany
    {
        return $this->hasMany(Bancario::class);
    }

    /**
     * @return HasMany
     */
    public function docs(): HasMany
    {
        return $this->hasMany(Doc::class);
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

    /**
     * Get the first image thumbnail for display in lists
     * 
     * @return string|null
     */
    public function getFirstImageThumbnail(): ?string
    {
        if ($this->images && $this->images->count() > 0) {
            $image = $this->images->first();
            if (\Illuminate\Support\Facades\Storage::exists($image->path)) {
                $type = \Illuminate\Support\Facades\Storage::mimeType($image->path);
                $content = \Illuminate\Support\Facades\Storage::get($image->path);
                $content = base64_encode($content);
                return "data:{$type};base64,{$content}";
            }
        }
        return null;
    }

    /**
     * Check if person has images
     * 
     * @return bool
     */
    public function hasImages(): bool
    {
        return $this->images && $this->images->count() > 0;
    }
}
