<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->string('lat_column')->nullable();
            $table->string('lng_column')->nullable();
            $table->string('address_column')->nullable();
            $table->json('attribute_columns')->nullable()->comment('Kolom atribut untuk popup');
            $table->timestamps();
        });

        Schema::table('field_mappings', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_mappings');
    }
};
