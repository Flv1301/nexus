<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            ['name' => 'whatsapp', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'whatsapp.ler', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'whatsapp.cadastrar', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'whatsapp.excluir', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'whatsapp.atualizar', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($permissions as $permission) {
            // Verificar se a permissão já existe antes de inserir
            $exists = DB::table('permissions')->where('name', $permission['name'])->exists();
            if (!$exists) {
                DB::table('permissions')->insert($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'whatsapp',
            'whatsapp.ler',
            'whatsapp.cadastrar', 
            'whatsapp.excluir',
            'whatsapp.atualizar'
        ])->delete();
    }
}; 