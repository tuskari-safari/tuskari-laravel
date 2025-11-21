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
        Schema::create('safari_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->nullable();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->foreignId('traveler_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('operator_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->string('duration')->nullable();
            $table->integer('no_of_adults')->nullable();
            $table->integer('no_of_children')->nullable();
            $table->decimal('adult_price', 10, 2)->default(0);
            $table->decimal('children_price', 10, 2)->default(0);
            $table->enum('status', ['ACTIVE', 'COMPLETED', 'PENDING', 'CANCELLED'])->default('PENDING');
            $table->date('completion_date')->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('stripe_fee', 10, 2)->default(0);
            $table->decimal('additional_fee', 10, 2)->default(0);
            $table->decimal('pay_to_operator', 10, 2)->default(0);
            $table->decimal('admin_commission', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('next_deposit_amount', 10, 2)->default(0);
            $table->date('next_deposit_date')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->boolean('is_full_paid')->default(false);
            $table->text('cancel_reason')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->date('cancelled_at')->nullable();
            $table->enum('payment_type', ['pay_in_full', 'deposit_auto_payment', 'deposit_manual_payment'])->default('pay_in_full');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_bookings');
    }
};
