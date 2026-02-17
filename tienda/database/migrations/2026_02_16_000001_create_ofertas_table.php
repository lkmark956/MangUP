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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_descuento', ['porcentaje'])->default('porcentaje');
            $table->decimal('valor_descuento', 10, 2); // Máximo 85%
            $table->enum('aplica_a', ['todos', 'manga', 'figura', 'merch', 'producto_especifico'])->default('todos');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->string('tipo_producto')->nullable(); // manga, figura, merch
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);
            $table->timestamps();
            
            $table->index(['fecha_inicio', 'fecha_fin']);
            $table->index('activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
