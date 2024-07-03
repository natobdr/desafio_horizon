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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('passageiro_id');
            $table->unsignedBigInteger('voo_id');
            $table->decimal('preco_total', 8, 2);
            $table->boolean('voos_status')->default(true);
            $table->timestamps();

            $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('passageiro_id')->references('id')->on('passageiros')->onDelete('cascade');
            $table->foreign('voo_id')->references('id')->on('voos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passagems');
    }
};
