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
        Schema::create('voos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->dateTime('data_hora_partida');
            $table->boolean('voos_status')->default(true);
            $table->timestamps();

            $table->foreignId('aeroporto_origem_id')->constrained('aeroportos');
            $table->foreignId('aeroporto_destino_id')->constrained('aeroportos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voos');
    }
};
