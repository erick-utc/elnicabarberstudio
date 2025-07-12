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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('barbero_id');
            $table->unsignedBigInteger('paquete_id');
            $table->string('dia');
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('barbero_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
