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
        Schema::create('cms_management', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('slug')->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('features')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('text_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_management');
    }
};
