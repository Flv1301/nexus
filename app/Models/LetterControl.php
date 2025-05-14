<?php

namespace App\Models;

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LetterControl extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'unity_id',
        'sector_id',
        'recipient',
        'subject',
        'year',
        'number'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * @return BelongsTo
     */
    public function unity(): BelongsTo
    {
        return $this->belongsTo(Unity::class, 'unity_id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Controle de Oficio');
    }
}
