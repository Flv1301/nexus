<?php

namespace Database\Seeders;

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Administrador',
            'nickname' => 'Super',
            'email' => 'super@pcpa.com',
            'password' => Hash::make('@Nip974#'),
            'remember_token' => Str::random(10),
            'registration' => '1',
            'cpf' => '11111111111',
            'birth_date' => '01/12/2022',
            'office' => 'Super',
            'role' => 'Super',
            'unity_id' => Unity::all()->first()->id,
            'sector_id' => Sector::all()->first()->id,
        ])->assignRole('Super');
        User::create([
            'name' => 'Administrador',
            'nickname' => 'Administrador',
            'email' => 'administrador@pcpa.com',
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
            'registration' => '2',
            'cpf' => '11111111112',
            'birth_date' => '01/12/2022',
            'office' => 'Administrativo',
            'role' => 'Administrador',
            'unity_id' => Unity::all()->first()->id,
            'sector_id' => Sector::all()->first()->id,
        ])->assignRole('Administrador');
    }
}
