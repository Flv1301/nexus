<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('model')->nullable()->after('brand');
            $table->string('year')->nullable()->after('model');
            $table->string('color')->nullable()->after('year');
            $table->string('plate')->nullable()->after('color');
            $table->string('jurisdiction')->nullable()->after('plate');
            $table->string('status')->nullable()->after('jurisdiction');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['model', 'year', 'color', 'plate', 'jurisdiction', 'status']);
        });
    }
}; 