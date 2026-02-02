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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre'); // Ej: "Mi casa", "Oficina"
            $table->string('calle');
            $table->string('numero');
            $table->string('piso')->nullable();
            $table->string('puerta')->nullable();
            $table->string('ciudad');
            $table->string('provincia');
            $table->string('codigo_postal');
            $table->string('pais');
            $table->boolean('es_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
