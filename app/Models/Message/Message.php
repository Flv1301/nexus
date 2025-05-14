<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends Model
{
    use LogsActivity;
    use HasFactory;

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Mensagem');
    }

    /**
     * @return HasMany
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class, 'message_id', 'id');
    }
}
