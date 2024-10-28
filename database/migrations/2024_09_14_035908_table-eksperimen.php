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
        Schema::create('eksperimen', function (Blueprint $table) {
            $table->uuid('eksperimen_id')->primary();
            $table->string('eksperimen_name');
            $table->string('domain_name');
            $table->string('created_by');
            $table->softDeletes(); // Adds the deleted_at column for soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksperimen');
    }
};
