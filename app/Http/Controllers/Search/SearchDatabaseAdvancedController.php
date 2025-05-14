<?php

/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Search;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Models\Utils\Databases;
use App\Services\AdvancedSearchService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchDatabaseAdvancedController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        Str::asciiRequest($request, $request->except('_token'));
        $logicalOperators = $request->logicals;
        $logicalgroup = $request->logicalgroup;
        $ordeBy = $request->orderBy;
        $orderByAscOrDesc = $request->orderByDesc ? 'desc' : 'asc';
        $operators = $request->operators;
        $values = $request->values;
        $columns = $request->columns;
        $database = $request->base;
        $paginate = $request->paginate;
        $groups = [];
        $logicalOperatorsGroup = 'where';
        $limit = is_numeric($request->limit) ? $request->limit : false;

        $query = DB::connection(Databases::getConnectionDatabase($database))->table(
            Databases::getTableDatabase($database)
        );

        foreach ($logicalOperators as $key => $logical) {
            $group = $logicalgroup[$key] ?? false;
            $column = $columns[$key];
            $operator = $operators[$key] ?? '=';
            $value = $this->formatValue($operator, $values[$key]);

            if (!$column || !$value) {
                continue;
            }

            if ($group) {
                $groups[] = [$logical, $column, $operator, $value];
            } else {
                $method = $logical === 'OR' ? 'orWhere' : 'where';
                $query->$method($column, $operator, $value);
                $logicalOperatorsGroup = $method;
            }
        }

        if ($groups) {
            $query->$logicalOperatorsGroup(function ($query) use ($groups) {
                foreach ($groups as $group) {
                    $logical = $group[0];
                    $column = $group[1];
                    $operator = $group[2];
                    $value = $group[3];
                    $method = $logical === 'OR' ? 'orWhere' : 'where';
                    $query->$method($column, $operator, $value);
                }
            });
        }

        if ($ordeBy) {
            $query->orderBy($ordeBy, $orderByAscOrDesc);
        }

        if ($limit){
            $query->limit($limit);
        }

        $results = $query->paginate($paginate)->appends($request->query());
        $view = view($database . '.advanced_search.list', compact('results', 'database'))->render();
        return response()->json(['html' => $view]);
    }


    /**
     * @param string $operator
     * @param string $value
     * @return string
     */
    private function formatValue(string $operator, string $value): string
    {
        if (!$value) {
            return $value;
        }

        $dateTimeFormat = 'd/m/Y H:i:s';
        if (\DateTime::createFromFormat($dateTimeFormat, $value) !== false) {
            return Carbon::createFromFormat($dateTimeFormat, $value)->format('Y-m-d H:i:s');
        }

        $dateFormat = 'd/m/Y';
        if (\DateTime::createFromFormat($dateFormat, $value) !== false) {
            return Carbon::createFromFormat($dateFormat, $value)->format('Y-m-d');
        }

        $likeOperators = ['like', 'not like'];
        if (in_array(strtolower($operator), $likeOperators, true)) {
            $value = strtoupper("%$value%");
        }

        return $value;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request): mixed
    {
        if (!$request->base) {
            return response('Base de dados não definida!', 404);
        }

        $service = new AdvancedSearchService();
        $database = $request->base;
        $id = $request->id;
        $result = $service->$database($id);

        $view = view('search.advanced.modal', compact('result', 'database'))->render();
        return response()->json(['html' => $view]);
    }
}
