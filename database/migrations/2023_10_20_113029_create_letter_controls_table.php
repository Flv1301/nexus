<?php

use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterControlsTable extends Migration
{
    /**
     * @return void
     */

    public function up(): void
    {
        Schema::create('letter_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Unity::class);
            $table->foreignIdFor(Sector::class);
            $table->string('recipient')->nullable();
            $table->string('subject')->nullable();
            $table->timestamps();
            $table->integer('year')->nullable();
            $table->unsignedBigInteger('number');
        });
    }


    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_controls');
    }
}
