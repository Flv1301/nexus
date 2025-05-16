<?php

namespace App\Models\Emails;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailNotification extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'email_notifications';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Usuario');
    }
}
