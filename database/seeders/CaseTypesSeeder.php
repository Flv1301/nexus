<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */
namespace Database\Seeders;

use App\Models\Cases\CaseType;
use Illuminate\Database\Seeder;

class CaseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CaseType::create(['name' => 'VPI']);
        CaseType::create(['name' => 'APOIO']);
        CaseType::create(['name' => 'OPERAÇÂO']);
    }
}
