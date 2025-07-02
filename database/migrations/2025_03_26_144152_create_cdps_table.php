<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cdps', function (Blueprint $table) {
            $table->id();
            $table->string('num_cdp');
            $table->date('fecha_cdp');
            $table->unsignedBigInteger('id_proceso');       
            $table->unsignedBigInteger('cfinanciero_id')->default(14);
            $table->string('requerimiento')->nullable();
            $table->text('descripcion');            
            $table->unsignedBigInteger('ccosto');
            $table->enum('moneda', ['CLP', 'UF']);          
            $table->string('total_moneda_compra')->nullable(); // puede contener decimales y comas
            $table->decimal('paridad', 15, 4)->nullable();
            $table->decimal('monto_total_impto_incluido',20, 0)->nullable();         
            $table->enum('st', ['22', '29', '31']);              
            $table->unsignedBigInteger('catalogo_id');  
            $table->text('denominacion')->nullable();     
            $table->enum('tipo_gasto1', ['GO', 'INI']);           
            $table->unsignedBigInteger('proyecto_id');
            $table->integer('pp')->default(100);
            $table->enum('tipo_gasto2', ['TRANSITORIO', 'PERMANENTE']); 
            $table->boolean('cargado_cgu')->nullable();
            $table->boolean('comprometido_cgu');
            $table->decimal('total_compromiso');
            $table->unsignedBigInteger('num_compromiso')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('validez')->nullable();
          
            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cdps');
    }
};
