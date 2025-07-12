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
        Schema::create('bitacora_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('accion'); // ver, crear, editar, borrar
            $table->string('permiso'); // nombre del permiso asignado
            $table->string('modelo')->nullable(); // App\Models\X
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_movimientos');
    }
};
