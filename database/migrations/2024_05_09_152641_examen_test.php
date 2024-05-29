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
        /* Schema::create('party', function (Blueprint $table) {
         *     $table->id('id');
         *     $table->integer('id_mundo_deportivo');
         *     $table->string('type', 255);
         *     $table->date('date');
         *     $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
         * });
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
