<?php

namespace App\Models\Seap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeapVisitor extends Model
{
    /** @var string */
    protected $connection = 'seap';
    /** @var string */
    protected $table = 'visitante';
    /** @var string */
    protected $primaryKey = 'id_visitante';

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(SeapPhotoVisitor::class, 'id_visitante', 'id_visitante');
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(SeapDocumentsVisitor::class, 'id_visitante', 'id_visitante');
    }

    /**
     * @return HasMany
     */
    public function moviments(): HasMany
    {
        return $this->hasMany(VisitorMoviment::class, 'id_visitante', 'id_visitante');
    }

    public static function getVisitorById($id)
    {
        return SeapVisitor::query()->where('id_visitante', '=', $id)->first();
    }
}
