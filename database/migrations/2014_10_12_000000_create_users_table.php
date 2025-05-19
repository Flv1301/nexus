<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('nickname')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('registration')->nullable(false)->unique();
            $table->string('cpf')->nullable(false)->unique();
            $table->string('office')->nullable(false);
            $table->string('role')->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->date('birth_date')->nullable();
            $table->rememberToken()->nullable();
            $table->boolean('status')->default(false);
            $table->string('ddd')->nullable();
            $table->string('telephone')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('uf')->nullable();
            $table->string('code')->nullable();
            $table->string('complement')->nullable();
            $table->string('reference_point')->nullable();
            $table->unsignedBigInteger('unity_id')->nullable(false);
            $table->unsignedBigInteger('sector_id')->nullable(false);
            $table->string('user_creator');
            $table->string('user_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
