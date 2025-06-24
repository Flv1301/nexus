<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\PermissionsEnum;

class WhatsappPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            PermissionsEnum::WHATSAPP->value,
            PermissionsEnum::WHATSAPP_READ->value,
            PermissionsEnum::WHATSAPP_WRITE->value,
            PermissionsEnum::WHATSAPP_DELETE->value,
            PermissionsEnum::WHATSAPP_UPDATE->value,
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissões WhatsApp criadas com sucesso!');
    }
} 