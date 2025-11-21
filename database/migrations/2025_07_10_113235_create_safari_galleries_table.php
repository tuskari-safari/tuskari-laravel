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
        Schema::create('safari_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->string('files')->nullable();
            $table->string('type')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_galleries');
    }
};
