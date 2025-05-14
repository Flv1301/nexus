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

class UserAccessReleaseDocument extends Model
{
    use HasFactory;

    protected $table = 'user_access_documents';
}
