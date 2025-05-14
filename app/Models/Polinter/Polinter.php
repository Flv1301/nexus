<?php

namespace App\Models\Polinter;

use Illuminate\Database\Eloquent\Model;

class Polinter extends Model
{
    /**
     * @var string
     */
    protected $connection = 'polinter';
    /**
     * @var string
     */
    protected $table = 'mandados';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
