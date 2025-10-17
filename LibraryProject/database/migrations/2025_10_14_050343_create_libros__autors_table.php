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
        Schema::create('libros__autors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_libro');
            $table->unsignedBigInteger('id_autor');
            $table->foreign('id_libro')->references('id')->on('libros')->onDelete('cascade');
            $table->foreign('id_autor')->references('id')->on('autors')->onDelete('cascade');
            $table->unique(['id_libro', 'id_autor']);
            $table->timestamps();
        });
    }

    //$table->foreignId('id_autor')->constrained('autors')->onDelete('cascade');

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros__autors');
    }
};
