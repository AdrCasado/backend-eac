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
        Schema::create('criterios_evaluacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resultado_aprendizaje_id')->constrained('resultados_aprendizaje')->cascadeOnDelete();
            $table->string('codigo', 5);
            $table->text('descripcion');
            // $table->decimal('peso_porcentaje')->unsigned()->nullable();
            // $table->integer('orden')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['resultado_aprendizaje_id', 'codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterios_evaluacion');
    }
};
