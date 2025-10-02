<?php

namespace App\Models\Utils;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class Databases extends Model
{
    /**
     * @var array|array[]
     */
    private static array $bases = [
        'nexus' => ['base' => 'pgsql', 'table' => 'persons'],
    ];

    /**
     * @param string $database
     * @return array
     */
    public static function getTableColumnsName(string $database = ''): array
    {
        try {
            if (array_key_exists($database, self::$bases)) {
//                $columns = Schema::connection(self::getConnectionDatabase($database))->getColumnListing(self::getTableDatabase($database));
                $table = self::getTableDatabase($database);

                $columns = DB::connection(self::getConnectionDatabase($database))
                    ->table('pg_catalog.pg_attribute AS a')
                    ->join('pg_catalog.pg_class AS c', 'a.attrelid', '=', 'c.oid')
                    ->where('c.relname', '=', $table)
                    ->whereIn('c.relkind', ['m', 'v'])
                    ->where('a.attnum', '>', 0)
                    ->where('a.attisdropped', false)
                    ->pluck('a.attname')
                    ->toArray();

                sort($columns);

                return array_reduce($columns, function ($result, $column) use ($database) {
                    $translationAlias = "database.$database.$column.alias";
                    $translationPlaceholder = "database.$database.$column.placeholder";

                    if (Lang::has($translationAlias) && Lang::has($translationPlaceholder)) {
                        $result[$column] = [trans($translationAlias), trans($translationPlaceholder)];
                    }

                    return $result;
                }, []);
            }

            return [];
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }

    /**
     * @param string $database
     * @return mixed|string
     */
    public static function getConnectionDatabase(string $database): mixed
    {
        return self::$bases[$database]['base'];
    }

    /**
     * @param string $database
     * @return mixed|string
     */
    public static function getTableDatabase(string $database): mixed
    {
        return self::$bases[$database]['table'];
    }
}
