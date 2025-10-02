<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 03/02/2023
 * @copyright NIP CIBER-LAB @2023
 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Super']);
        Role::create(['name' => 'Administrador']);
        Role::create(['name' => 'Usuario']);
        Role::create(['name' => 'Delegado']);
        Role::create(['name' => 'Analista']);
    }
}
