<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Document extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'documents';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'extension',
        'view',
        'path',
        'created_at',
        'updated_at'
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Documento');
    }
}
