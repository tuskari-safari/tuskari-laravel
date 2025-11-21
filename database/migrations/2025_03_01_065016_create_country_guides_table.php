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
        Schema::create('country_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('middle_sec_title')->nullable();
            $table->text('middle_sec_subtitle')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('content_details')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('unique_experience_title')->nullable();
            $table->text('unique_experience_desc')->nullable();
            $table->json('unique_experience')->nullable();
            $table->json('bottom_banner')->nullable();
            $table->json('faqs')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_guides');
    }
};
