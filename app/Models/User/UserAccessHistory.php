<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 21/06/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessHistory extends Model
{
    use HasFactory;
    /** @var string  */
    protected $table = 'user_access_historys';
    /** @var string[]  */
    protected $fillable = [
        'ip',
        'ip_public',
        'port',
        'user_agent',
        'latitude',
        'longitude',
        'user_id'
    ];
}
