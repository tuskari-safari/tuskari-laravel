<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PayoutToOperatorMail;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class WithdrawalRequestController extends Controller
{
    public function index()
    {
        $withdrawalRequests = WithdrawalRequest::with(['operator', 'operatorBank', 'processedBy'])
            ->with('wallet')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/withdrawal_requests/List', [
            'withdrawalRequests' => $withdrawalRequests
        ]);
    }

    public function updateStatus(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $withdrawalRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        $operator = $withdrawalRequest->operator;
        $data = [
            'operator' =>  $operator,
            'withdrawalRequest' => $withdrawalRequest
        ];


        Mail::to($operator->email)->queue(new PayoutToOperatorMail($data));
        // operator

        return redirect()->back()->with('success', 'Withdrawal request status updated successfully.');
    }
}
