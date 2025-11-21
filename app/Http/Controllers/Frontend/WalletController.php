<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OperatorBank;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function transferMoney(Request $request)
    {
        $request->validate([
            'bank_id' => 'nullable|integer',
            'bank_name' => $request->bank_id ? '' : 'required|string|max:255|unique:operator_banks,bank_name',
            'account_holder_name' =>  $request->bank_id ? '' : 'required|string|max:255|unique:operator_banks,account_holder_name',
            'account_number' => $request->bank_id ? '' : 'required|string|max:50|unique:operator_banks,account_number',
            'ifsc_code' => $request->bank_id ? '' : 'required|string|max:20|unique:operator_banks,ifsc_code',
            'price' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();
        try {
            $operatorBank = OperatorBank::where('operator_id', Auth::id())->where('id', $request->bank_id)->first();
            if (!$operatorBank) {
                $operatorBank = OperatorBank::create([
                    'operator_id' => Auth::id(),
                    'bank_name' => $request->bank_name,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                ]);
            } else {
                $operatorBank->update([
                    'bank_name' => $request->bank_name,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                ]);
            }

            $wallet = Wallet::where('operator_id', Auth::id())->first();

            if ($request->price > $wallet->available_amount) {
                DB::rollBack();
                return back()->withErrors([
                    'price' => 'Insufficient balance, please try different amount',
                ]);
            } else {
                WithdrawalRequest::create([
                    'operator_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $request->price,
                    'requested_at' => now(),
                    'operator_bank_id' => $operatorBank->id,
                ]);
                $wallet->withdrawFunds($request->price);
                DB::commit();
                return redirect()->back()->with('success', 'Withdrawal request submitted successfully');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return redirect()->back()->with('error', 'Something is wrong, please try again.');
        }
    }
}
