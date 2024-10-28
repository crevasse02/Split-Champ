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
            $table->uuid('variant_id')->primary();
            $table->uuid('eksperimen_id');
            $table->string('url_variant');
            $table->string('variant_name');
            $table->string('conversion_type');
            $table->integer('button_click')->nullable(true);
            $table->string('button_click_name')->nullable(true);
            $table->integer('form_submit')->nullable(true);
            $table->string('submit_form_name')->nullable(true);
            $table->integer('view')->nullable(true);
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
