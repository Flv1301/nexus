<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccessHistorys extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_access_historys', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('ip_public');
            $table->string('port');
            $table->text('user_agent');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();

            $table->foreignIdFor(User::class, 'user_id');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_access_historys');
    }
}
