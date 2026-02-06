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
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variante_id')->nullable()->after('producto_type');
            $table->string('variante_detalle')->nullable()->after('variante_id'); // Para guardar talla/color
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropColumn(['variante_id', 'variante_detalle']);
        });
    }
};
