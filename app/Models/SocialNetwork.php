<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialNetwork extends Model
{
    use HasFactory;
    use SoftDeletes;
//    use LogsActivity;

    /**
     * @var string[]
     */
    protected $fillable = ['social', 'type'];
    /**
     * @var string
     */
    protected $table = 'social_networks';

    //    /**
//     * @return LogOptions
//     */
//    public function getActivitylogOptions(): LogOptions
//    {
//        return LogOptions::defaults()
//            ->logFillable()
//            ->logOnlyDirty()
//            ->useLogName('Email');
//    }
}
