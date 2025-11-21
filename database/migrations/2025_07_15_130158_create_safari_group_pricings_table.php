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
        Schema::create('safari_group_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
            $table->enum('person_type', ['ADULT', 'CHILD'])->nullable();
            $table->integer('count')->default(0);
            $table->enum('season', ['high_season', 'low_season', 'shoulder_season','all_year'])->nullable();
            $table->decimal('net_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_group_pricings');
    }
};
