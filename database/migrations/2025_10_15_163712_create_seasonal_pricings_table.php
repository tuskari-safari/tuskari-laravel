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
        Schema::create('seasonal_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->enum('season', ['LOW', 'HIGH'])->default('LOW');
            $table->date('available_start_date')->nullable();
            $table->date('available_end_date')->nullable();
            $table->date('blocked_start_date')->nullable();
            $table->date('blocked_end_date')->nullable();
            $table->decimal('adult_price', 10, 2)->default(0);
            $table->decimal('child_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasonal_pricings');
    }
};
