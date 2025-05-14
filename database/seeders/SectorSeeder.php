<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace Database\Seeders;

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unity = (Unity::where('name', 'NIP')->first())->id;
        Sector::create(['name' => 'CIBER LAB', 'unity_id' => $unity]);
        Sector::create(['name' => 'GTF', 'unity_id' => $unity]);
        Sector::create(['name' => 'LAB LD', 'unity_id' => $unity]);
        Sector::create(['name' => 'GTE', 'unity_id' => $unity]);
    }
}
