<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('safari_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('safari_id');
            $table->date('available_start_date')->nullable();
            $table->date('available_end_date')->nullable();
            $table->date('blocked_start_date')->nullable();
            $table->date('blocked_end_date')->nullable();
            $table->timestamps();
            
            $table->index('safari_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('safari_dates');
    }
};