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
        Schema::create('jogos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('jogo');
            $table->integer('n1');
            $table->integer('n2');
            $table->integer('n3');
            $table->integer('n4');
            $table->integer('n5');
            $table->integer('n6');
            $table->integer('n7')->nullable();
            $table->integer('n8')->nullable();
            $table->integer('n9')->nullable();
            $table->integer('n10')->nullable();
            $table->integer('n11')->nullable();
            $table->integer('n12')->nullable();
            $table->integer('force')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jogos');
    }
};
