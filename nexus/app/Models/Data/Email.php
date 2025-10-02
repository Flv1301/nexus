<?php

namespace App\Models\Data;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Email extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'emails';

    /**
     * @var string[]
     */
    protected $fillable = ['email'];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Email');
    }
}
