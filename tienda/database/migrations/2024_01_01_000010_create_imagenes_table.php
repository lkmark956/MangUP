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
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('ruta'); // Ruta de la imagen en storage
            $table->boolean('es_principal')->default(false); // Si es la imagen principal
            $table->integer('orden')->default(0); // Orden en la galería
            
            // Campos polimórficos para relacionar con mangas, figuras o merchs
            $table->morphs('imageable'); // Crea imageable_id e imageable_type
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
