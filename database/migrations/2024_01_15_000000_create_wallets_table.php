<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->decimal('available_amount', 10, 2)->default(0.00);
            $table->decimal('pending_amount', 10, 2)->default(0.00);
            $table->decimal('total_earned', 10, 2)->default(0.00);
            $table->decimal('total_withdrawn', 10, 2)->default(0.00);
            $table->timestamp('last_transfer_date')->nullable();
            $table->timestamps();
            
            $table->index('operator_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};