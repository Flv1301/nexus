<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risp extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'risps';

    /**
     * @var string[]
     */
    protected $fillable = [
      'description'
    ];
}
