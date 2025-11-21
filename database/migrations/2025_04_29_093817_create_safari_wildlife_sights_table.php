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
        Schema::create('safari_wildlife_sights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
             $table->foreignId('animal_id')->nullable()->constrained('wild_lives')->onDelete('cascade');
            $table->string('probability')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_wildlife_sights');
    }
};
