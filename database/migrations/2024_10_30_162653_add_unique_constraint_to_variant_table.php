<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('variant_tabel', function (Blueprint $table) {
            // Create a unique constraint on eksperimen_id and variant_name together
            $table->unique(['eksperimen_id', 'variant_name']);

        });
    }

    public function down()
    {
        Schema::table('variant_tabel', function (Blueprint $table) {
            $table->dropUnique(['eksperimen_id', 'variant_name']);
        });
    }
};
