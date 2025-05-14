<?php

namespace App\Http\Controllers\Gi2;

use App\Http\Controllers\Controller;
use App\Models\Gi2;
use App\Models\IMEI;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
set_time_limit(0);

class Gi2Controller extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        list($imeiCount, $gi2Count, $intersection) = $this->gi2AndImeiCount();
        $operations = Gi2::distinct()->pluck('operacao');
        return view('gi2.index', compact('imeiCount', 'gi2Count', 'intersection', 'operations'));
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function search(Request $request): Factory|View|Application
    {
        $request->validate(['imei_imsi' => 'required|min:12']);
        $gi2s = Gi2::where('imei', 'like', "%$request->imei_imsi%")->orWhere(
            'imsi',
            'like',
            "%$request->imei_imsi%"
        )->get();
        list($imeiCount, $gi2Count, $intersection) = $this->gi2AndImeiCount();
        return view('gi2.index', compact('gi2s', 'imeiCount', 'gi2Count', 'intersection'));
    }

    /**
     * @return array
     */
    public function gi2AndImeiCount(): array
    {
        $imeiCount = IMEI::query()->count();
        $gi2Count = Gi2::query()->count();
        $intersection = DB::connection("pgsql2")->table('gi2.gi2')
            ->join('sisp.boprel_imei', 'gi2.gi2.imei', '=', 'sisp.boprel_imei.imei')
            ->where('gi2.gi2.date_time', '>', '2023-03-26')
            ->distinct('gi2.gi2.imei')
            ->count();
        return array($imeiCount, $gi2Count, $intersection);
    }

    /**
     * @return Factory|View|Application
     */
    public function intersection(): Factory|View|Application
    {
        $gi2s = DB::connection("pgsql2")->table('gi2.gi2')
            ->join('sisp.boprel_imei', 'gi2.gi2.imei', '=', 'sisp.boprel_imei.imei')
            ->join('sisp.bop', 'sisp.boprel_imei.boprel_imei_bop_key', '=', 'bop_bop_key')
            ->select(
                'gi2.gi2.*',
                'sisp.bop.n_bop',
                'sisp.bop.bop_bop_key',
                'sisp.bop.registros',
                'sisp.bop.dt_registro'
            )->distinct('gi2.gi2.imei')
            ->get();
        list($imeiCount, $gi2Count, $intersection) = $this->gi2AndImeiCount();
        return view('gi2.intersection', compact('gi2s', 'imeiCount', 'gi2Count', 'intersection'));
    }


    public function filterIntersection(Request $request)
    {
        $filters = [];
        if ($request->filled('nomeOperacao')) {
            $filters[] = ['gi2.gi2.operacao', '=', $request->input('nomeOperacao')];
        }
        if ($request->filled('nomeOperadora')) {
            $filters[] = ['gi2.gi2.operador', 'ilike', '%' . $request->input('nomeOperadora') . '%'];
        }
        if ($request->filled('natureza')) {
            $filters[] = ['sisp.bop.registros', 'like', '%' . $request->input('natureza') . '%'];
        }

        $gi2s = DB::connection('pgsql2')
            ->table('gi2.gi2')
            ->join('sisp.boprel_imei', 'gi2.gi2.imei', '=', 'sisp.boprel_imei.imei')
            ->join('sisp.bop', 'sisp.boprel_imei.boprel_imei_bop_key', '=', 'bop_bop_key')
            ->select(
                'gi2.gi2.*',
                'sisp.bop.n_bop',
                'sisp.bop.bop_bop_key',
                'sisp.bop.registros',
                'sisp.bop.dt_registro'
            )
            ->where($filters)
            ->orderBy('gi2.gi2.date_time')
            ->get();

        list($imeiCount, $gi2Count, $intersection) = $this->gi2AndImeiCount();
        $operations = Gi2::distinct()->pluck('operacao');

        return view('gi2.intersection', compact('gi2s', 'imeiCount', 'gi2Count', 'intersection', 'operations'));
    }

    public static function filterIntersectionReport($operadora, $operacao)
    {
        $filters = [];
        $oneYearAgo = Carbon::now()->subYear()->toDateString();

            array_push($filters, ['gi2.gi2.operacao', '=', $operacao]);


            array_push($filters, ['gi2.gi2.operador', 'ilike', '%'.$operadora.'%']);

            array_push($filters,['sisp.bop.dt_registro', '>=', $oneYearAgo]);


        $gi2s = DB::connection("pgsql2")->table('gi2.gi2')
            ->join('sisp.boprel_imei', 'gi2.gi2.imei', '=', 'sisp.boprel_imei.imei')
            ->join('sisp.bop', 'sisp.boprel_imei.boprel_imei_bop_key', '=', 'bop_bop_key')
            ->select(
                'gi2.gi2.imei',

                'sisp.bop.dt_registro'
            )->where($filters)->distinct('gi2.gi2.imei')
            ->get();
       return $gi2s;

    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function show($id): Factory|View|Application
    {
        $gi2s = Gi2::where('id', $id)->get();
        return view('gi2.show', compact('gi2s'));
    }

    public function create()
    {
        return view('gi2.create');
    }


    public function save(Request $request)
    {
        $contador = 0;
        try {
            //pega o conteudo do arquivo
            $arquivo = $request->file("arquivo")->get();
            //separa em linhas
            $arrayarquivo = explode("\n", $arquivo);
            //guarda o cabeçalho
            $strheader = array_shift($arrayarquivo);
            //tranforma o cabeçalho em array
            $header = str_getcsv($strheader, ',', '"',);
            $newHeader = Gi2::treatmentHeader($header);

            foreach ($arrayarquivo as $rows) {
                try{
                    //transforma cada linha em array

                    $arrayrow = str_getcsv($rows, ',', '"',);
                    $nlinhas = count($arrayarquivo);

                    //cria um array associativo com o nome do cabeçalho e seu valor
                    if (count($newHeader) == count($arrayrow) and count($arrayrow) != 0) {


                        $arrayGi2 = array_combine($newHeader, $arrayrow);

                        $arrayGi2['altitude'] = trim(" m", $arrayGi2['altitude']);

                        foreach ($arrayGi2 as $key => $value) {
                            if ($arrayGi2[$key] == "") {
                                $arrayGi2[$key] = null;
                            }
                        }

                        $gi2 = new Gi2();
                        $gi2->fill($arrayGi2);
                        $gi2->save();
                        $contador++;
                        echo "$contador / $nlinhas \n";

                    }
                }catch(\Exception $e){
                    echo $e->getMessage();
                }
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

        return view('gi2.save')->with("arquivo", $arrayarquivo);
    }
//    public function show($latitude, $longitude){
//
//        $gi2 = ['latitude' => $latitude,'longitude' => $longitude];
//        return view('gi2.map-radius')->with("gi2",$gi2);
//    }

}
