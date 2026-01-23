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
        Schema::create('merch_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merch_id')->constrained('merchs')->onDelete('cascade');
            $table->foreignId('talla_id')->nullable()->constrained('tallas')->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained('colores')->onDelete('set null');
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            // Evitar duplicados de la misma combinación
            $table->unique(['merch_id', 'talla_id', 'color_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merch_variantes');
    }
};
