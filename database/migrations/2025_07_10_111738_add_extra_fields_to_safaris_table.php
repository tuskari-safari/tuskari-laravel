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
            $table->foreignId('region_id')->nullable()->constrained('regions')->before('country_id')->onDelete('cascade');
            $table->foreignId('national_park_id')->nullable()->constrained('national_park_and_reserves')->after('country_id')->onDelete('cascade');
            $table->string('imapact_image')->after('impact')->nullable();
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
