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
        Schema::create('national_park_and_reserves', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type',['national_park', 'private_reserve'])->nullable();
            $table->text('short_description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('middle_banner_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('title')->nullable();
            $table->text('title_2')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('subtitle_2')->nullable();
            $table->longText('description')->nullable();
            $table->string('location')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('country_guides')->nullOnDelete();
            $table->json('category')->nullable();
            $table->string('wildlife_highlights_title')->nullable();
            $table->text('wildlife_highlights_desc')->nullable();
            $table->json('best_visit_time')->nullable();
            $table->string('wild_lives_ids')->nullable();
            $table->string('accomodation_ids')->nullable();
            $table->json('traveler_tips')->nullable();
            $table->json('unique_experience')->nullable();
            $table->text('impact')->nullable();
            $table->string('impact_image')->nullable();
            $table->boolean('is_most_popular')->default(false);
            $table->boolean('is_hidden_gem')->default(false);
            $table->json('faqs')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_park_and_reserves');
    }
};
