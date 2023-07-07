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
        Schema::create('tareas', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->id();
            $table->bigInteger('idProyecto')->unsigned();
            $table->string('titulo');
            $table->string('descripcion');
            $table->enum('estado', ['pendiente', 'en progreso', 'completada']);
            $table->foreign('idProyecto')->references('id')->on('proyectos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea');
    }
};
