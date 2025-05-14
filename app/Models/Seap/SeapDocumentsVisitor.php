<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/07/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;

class SeapDocumentsVisitor extends Model
{
    /** @var string */
    protected $connection = "seap";
    /** @var string */
    protected $table = "visitante_documento";

}
