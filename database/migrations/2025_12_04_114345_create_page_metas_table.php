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
        Schema::create('page_metas', function (Blueprint $table) {
            $table->id();
            $table->enum('page_name', [
                'Homepage',
                'Destination',
                'Blog Listing',
                'Blog Details',
                'Story',
                'Safari',
                'Safari Details',
                'Contact Us',
                'Country Guide',
                'National Park',
                'Private Reserves',
                'Safari Style',
                'Operator',
                'how-it-works',
                'why-it-different',
                'responsible-travel',
                'faq'
            ])->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_type')->nullable();
            $table->string('og_image')->nullable();
            $table->integer('og_image_width')->nullable();
            $table->integer('og_image_height')->nullable();
            $table->string('og_url')->nullable();
            $table->string('og_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('index_follow')->default(false);
            $table->text('schema_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_metas');
    }
};
