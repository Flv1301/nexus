<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/01/2023
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

class CaseProcedureResponse extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'case_procedure_responses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'response',
        'case_procedure_id',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function procedure(): BelongsTo
    {
        return $this->belongsTo(
            CaseProcedure::class,
            'case_procedure_id',
            'id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            CaseFile::class,
            'case_procedure_response_files',
            'procedure_response_id',
            'case_file_id',
            'id',
            'id'
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
            ->useLogName('Resposta de Tramitação de Caso');
    }
}
