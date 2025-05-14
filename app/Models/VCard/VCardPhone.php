<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 23/03/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\VCard;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VCardPhone extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'vcard_phones';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = ['number', 'vcard_id'];

    /**
     * @return Attribute
     */
    protected function number(): Attribute
    {
        return Attribute::set(fn($v) => trim(preg_replace('/[^0-9]/', '', $v)));
    }

    /**
     * @return BelongsTo
     */
    public function vcard(): BelongsTo
    {
        return $this->belongsTo(
            VCard::class,
            'vcard_id',
            'id'
        );
    }
}
