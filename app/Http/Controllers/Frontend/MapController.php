<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function getLocations()
    {
        return response()->json([
            [
                'lat' => -24.7828986,
                'lng' => 26.1870993,
                'name' => 'South Africa',
                'description' => 'This is a beautiful safari location.',
                'contact' => '+27 123 4567'
            ],
        ]);
    }
}
