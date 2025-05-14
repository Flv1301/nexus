<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models;

use App\Models\Data\Image;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Support extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'supports';

    /**
     * @var string[]
     */
    protected $fillable = ['title', 'description', 'user_id', 'status'];

    /**
     * @return Attribute
     */
    public function title(): Attribute
    {
        return Attribute::set(fn($v) => Str::upper($v));
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'support_images',
            'support_id',
            'image_id'
        );
    }

    /**
     * @return HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SupportResponse::class, 'support_id', 'id');
    }
}
