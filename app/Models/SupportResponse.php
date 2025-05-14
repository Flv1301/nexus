<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models;

use App\Models\Data\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportResponse extends Model
{
    use HasFactory;

    protected $table = 'support_responses';

    protected $fillable = ['response', 'user_id', 'redirect_user_id', 'support_id'];

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'support_response_images',
            'support_response_id',
            'image_id'
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
    public function support(): HasOne
    {
        return $this->hasOne(Support::class, 'id', 'support_id');
    }
}
