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
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 8, 2);
            $table->integer('stock')->default(0);
            $table->boolean('disponibilidad')->default(true);
            $table->string('autor')->nullable();
            $table->string('editorial')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->integer('numero_paginas')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('numero_tomo')->nullable();
            $table->foreignId('categoria_manga_id')->constrained('categorias_manga')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
    }
};
