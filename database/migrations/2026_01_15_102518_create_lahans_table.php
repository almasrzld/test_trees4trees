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
        Schema::create('lahans', function (Blueprint $table) {
            $table->id();
            $table->string('lahan_no')->nullable()->index();
            $table->string('document_no')->nullable();
            $table->string('coordinate')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('village')->nullable()->index();
            $table->string('kecamatan')->nullable()->index();
            $table->string('city')->nullable()->index();
            $table->string('province')->nullable()->index();
            $table->string('farmer_no')->nullable()->index();
            $table->string('land_area')->nullable();
            $table->string('planting_area')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('source_data');
            $table->boolean('is_duplicate')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahans');
    }
};
