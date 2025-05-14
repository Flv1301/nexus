<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Seap;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seap extends Model
{
    use HasFactory;

    /**@var string */
    protected $connection = "seap";
    /** @var string */
    protected $table = "preso";
    /** @var string */
    protected $primaryKey = 'id_preso';
    /** @var string[] */
    protected $casts = [
        'preso_datanascimento' => DateCast::class,
        'preso_dataultimaprisao' => DateCast::class,
    ];

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(SeapDocumentPrisoner::class, 'id_preso', 'id_preso');
    }

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(PrisonerPhoto::class, 'id_preso', 'id_preso');
    }

    /**
     * @return HasMany
     */
    public function moviments(): HasMany
    {
        return $this->hasMany(SeapMovePrisoner::class, 'id_preso', 'id_preso');
    }

    /**
     * @return HasMany
     */
    public function peculiaritys(): HasMany
    {
        return $this->hasMany(SeapPeculiarityPrisoner::class, 'id_preso', 'id_preso');
    }

    /**
     * @return HasMany
     */
    public function visitorMoviments(): HasMany
    {
        return $this->hasMany(VisitorMoviment::class, 'id_preso', 'id_preso');
    }


//    /**
//     * @param $id
//     * @return mixed
//     */
//    public static function getPrisonerById($id): mixed
//    {
//        return Seap::find($id);
//    }
//
//    /**
//     * @param $cpf
//     * @return mixed
//     */
//    public static function getPrisonerByCPF($cpf): mixed
//    {
//        return Seap::query()->join('seap.preso_documento', 'preso.id_preso', '=', 'preso_documento.id_preso')->where(
//            'presodocumento_numero',
//            '=',
//            $cpf
//        )->first();
//    }
//
//    /**
//     * @param $name
//     * @param $mother
//     * @return mixed
//     */
//    public static function getPrisonerByMotherAndName($name, $mother): mixed
//    {
//        return Seap::query()->where('preso_nome', 'ilike', $name)->where(
//            'presofiliacao_mae',
//            'ilike',
//            $mother
//        )->first();
//    }
//
//    /**
//     * @param $name
//     * @param $birth
//     * @return mixed
//     */
//    public static function getPrisonerByNameAndBirthday($name, $birth): mixed
//    {
//        return Seap::query()->where('preso_nome', 'ilike', $name)->whereRaw(
//            'DATE(preso_datanascimento) = ?',
//            $birth
//        )->first();
//    }

}

