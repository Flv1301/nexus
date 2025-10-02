<?php

namespace App\Models\Person;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tj extends Model
{
    use HasFactory;

    protected $table = 'tjs';

    protected $fillable = [
        'person_id',
        'situacao',
        'data_denuncia',
        'data_condenacao',
        'processo',
        'natureza',
        'classe',
        'autor',
        'data',
        'uf',
        'comarca',
        'jurisdicao',
        'processo_prevento',
        'situacao_processo',
        'distribuicao',
        'orgao_julgador',
        'orgao_julgador_colegiado',
        'competencia',
        'numero_inquerito_policial',
        'valor_causa',
        'advogado',
        'prioridade',
        'gratuidade',
    ];

    protected $casts = [
        'data' => DateCast::class,
        'data_denuncia' => DateCast::class,
        'data_condenacao' => DateCast::class,
        'prioridade' => 'boolean',
        'gratuidade' => 'boolean',
        'valor_causa' => 'decimal:2',
    ];

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
} 