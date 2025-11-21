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
        Schema::create('operator_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->longText('review_text');
            $table->string('source');
            $table->boolean('status')->default(true);
            $table->integer('rating')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_reviews');
    }
};