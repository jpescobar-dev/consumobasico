<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesmedidoresTable extends Migration
{
    public function up()
    {
        Schema::create('clientesmedidores', function (Blueprint $table) {
            $table->string('numerocliente', 20)->primary();
            $table->string('medidor', 20)->nullable();

            $table->string('rutproveedor', 15);
            $table->foreign('rutproveedor')
                ->references('rutproveedor')
                ->on('proveedores')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->string('ccosto', 15);
            $table->foreign('ccosto')
                ->references('ccosto')
                ->on('ccostos')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->string('tarifa', 50);
            $table->enum('tipo', ['Normal', 'Calefaccion']);
            $table->boolean('vigente')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientesmedidores');
    }
}
