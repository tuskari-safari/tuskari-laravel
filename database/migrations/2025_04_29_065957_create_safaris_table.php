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
        Schema::create('safaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price_for_adult', 10, 2)->default(0);
            $table->decimal('price_for_child', 10, 2)->default(0);
            $table->decimal('price_for_pet', 10, 2)->default(0);
            $table->foreignId('country_id')->nullable()->constrained('country_guides')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('travel_season')->nullable();
            $table->foreignId('accomodation_id')->nullable()->constrained('accomodation_to_stays')->onDelete('cascade');
            $table->text('best_visit_time')->nullable();
            $table->text('health_and_safety')->nullable();
            $table->text('travel_access_info')->nullable();
            $table->enum('pace_activity_level', ['Relaxed', 'Moderate', 'Active'])->nullable();
            $table->text('impact')->nullable();
            $table->text('vehicle_types')->nullable();
            $table->foreignId('safari_type_id')->nullable()->constrained('safari_types')->onDelete('cascade');
            $table->string('thumbnail')->nullable();
            $table->string('banner_image')->nullable();
            $table->enum('destination', ['Countries', 'Regions', 'Parks/Reserves'])->nullable();
            $table->longText('environment')->nullable();
            $table->enum('activity_level', ['Relaxed', 'Moderate', 'Active'])->nullable();
            $table->integer('no_of_adult')->default(0);
            $table->integer('no_of_child')->default(0);
            $table->enum('availability_tag', ['Last-minute available','Limited spaces'])->nullable();
            $table->longText('today_highlights')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_draft')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safaris');
    }
};
