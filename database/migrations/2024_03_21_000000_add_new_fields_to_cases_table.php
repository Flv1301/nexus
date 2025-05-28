<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->date('data_abertura')->nullable();
            $table->string('pje')->nullable();
            $table->string('pic')->nullable();
            $table->string('saj')->nullable();
            $table->string('gedoc')->nullable();
            $table->string('noticia_fato')->nullable();
            $table->string('portaria')->nullable();
            $table->string('numero_operacao')->nullable();
            $table->string('grau')->nullable();
            $table->string('numero_segundo_grau')->nullable();
            $table->text('relato_juiz')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn([
                'data_abertura',
                'pje',
                'pic',
                'saj',
                'gedoc',
                'noticia_fato',
                'portaria',
                'numero_operacao',
                'grau',
                'numero_segundo_grau',
                'relato_juiz'
            ]);
        });
    }
} 