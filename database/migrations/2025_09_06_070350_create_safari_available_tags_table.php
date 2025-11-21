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
        Schema::create('safari_available_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->nullable()->constrained('available_tags')->onDelete('cascade');
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_available_tags');
    }
};
