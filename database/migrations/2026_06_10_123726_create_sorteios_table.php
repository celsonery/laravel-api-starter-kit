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
        Schema::create('sorteios', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->date('data');
            $table->integer('n1');
            $table->integer('n2');
            $table->integer('n3');
            $table->integer('n4');
            $table->integer('n5');
            $table->integer('n6');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorteios');
    }
};
