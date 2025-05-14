<?php

namespace App\Models\Dpa;

use App\Models\Srh\Srh;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dpa extends Model
{
    /**
     * @var string
     */
    protected $connection = 'dpa';
    /**
     * @var string
     */
    protected $table = 'dpa_proprietario';
    /**
     * @var string
     */
    protected $primaryKey = 'id_proprietario';
}
