<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_consumos_basicos', function (Blueprint $table) 
        {
            $table->id(); // ID primario autoincremental
            $table->foreignId('dtes_id')->constrained('dtes')->unique()->onDelete('cascade');    
            $table->enum('tipo', ['ELECTRICIDAD', 'AGUA', 'TELEFONIA FIJA', 'TELEFONIA MOVIL', 'CORREOS']);
            $table->string('numerocliente'); // <-- primero se crea
            $table->foreign('numerocliente')->references('numerocliente')->on('clientesmedidores')->onDelete('cascade');
            $table->string('periodoconsumo', 20);
            $table->integer('lecturaanterior')->nullable();
            $table->integer('lecturaactual')->nullable();
            $table->integer('consumo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_consumos_basicos');
    }
};

