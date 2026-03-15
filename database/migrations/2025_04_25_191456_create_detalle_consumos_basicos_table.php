<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    

    public function up(): void
    {
        Schema::create('detalle_consumos_basicos', function (Blueprint $table) 
        {
            $table->id(); // ID primario autoincremental
            $table->foreignId('dtes_id')->constrained('dtes')->onDelete('cascade');                
            $table->string('tipo')->Enum(['Electricidad', 'Agua', 'Telefonia Fija', 'Telefonia Movil'])->default('Electricidad');
            $table->string('numerocliente'); 
            $table->foreign('numerocliente')->references('numerocliente')->on('clientesmedidores')->onDelete('cascade');           
            $table->decimal('consumo', 10, 3)->default(0);
     
            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('detalle_consumos_basicos');
    }
};

