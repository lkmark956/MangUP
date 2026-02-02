<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Añadimos el campo is_admin para identificar administradores.
     * - boolean: Solo puede ser true o false
     * - default(false): Por defecto los usuarios NO son admin
     * - after('password'): Se coloca después del campo password
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Si hacemos rollback, eliminamos el campo is_admin
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
