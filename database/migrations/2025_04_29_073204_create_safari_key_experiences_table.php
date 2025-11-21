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
        Schema::create('safari_key_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->foreignId('key_experience_id')->nullable()->constrained('key_experiences')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_key_experiences');
    }
};
