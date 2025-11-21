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
        Schema::create('safari_booking_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('safari_bookings')->onDelete('cascade');
            $table->foreignId('traveler_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('state', ['paid_in_full','deposit_paid','withdrawal_requested', 'withdrawal_approved','cancelled', 'refunded'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_booking_states');
    }
};
