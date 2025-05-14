<?php

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorMoviment extends Model
{
    /**@var string */
    protected $connection = "seap";
    /** * @var string */
    protected $table = "visitante_movimentacao";

    /**
     * @return BelongsTo
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(SeapVisitor::class, 'id_visitante', 'id_visitante');
    }

    /**
     * @return BelongsTo
     */
    public function stuck(): BelongsTo
    {
        return $this->belongsTo(Seap::class, 'id_preso', 'id_preso');
    }

    public static function getVisitorsByPrisoner($id)
    {
        $visitors = VisitorMoviment::query()->where('id_preso', '=', $id)->distinct()->get();
        return $visitors;
    }

}
