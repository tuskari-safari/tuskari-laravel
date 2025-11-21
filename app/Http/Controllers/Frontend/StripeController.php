<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Stripe\{Stripe, Account, AccountLink};

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function stripeConnect(Request $request)
    {
        try {
            $user = $request->userId ? User::find($request->userId) : Auth::user();
            if (!$user) {
                return $this->sendError('User not found');
            }
            if (!$user->stripe_account_no) {
                $account = Account::create([
                    'type' => 'express',
                    'country' => 'US',
                    'email' => $user->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);
                $user->stripe_account_no = $account->id;
                $user->save();
            }

            if ($user->is_completed_stripe_account_verification == true) {
                return response()->json([
                    'status' => true,
                    'message' => 'Stripe account already created',
                    'data' => [
                        'url' => $user->stripe_account_link,
                    ],
                ], 200, [], JSON_UNESCAPED_SLASHES);
            }

            $accountLink = AccountLink::create([
                'account' => $user->stripe_account_no,
                'refresh_url' => url('/stripe/refresh') . '?' . http_build_query(['userId' => $user->id]),
                'return_url' => url('/stripe/return') . '?' . http_build_query(['userId' => $user->id]),
                'type' => 'account_onboarding',
            ]);
            if ($request->userId) {
                return redirect($accountLink->url);
            }
            return response()->json([
                'status' => true,
                'message' => 'Stripe account created successfully',
                'data' => [
                    'url' => $accountLink->url,
                ],
            ], 200, [], JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $th) {
            Log::error('Stripe account creation error: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => $th->getMessage(),
            ]);
        }
    }

    public function returnStripeAccount(Request $request)
    {
        try {
            $user = User::find($request->userId);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }
            $stripeAccount = Account::retrieve($user->stripe_account_no);

            if (!$stripeAccount || !$stripeAccount->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stripe account not found',
                ], 404);
            }

            $isSetupCompleted = $stripeAccount->capabilities->card_payments === 'active' &&
                $stripeAccount->capabilities->transfers === 'active';

            $loginLink = Account::createLoginLink($stripeAccount->id);
            $user->is_completed_stripe_account_verification = $isSetupCompleted;
            $user->stripe_account_link = $loginLink->url;
            $user->save();

            $loginLinkUrl = $loginLink->url ?? null;
            $redirectUrl = route('frontend.operator-my-profile') . '?loginUrl=' . $loginLinkUrl;

            return redirect($redirectUrl);
        } catch (\Throwable $th) {
            Log::error(":: return Stripe Account :: ", [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => $th->getMessage(),
            ]);
        }
    }
}
