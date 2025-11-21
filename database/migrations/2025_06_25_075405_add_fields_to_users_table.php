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
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_logo')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->string('main_destination')->nullable();
            $table->string('no_of_employees')->nullable();
            $table->string('is_operate_directly')->nullable();
            $table->string('website_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->text('about_operation')->nullable();
            $table->string('registration_certificate')->nullable();
            $table->string('tourism_operating_license')->nullable();
            $table->string('insurance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
