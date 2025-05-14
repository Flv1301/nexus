<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/12/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Cases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CaseFile extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'case_files';

    /**
     * @var string[]
     */
    protected $fillable = [
        'case_id',
        'name',
        'procedure_id',
        'user_id',
        'unity_id',
        'sector_id',
        'file_type',
        'file_layout',
        'file_alias',
        'file_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function cases(): BelongsTo
    {
        return $this->belongsTo(
            CaseProcedure::class,
            'case_id',
            'id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function procedures(): BelongsToMany
    {
        return $this->belongsToMany(
            CaseProcedure::class,
            'case_procedure_files',
            'case_procedure_id',
            'case_file_id'
        );
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Arquivo de Caso');
    }
}
