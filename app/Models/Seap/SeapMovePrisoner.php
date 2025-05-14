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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SeapMovePrisoner extends Model
{
    /**
     * @var string
     */
    protected $connection = "seap";
    /**
     * @var string
     */
    protected $table = "preso_movimentacao";
    /**
     * @var string[]
     */
    protected $casts = [
        'movimentacao_data' => DateCast::class
    ];

    /**
     * @param $id
     * @return Builder[]|Collection
     */
    public static function getMovesByPrisioner($id)
    {
        return SeapMovePrisoner::query()->where('id_preso', '=', $id)->orderBy('movimentacao_data', 'asc')->get();
    }


}
