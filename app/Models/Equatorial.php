<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equatorial extends Model
{
    /**
     * @var string
     */
    protected $connection = 'pgsql';
    /**
     * @var string
     */
    protected $table = 'equatorial';
    use HasFactory;
}
