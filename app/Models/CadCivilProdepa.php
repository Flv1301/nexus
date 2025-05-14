<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CadCivilProdepa extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $connection = 'siscivil';
    protected $table = 'cadcivil';
    protected $primaryKey = 'reg_geral_numerico';


    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Cadastro Civil Prodepa');
    }
}
