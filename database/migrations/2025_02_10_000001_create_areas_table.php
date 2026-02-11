<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('area_ha', 12, 4)->nullable()->comment('Luas dalam hektar');
            $table->json('boundary')->comment('GeoJSON polygon');
            $table->json('attributes')->nullable()->comment('Atribut tambahan (jenis, catatan, dll)');
            $table->timestamps();
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
