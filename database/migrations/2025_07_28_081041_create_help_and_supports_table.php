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
        Schema::create('help_and_supports', function (Blueprint $table) {
            $table->id();
            $table->enum('tag', ['quick-help', 'booking-trip-support', 'payments-pricing', 'trip-customisation', 'account-dashboard'])->default('quick-help')->nullable();
            $table->text('question')->nullable();
            $table->longText('answer')->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_and_supports');
    }
};
