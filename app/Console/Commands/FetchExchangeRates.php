<?php

namespace App\Console\Commands;

use App\Models\CurrencyExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExchangeRates extends Command
{
    protected $signature = 'exchange:fetch';
    protected $description = 'Fetch and cache USD-based exchange rates daily';

    public function handle()
    {
        Log::Debug('Fetching exchange rates');
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
        
        if ($response->successful()) {
            CurrencyExchangeRate::truncate();
            CurrencyExchangeRate::create([
                'date' => $response->json()['date'],
                'rates' => json_encode($response->json()['rates']),
            ]);
            $this->info('Exchange rates updated successfully.');
        } else {
            $this->error('Failed to fetch exchange rates.');
        }
    }
}