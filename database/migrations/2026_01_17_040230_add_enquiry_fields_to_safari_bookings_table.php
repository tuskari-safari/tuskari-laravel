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
        Schema::table('safari_bookings', function (Blueprint $table) {
            $table->boolean('is_enquiry')->default(false)->after('safari_id');
            $table->foreignId('chat_room_id')->nullable()->after('is_enquiry')->constrained('chat_rooms')->nullOnDelete();
            $table->enum('enquiry_status', ['pending', 'quoted', 'confirmed', 'declined'])->nullable()->after('chat_room_id');
            $table->decimal('quoted_total_price', 10, 2)->nullable()->after('enquiry_status');
            $table->timestamp('quoted_at')->nullable()->after('quoted_total_price');
            $table->text('traveler_notes')->nullable()->after('quoted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safari_bookings', function (Blueprint $table) {
            $table->dropForeign(['chat_room_id']);
            $table->dropColumn([
                'is_enquiry',
                'chat_room_id',
                'enquiry_status',
                'quoted_total_price',
                'quoted_at',
                'traveler_notes',
            ]);
        });
    }
};
