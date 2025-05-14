<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewVCard extends Model
{
    use HasFactory;
    /** @var string  */
    protected $table = 'vcard_view';
}
