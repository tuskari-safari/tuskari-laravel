<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('first_deposit_percentage', 5, 2)->default(0.00);
            $table->integer('auto_balance_duration_days')->default(0);
            $table->decimal('platform_fee', 5, 2)->default(0.00);
            $table->decimal('stripe_processing_fee', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};