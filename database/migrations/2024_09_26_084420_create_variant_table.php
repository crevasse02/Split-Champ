<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variant_tabel', function (Blueprint $table) {
            $table->id('variant_id');
            $table->unsignedBigInteger('eksperimen_id');
            $table->string('url_variant');
            $table->string('conversion_type');
            $table->integer('button_click');
            $table->integer('form_submit');
            $table->integer('view');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_tabel');
    }
};
