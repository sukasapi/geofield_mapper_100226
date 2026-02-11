<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imported_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_mapping_id')->constrained()->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });

        Schema::table('imported_records', function (Blueprint $table) {
            $table->index(['field_mapping_id', 'lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imported_records');
    }
};
