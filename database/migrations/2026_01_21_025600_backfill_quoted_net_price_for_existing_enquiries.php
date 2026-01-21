<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Backfill quoted_net_price for existing enquiries that have quoted_total_price but no quoted_net_price.
     */
    public function up(): void
    {
        DB::table('safari_bookings')
            ->where('is_enquiry', true)
            ->whereNotNull('quoted_total_price')
            ->whereNull('quoted_net_price')
            ->update([
                'quoted_net_price' => DB::raw('ROUND(quoted_total_price * 0.87, 2)'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('safari_bookings')
            ->where('is_enquiry', true)
            ->whereNotNull('quoted_net_price')
            ->update([
                'quoted_net_price' => null,
            ]);
    }
};
