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
        Schema::table('safaris', function (Blueprint $table) {
            $table->string('day_duration')->nullable();
            $table->string('night_duration')->nullable();
            $table->text('safari_included')->nullable();
            $table->text('safari_not_included')->nullable();
            $table->text('add_ons')->nullable();
            $table->integer('number_of_groups')->default(0);
            $table->enum('group_type', ['Private', 'Group', 'Both'])->nullable();
            $table->date('low_season_start_date')->nullable();
            $table->date('low_season_end_date')->nullable();
            $table->date('high_season_start_date')->nullable();
            $table->date('high_season_end_date')->nullable();
            $table->decimal('low_season_adult_price', 10, 2)->default(0);
            $table->decimal('high_season_adult_price', 10, 2)->default(0);
            $table->decimal('low_season_child_price', 10, 2)->default(0);
            $table->decimal('high_season_child_price', 10, 2)->default(0);
            $table->date('available_start_date')->nullable();
            $table->date('available_end_date')->nullable();
            $table->date('blocked_start_date')->nullable();
            $table->date('blocked_end_date')->nullable();
            $table->string('per_date_group_limit')->nullable();
            $table->string('total_slots')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safaris', function (Blueprint $table) {
            //
        });
    }
};
