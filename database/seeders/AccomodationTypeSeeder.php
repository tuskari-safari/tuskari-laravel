<?php

namespace Database\Seeders;

use App\Models\AccomodationType;
use Illuminate\Database\Seeder;

class AccomodationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Lodge',
            'Tented Camp',
            'Luxury Camp',
            'Bush Camp',
            'Mobile Camp',
            'Tree House',
        ];

        foreach ($types as $type) {
            AccomodationType::firstOrCreate(
                ['name' => $type],
                [
                    'name' => $type,
                    'status' => true,
                ]
            );
        }
    }
}
