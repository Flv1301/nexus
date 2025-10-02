<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace Database\Seeders;

use App\Models\Departament\Unity;
use Illuminate\Database\Seeder;

class UnitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unity::create(['name' => 'NIP']);
        Unity::create(['name' => 'NAI']);
    }
}
