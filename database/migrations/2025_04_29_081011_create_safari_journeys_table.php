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
        Schema::create('safari_journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->string('day_number')->nullable();
            $table->string('heading')->nullable();
            $table->longText('subtext')->nullable();
            $table->text('accommodation')->nullable();
            $table->longText('meal')->nullable();
            $table->text('included')->nullable();
            $table->unsignedBigInteger('wildlife_location')->nullable();
            $table->text('wildlife_highlights')->nullable();
            $table->longText('today_highlights')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('no_accommodation_included')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_journeys');
    }
};
