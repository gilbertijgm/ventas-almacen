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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto', 8,3)->unsigned();
            $table->string('numero_comprobante', 255);
            $table->decimal('total', 8,3)->unsigned();
            $table->tinyInteger('estado')->default(1);
            $table->unsignedBigInteger('comprobante_id');
            $table->unsignedBigInteger('proveedore_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
