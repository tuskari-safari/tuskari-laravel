<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function edit()
    {
        try {
            $setting = Setting::first();
            if (!$setting) {
                $setting = Setting::create([
                    'first_deposit_percentage' => 0.00,
                    'auto_balance_duration_days' => 0,
                    'platform_fee' => 10,
                    'stripe_processing_fee' => 0.00,
                ]);
            }
            return Inertia::render('Admin/settings/Edit', compact('setting'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_deposit_percentage' => 'required|numeric|min:0|max:100',
            'auto_balance_duration_days' => 'required|integer|min:0',
            'platform_fee' => 'required|numeric|min:0|max:100',
            'stripe_processing_fee' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $setting = Setting::first();
            if (!$setting) {
                $setting = new Setting();
            }

            $setting->fill($request->only([
                'first_deposit_percentage',
                'auto_balance_duration_days',
                'platform_fee',
                'stripe_processing_fee',
            ]));
            $setting->save();

            return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
