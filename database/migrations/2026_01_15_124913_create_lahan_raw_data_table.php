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
        Schema::create('lahan_raw_data', function (Blueprint $table) {
            $table->id();
            $table->string('lahan_no')->nullable()->index();
            $table->string('source_data');
            $table->boolean('is_duplicate')->default(false);
            $table->json('raw_payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan_raw_data');
    }
};
