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
        Schema::create('apartment_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('apartment_id'); // Chiave esterna verso la tabella apartments
            $table->unsignedBigInteger('service_id');   // Chiave esterna verso la tabella services
            $table->timestamps();

            // Definire le chiavi esterne
            $table->foreign('apartment_id')->references('id')->on('apartments')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_service');
    }
};
