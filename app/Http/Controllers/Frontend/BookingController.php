<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\CancelBookingMail;
use App\Mail\ManualPaymentToOperatorMail;
use App\Mail\RefundInitiatedMail;
use App\Mail\SafariBookingConfirmation;
use App\Mail\TravelerBookingConfirmation;
use App\Models\ChatRoom;
use App\Models\Member;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\SafariBookingState;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Refund;
use Stripe\SetupIntent;
use Stripe\Stripe;

class BookingController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function safariBooking(Request $request)
    {
        session()->forget('safari_booking');
        $validatedData = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable',
            'duration' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'total_adult_price' => 'required|numeric|min:1',
            'total_child_price' => 'nullable',
            'safari_id' => 'required|exists:safaris,id',
            'operator_adult_price' => 'required|numeric|min:1',
            'operator_child_price' => 'nullable',
            'hasDiscountAdultPrice' => 'required|boolean',
            'hasDiscountChildPrice' => 'required|boolean',
        ]);
        session()->put('safari_booking', $validatedData);
        $redirectUrl = route('frontend.checkout');
        if (! Auth::check()) {
            $redirectUrl = route('frontend.login');
        }

        return redirect($redirectUrl);
    }

    public function checkPriceSpecialDiscount(Request $request)
    {
        $request->validate([
            'safari_id' => 'required|exists:safaris,id',
            'noOfAdults' => 'required|integer|min:1',
            'noOfChildren' => 'nullable|integer|min:0',
            'season' => 'required|string',
        ]);

        try {
            $safari = Safari::with('group_pricing')->find($request->safari_id);

            if (! $safari) {
                return response()->json([
                    'price' => (float) $request->adultPrice,
                    'has_group_pricing' => false,
                    'error' => 'Safari not found',
                ]);
            }

            $numberOfAdults = (int) $request->noOfAdults;
            $numberOfChildren = (int) $request->noOfChildren;
            $season = strtolower($request->season);

            $adultGroup = $safari->group_pricing()
                ->where('person_type', 'ADULT')
                ->where('count', $numberOfAdults)
                ->where(function ($q) use ($season) {
                    $q->where('season', strtolower($season).'_season')
                        ->orWhere('season', 'all_year');
                })
                ->orderByDesc('count')
                ->first();

            $childGroup = $safari->group_pricing()
                ->where('person_type', 'CHILD')
                ->where(function ($q) use ($season) {
                    $q->where('season', strtolower($season).'_season')
                        ->orWhere('season', 'all_year');
                })
                ->where('count', $numberOfChildren)
                ->orderByDesc('count')
                ->first();

            $finalAdultPrice = $adultGroup ? (float) $adultGroup->net_price : 0;
            $finalChildPrice = $childGroup ? (float) $childGroup->net_price : 0;

            $response = [
                'net_adult_price' => $finalAdultPrice,
                'net_child_price' => $finalChildPrice,
                'has_adult_discount' => $adultGroup ? true : false,
                'has_child_discount' => $childGroup ? true : false,
            ];

            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'price' => (float) $request->adultPrice,
                'has_group_pricing' => false,
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function safariPayment(Request $request)
    {
        $validatedData = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable|integer|min:0',
            'duration' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'total_adult_price' => 'required|numeric|min:0',
            'total_child_price' => 'nullable',
            'safari_id' => 'required|exists:safaris,id',
            'card_id' => 'required',
            'total_payable' => 'required',
            'payment_type' => 'required',
            'operator_adult_price' => 'required|numeric|min:1',
            'operator_child_price' => 'nullable|numeric',
            'hasDiscountAdultPrice' => 'required|boolean',
            'hasDiscountChildPrice' => 'required|boolean',
            'is_enquiry_booking' => 'nullable|boolean',
            'enquiry_id' => 'nullable|exists:safari_bookings,id',
        ]);
        $safari = Safari::where('id', $validatedData['safari_id'])->first();
        $isEnquiryBooking = $request->is_enquiry_booking ?? false;
        $enquiryId = $request->enquiry_id ?? null;

        /** Check if user already has an active booking (skip for enquiry bookings) */
        if (! $isEnquiryBooking) {
            $alreadyBooked = SafariBooking::where('traveler_id', Auth::id())
                ->where('status', 'ACTIVE')
                ->where('safari_id', $validatedData['safari_id'])
                ->first();
            if ($alreadyBooked) {
                return response()->json(['error' => 'You already have an active booking for this safari.'], 422);
            }
        }

        DB::beginTransaction();
        try {
            /** Create payment intent */
            if ($request->payment_type === 'deposit') {
                $depositPercentage = Setting::first()->first_deposit_percentage ?? 0;
                $depositAmount = round(($validatedData['total_payable'] * $depositPercentage) / 100);
                $validatedData['deposit_amount'] = $depositAmount;
                $validatedData['next_deposit_amount'] = $validatedData['total_payable'] - $depositAmount;
                $validatedData['next_deposit_date'] = \Carbon\Carbon::parse($validatedData['check_in_date'])->subDays(30)->format('Y-m-d');
            }
            $paymentIntentData = [
                'amount' => $request->payment_type === 'deposit' ? intval($validatedData['deposit_amount'] * 100) : intval($validatedData['total_payable'] * 100),
                'currency' => 'USD',
                'payment_method' => $request->card_id,
                'description' => $safari->title.' - '.$request->duration.' Booking'.($isEnquiryBooking ? ' (Enquiry)' : ''),
            ];

            if (Auth::user()->stripe_customer_id) {
                $paymentIntentData['customer'] = Auth::user()->stripe_customer_id;
            }
            $paymentIntent = PaymentIntent::create($paymentIntentData);

            /** Calculate additional cost */
            if ($isEnquiryBooking) {
                $setting = Setting::first();
                $platformFeePercentage = $setting->platform_fee ?? 13;
                $platformFee = round(($validatedData['total_payable'] * $platformFeePercentage) / 100, 2);
                $payToOperator = $validatedData['total_payable'] - $platformFee;
                $additionalCost = 0;
            } else {
                $totalAdultPrice = $validatedData['operator_adult_price'] * $validatedData['number_of_adults'];
                $totalChildPrice = $validatedData['operator_child_price'] * $validatedData['number_of_children'];
                $additionalCost = (float) number_format($validatedData['total_adult_price'] - $totalAdultPrice, 2, '.', '');
                $payToOperator = (float) number_format($totalAdultPrice + $totalChildPrice, 2, '.', '');
            }

            /** For enquiry bookings, update existing booking; otherwise create new */
            if ($isEnquiryBooking && $enquiryId) {
                $booking = SafariBooking::findOrFail($enquiryId);
                $booking->update([
                    'adult_price' => $validatedData['total_adult_price'],
                    'children_price' => $validatedData['total_child_price'],
                    'payment_status' => 'pending',
                    'total_price' => $validatedData['total_payable'],
                    'additional_fee' => $additionalCost,
                    'pay_to_operator' => $payToOperator,
                    'deposit_amount' => $validatedData['deposit_amount'] ?? 0,
                    'next_deposit_amount' => $validatedData['next_deposit_amount'] ?? 0,
                    'next_deposit_date' => $validatedData['next_deposit_date'] ?? null,
                    'payment_type' => $request->payment_type === 'deposit' ? 'deposit_auto_payment' : 'pay_in_full',
                    'payment_method_id' => $validatedData['card_id'],
                    'is_full_paid' => $request->payment_type === 'deposit' ? 0 : 1,
                    'booking_id' => 'TSK-'.$booking->id,
                ]);
            } else {
                $booking = SafariBooking::create([
                    'traveler_id' => Auth::id(),
                    'operator_id' => Safari::find($validatedData['safari_id'])->user_id,
                    'safari_id' => $validatedData['safari_id'],
                    'adult_price' => $validatedData['total_adult_price'],
                    'children_price' => $validatedData['total_child_price'],
                    'check_in_date' => $validatedData['check_in_date'],
                    'check_out_date' => $validatedData['check_out_date'],
                    'no_of_adults' => $validatedData['number_of_adults'],
                    'no_of_children' => $validatedData['number_of_children'] ?? 0,
                    'duration' => $validatedData['duration'],
                    'payment_status' => 'pending',
                    'total_price' => $validatedData['total_payable'],
                    'additional_fee' => $additionalCost,
                    'pay_to_operator' => $payToOperator,
                    'deposit_amount' => $validatedData['deposit_amount'] ?? 0,
                    'next_deposit_amount' => $validatedData['next_deposit_amount'] ?? 0,
                    'next_deposit_date' => $validatedData['next_deposit_date'] ?? null,
                    'payment_type' => $request->payment_type === 'deposit' ? 'deposit_auto_payment' : 'pay_in_full',
                    'payment_method_id' => $validatedData['card_id'],
                    'is_full_paid' => $request->payment_type === 'deposit' ? 0 : 1,
                ]);

                $booking->update(['booking_id' => 'TSK-'.$booking->id]);
            }

            // Save Payment
            Payment::create([
                'traveler_id' => Auth::id(),
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $request->payment_type === 'deposit' ? $validatedData['deposit_amount'] : $validatedData['total_payable'],
                'payment_status' => 'pending',
                'payment_method' => $validatedData['card_id'],
            ]);

            if ($booking->payment_type === 'pay_in_full') {
                SafariBookingState::create([
                    'booking_id' => $booking->id,
                    'traveler_id' => Auth::id(),
                    'state' => 'paid_in_full',
                ]);
            }
            if ($booking->payment_type === 'deposit_auto_payment') {
                SafariBookingState::create([
                    'booking_id' => $booking->id,
                    'traveler_id' => Auth::id(),
                    'state' => 'deposit_paid',
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error(' :: EXCEPTION :: '.$e->getMessage()."\n".$e->getTraceAsString());
            DB::rollBack();

            return response()->json(['error' => 'Failed to create booking or payment'], 500);
        }

        return response()->json(['message' => 'success', 'client_secret' => $paymentIntent->client_secret]);
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
        $payment = Payment::where('payment_intent_id', $request->payment_intent_id)->first();
        /** Get receipt url */
        $latestChargeId = $paymentIntent['latest_charge'];
        $receiptUrl = null;
        if ($latestChargeId) {
            $charge = Charge::retrieve($latestChargeId);
            $receiptUrl = $charge->receipt_url;
        }

        if ($payment && $paymentIntent->status == 'succeeded') {
            $payment->payment_status = 'completed';
            $payment->payment_details = json_encode($paymentIntent);
            $payment->save();

            $booking = $payment->safariBooking;
            if ($booking) {
                $booking->payment_status = 'confirmed';
                $booking->status = 'ACTIVE';
                $booking->save();

                /** Add funds to wallet as booking is confirmed */
                if ($booking->is_full_paid) {
                    $wallet = Wallet::firstOrCreate(['operator_id' => $booking->operator_id], [
                        'available_amount' => 0,
                        'pending_amount' => 0,
                        'total_earned' => 0,
                        'total_withdrawn' => 0,
                    ]);
                    $wallet->addFunds($booking->pay_to_operator, $booking->id, 'Safari booking payment');
                }
            }

            if ($booking->payment_type === 'pay_in_full') {
                $receiver = User::find($booking->operator_id);
                $notifyDetails['type'] = 'Booking Safari & Paid full';
                $notifyDetails['title'] = Auth::user()->first_name.' Booked your Safari '.$booking->safari->title;
                $notifyDetails['body'] = Auth::user()->full_name.' booked your safari and payment has been made in full.';
                $notifyDetails['safariId'] = $booking->safari->id;
                $notifyDetails['sender'] = Auth::id();
            } else {
                $receiver = User::find($booking->operator_id);
                $notifyDetails['type'] = 'Booking Safari & Paid Deposit';
                $notifyDetails['title'] = Auth::user()->first_name.' Booked your Safari '.$booking->safari->title;
                $notifyDetails['body'] = Auth::user()->full_name.' booked your safari and payment has been made in deposit.';
                $notifyDetails['safariId'] = $booking->safari->id;
                $notifyDetails['sender'] = Auth::id();
            }

            $notify_users = $receiver;
            Notification::send($notify_users, new SendNotification($notifyDetails));
            $data = [
                'receiptUrl' => $receiptUrl,
                'operator' => $booking->operator,
                'traveler' => $booking->traveler,
                'booking' => $booking,
            ];

            $operator = $booking->safari->user;

            Mail::to($operator->email)->queue(new SafariBookingConfirmation($data));
            Mail::to(Auth::user()->email)->queue(new TravelerBookingConfirmation($data));

            session()->forget('safari_booking');

            return response()->json(['message' => 'Payment status updated']);
        }

        return response()->json(['error' => 'Payment not found'], 404);
    }

    public function checkout()
    {
        $bookingData = session('safari_booking');
        if (! is_array($bookingData) || empty($bookingData)) {
            return redirect()->route('frontend.index');
        }

        $safari = Safari::find($bookingData['safari_id']);
        $setting = Setting::first();

        return Inertia::render('Frontend/Auth/check-out', [
            'bookingData' => $bookingData,
            'safari' => $safari,
            'setting' => $setting,
        ]);
    }

    public function getPaymentMethods(Request $request)
    {
        $user = $request->user();

        if (! $user->stripe_customer_id) {
            return response()->json(['error' => 'Stripe customer not found'], 404);
        }
        $paymentMethods = PaymentMethod::all([
            'customer' => $user->stripe_customer_id,
            'type' => 'card',
        ]);

        // Get customer to fetch default payment method
        $customer = Customer::retrieve($user->stripe_customer_id);

        return response()->json([
            'cards' => $paymentMethods->data,
            'default_payment_method' => $customer->invoice_settings->default_payment_method ?? null,
        ]);
    }

    public function createSetupIntent(Request $request)
    {
        $user = $request->user();

        if (! $user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
            ]);
            $user->stripe_customer_id = $customer->id;
            $user->save();
        }

        // Create new setup intent
        $setupIntent = SetupIntent::create([
            'customer' => $user->stripe_customer_id,
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $setupIntent->client_secret,
        ]);
    }

    public function storePaymentMethod(Request $request)
    {
        $user = $request->user();
        $paymentMethodId = $request->payment_method;

        $newPaymentMethod = PaymentMethod::retrieve($paymentMethodId);

        $newPaymentMethod->attach([
            'customer' => $user->stripe_customer_id,
        ]);

        Customer::update($user->stripe_customer_id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ],
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteCard(Request $request, $card_id)
    {
        $user = $request->user();

        $paymentMethod = PaymentMethod::retrieve($card_id);

        if ($paymentMethod->customer !== $user->stripe_customer_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentMethod->detach();

        return response()->json(['success' => true]);
    }

    public function travellerBookingDetails(Request $request)
    {
        $booking = SafariBooking::where('traveler_id', Auth::id())
            ->with([
                'safari' => function ($query) {
                    $query->withAvg('safariReviews', 'rating');
                },
                'safari.activity',
                'payment',
                'safari.safariReviews' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
                'operator',
            ])->find($request->safariBookingId);

        return response()->json($booking);
    }

    public function manualPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:safari_bookings,id',
        ]);

        DB::beginTransaction();

        try {
            $booking = SafariBooking::find($request->booking_id);

            if (! $booking) {
                return response()->json(['error' => 'Booking not found'], 404);
            }

            if ($booking->is_full_paid != 0 || $booking->payment_type !== 'deposit_auto_payment') {
                Log::warning("Invalid booking state for booking {$booking->id}", [
                    'is_full_paid' => $booking->is_full_paid,
                    'payment_type' => $booking->payment_type,
                ]);

                return response()->json(['error' => 'Invalid booking state'], 400);
            }

            $paymentMethod = PaymentMethod::retrieve($booking->payment_method_id);
            if ($paymentMethod->customer !== Auth::user()->stripe_customer_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $paymentIntentData = [
                'amount' => intval($booking->next_deposit_amount * 100),
                'currency' => 'USD',
                'payment_method' => $booking->payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'description' => $booking?->safari?->title.' - '.$booking?->duration.' Booking',
            ];

            if (Auth::user()->stripe_customer_id) {
                $paymentIntentData['customer'] = Auth::user()->stripe_customer_id;
            }

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            if ($paymentIntent->status !== 'succeeded') {
                DB::rollBack();

                return response()->json([
                    'error' => 'Payment not completed',
                    'status' => $paymentIntent->status,
                ], 400);
            }

            $booking->payment_type = 'deposit_manual_payment';
            $booking->is_full_paid = 1;
            $booking->next_deposit_date = today()->format('Y-m-d');
            $booking->save();

            Payment::create([
                'traveler_id' => Auth::id(),
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $booking->next_deposit_amount,
                'payment_method' => $booking->payment_method_id,
                'payment_status' => 'completed',
                'payment_details' => json_encode($paymentIntent),
            ]);

            SafariBookingState::create([
                'booking_id' => $booking->id,
                'traveler_id' => Auth::id(),
                'state' => 'paid_in_full',
            ]);

            $wallet = Wallet::firstOrCreate(['operator_id' => $booking->operator_id], [
                'available_amount' => 0,
                'pending_amount' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
            ]);

            if ($booking->is_full_paid) {
                $wallet->addFunds($booking->pay_to_operator, $booking->id, 'Safari booking payment');
            }

            $receiptUrl = null;
            if ($paymentIntent['latest_charge']) {
                $charge = Charge::retrieve($paymentIntent['latest_charge']);
                $receiptUrl = $charge->receipt_url;
            }

            $receiver = User::find($booking->operator_id);

            $notifyDetails['type'] = 'Manual balance payment completed';
            $notifyDetails['title'] = Auth::user()->first_name.' completed the balance payment for safari '.$booking->safari->title;
            $notifyDetails['body'] = Auth::user()->full_name.' has paid the remaining balance for your safari.';
            $notifyDetails['safariId'] = $booking->safari->id;
            $notifyDetails['sender'] = Auth::id();

            $notify_users = $receiver;
            Notification::send($notify_users, new SendNotification($notifyDetails));

            $data = [
                'receiptUrl' => $receiptUrl,
                'operator' => $booking->operator,
                'traveler' => $booking->traveler,
                'booking' => $booking,
            ];

            $operator = $booking->safari->user;
            Mail::to($operator->email)->queue(new ManualPaymentToOperatorMail($data));

            DB::commit();

            return response()->json(['message' => 'Payment successful'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ManualPayment Exception: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Failed to create booking or payment'], 500);
        }
    }

    public function cancelBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:safari_bookings,id',
            'cancel_reason' => 'required|string',
        ]);

        $booking = SafariBooking::find($request->booking_id);

        if (! $booking) {
            return back()->with('error', 'Booking not found');
        }

        DB::beginTransaction();
        try {
            $refundAmount = 0;

            if ($booking->is_full_paid && $booking->payment_type === 'pay_in_full') {
                // Full refund of one payment intent
                $payment = $booking->payment()->first();
                $stripePrice = json_decode($payment->payment_details, true)['amount'];

                $refund = Refund::create([
                    'payment_intent' => $payment->payment_intent_id,
                    'amount' => $stripePrice,
                    'reason' => 'requested_by_customer',
                ]);

                $refundAmount = $payment->amount;

                $payment->update([
                    'payment_status' => 'cancelled',
                    'refund_details' => json_encode($refund),
                ]);
            } elseif ($booking->is_full_paid && in_array($booking->payment_type, ['deposit_auto_payment', 'deposit_manual_payment'])) {
                foreach ($booking->payment as $payment) {
                    $stripePrice = json_decode($payment->payment_details, true)['amount'];

                    $refund = Refund::create([
                        'payment_intent' => $payment->payment_intent_id,
                        'amount' => $stripePrice,
                        'reason' => 'requested_by_customer',
                    ]);

                    $refundAmount += $payment->amount;

                    $payment->update([
                        'payment_status' => 'cancelled',
                        'refund_details' => json_encode($refund),
                    ]);
                }
            } else {
                // Partial / other payment
                $payment = $booking->payment()->first();
                $stripePrice = json_decode($payment->payment_details, true)['amount'];

                $refund = Refund::create([
                    'payment_intent' => $payment->payment_intent_id,
                    'amount' => $stripePrice,
                    'reason' => 'requested_by_customer',
                ]);

                $refundAmount = $payment->amount;
                $payment->update([
                    'payment_status' => 'cancelled',
                    'refund_details' => json_encode($refund),
                ]);
            }
            $deductedAmount = $booking->pay_to_operator;
            if ($booking->is_full_paid === 1) {
                $wallet = Wallet::where('operator_id', $booking->operator_id)->first();
                $wallet->deductFunds($deductedAmount, $booking->id);
            }

            // Mark booking as cancelled
            $booking->update([
                'status' => 'CANCELLED',
                'cancel_reason' => $request->cancel_reason,
                'cancelled_at' => now(),
                'refund_amount' => $refundAmount,
                'payment_status' => 'cancelled',
            ]);

            SafariBookingState::create([
                'booking_id' => $booking->id,
                'traveler_id' => Auth::id(),
                'state' => 'cancelled',
            ]);

            $receiver = User::find($booking->operator_id);

            $notifyDetails['type'] = 'Booking canceled';
            $notifyDetails['title'] = Auth::user()->first_name.' Cancelled the Booking for safari '.$booking->safari->title;
            $notifyDetails['body'] = Auth::user()->full_name.' has canceled their booking for your safari.';
            $notifyDetails['safariId'] = $booking->safari->id;
            $notifyDetails['sender'] = Auth::id();

            $notify_users = $receiver;
            Notification::send($notify_users, new SendNotification($notifyDetails));

            $data = [
                'operator' => $booking->operator,
                'traveler' => $booking->traveler,
                'booking' => $booking,
            ];

            $operator = $booking->safari->user;
            Mail::to($operator->email)->queue(new CancelBookingMail($data));

            Mail::to($booking->traveler->email)->queue(new RefundInitiatedMail($data));
            DB::commit();

            return redirect()
                ->route('frontend.my-trips')
                ->with('success', 'Booking cancelled successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(' :: EXCEPTION :: '.$th->getMessage()."\n".$th->getTraceAsString());

            return back()->with('error', 'Failed to cancel booking');
        }
    }

    public function createEnquiry(Request $request)
    {
        $validatedData = $request->validate([
            'safari_id' => 'required|exists:safaris,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable|integer|min:0',
            'duration' => 'required|string',
            'traveler_notes' => 'nullable|string|max:2000',
        ]);

        $safari = Safari::findOrFail($validatedData['safari_id']);

        if (! $safari->isEnquiryMode()) {
            return response()->json(['error' => 'This safari does not accept enquiries'], 422);
        }

        $operatorId = $safari->user_id;

        DB::beginTransaction();
        try {
            $chatRoom = $this->findOrCreateChatRoom(Auth::id(), $operatorId);

            $enquiry = SafariBooking::create([
                'traveler_id' => Auth::id(),
                'operator_id' => $operatorId,
                'safari_id' => $validatedData['safari_id'],
                'check_in_date' => $validatedData['check_in_date'],
                'check_out_date' => $validatedData['check_out_date'],
                'no_of_adults' => $validatedData['number_of_adults'],
                'no_of_children' => $validatedData['number_of_children'] ?? 0,
                'duration' => $validatedData['duration'],
                'is_enquiry' => true,
                'enquiry_status' => 'pending',
                'chat_room_id' => $chatRoom->id,
                'traveler_notes' => $validatedData['traveler_notes'],
                'payment_status' => 'pending',
            ]);

            $enquiry->update(['booking_id' => 'ENQ-'.$enquiry->id]);

            $checkIn = Carbon::parse($validatedData['check_in_date'])->format('M d, Y');
            $checkOut = Carbon::parse($validatedData['check_out_date'])->format('M d, Y');
            $adults = $validatedData['number_of_adults'];
            $children = $validatedData['number_of_children'] ?? 0;

            $messageContent = "🌍 New Safari Enquiry\n\n";
            $messageContent .= "Safari: {$safari->title}\n";
            $messageContent .= "Dates: {$checkIn} - {$checkOut}\n";
            $messageContent .= "Guests: {$adults} adult(s)";
            if ($children > 0) {
                $messageContent .= ", {$children} child(ren)";
            }
            $messageContent .= "\n";
            if (! empty($validatedData['traveler_notes'])) {
                $messageContent .= "\nMessage:\n{$validatedData['traveler_notes']}";
            }

            Message::create([
                'chat_room_id' => $chatRoom->id,
                'user_id' => Auth::id(),
                'message' => $messageContent,
            ]);

            $receiver = User::find($operatorId);
            $notifyDetails = [
                'type' => 'New Enquiry',
                'title' => Auth::user()->first_name.' sent an enquiry for '.$safari->title,
                'body' => Auth::user()->full_name.' is interested in your safari and wants to plan the trip with you.',
                'safariId' => $safari->id,
                'chat_room_id' => $chatRoom->id,
                'sender' => Auth::id(),
            ];
            Notification::send($receiver, new SendNotification($notifyDetails));

            DB::commit();

            return response()->json([
                'message' => 'Enquiry submitted successfully',
                'chat_room_id' => $chatRoom->id,
                'redirect_url' => route('frontend.messages'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' :: ENQUIRY EXCEPTION :: '.$e->getMessage()."\n".$e->getTraceAsString());

            return response()->json(['error' => 'Failed to submit enquiry'], 500);
        }
    }

    public function quoteEnquiry(Request $request)
    {
        $validatedData = $request->validate([
            'enquiry_id' => 'required|exists:safari_bookings,id',
            'quoted_net_price' => 'required|numeric|min:1',
        ]);

        $enquiry = SafariBooking::with(['safari', 'traveler', 'chatRoom'])
            ->where('id', $validatedData['enquiry_id'])
            ->where('is_enquiry', true)
            ->firstOrFail();

        if ($enquiry->operator_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (! in_array($enquiry->enquiry_status, ['pending', 'quoted'])) {
            return response()->json(['error' => 'Cannot quote this enquiry'], 422);
        }

        DB::beginTransaction();
        try {
            $setting = Setting::first();
            $platformFeePercentage = $setting->platform_fee ?? 13;
            $netPrice = $validatedData['quoted_net_price'];
            $travelerTotal = round($netPrice * (1 + ($platformFeePercentage / 100)), 2);

            $enquiry->update([
                'quoted_net_price' => $netPrice,
                'quoted_total_price' => $travelerTotal,
                'enquiry_status' => 'quoted',
                'quoted_at' => now(),
            ]);

            $checkIn = Carbon::parse($enquiry->check_in_date)->format('M d, Y');
            $checkOut = Carbon::parse($enquiry->check_out_date)->format('M d, Y');

            $messageContent = "💰 Quote Provided\n\n";
            $messageContent .= "Safari: {$enquiry->safari->title}\n";
            $messageContent .= "Dates: {$checkIn} - {$checkOut}\n";
            $messageContent .= "Guests: {$enquiry->no_of_adults} adult(s)";
            if ($enquiry->no_of_children > 0) {
                $messageContent .= ", {$enquiry->no_of_children} child(ren)";
            }
            $messageContent .= "\n\nTotal: $".number_format($travelerTotal, 2);
            $messageContent .= "\n(Includes conservation and local community contribution)";

            Message::create([
                'chat_room_id' => $enquiry->chat_room_id,
                'user_id' => Auth::id(),
                'message' => $messageContent,
            ]);

            $notifyDetails = [
                'type' => 'Quote Received',
                'title' => 'You received a quote for '.$enquiry->safari->title,
                'body' => 'The operator has quoted a total of $'.number_format($travelerTotal, 2).' for your enquiry.',
                'safariId' => $enquiry->safari_id,
                'chat_room_id' => $enquiry->chat_room_id,
                'sender' => Auth::id(),
            ];
            Notification::send($enquiry->traveler, new SendNotification($notifyDetails));

            DB::commit();

            return response()->json([
                'message' => 'Quote submitted successfully',
                'enquiry' => $enquiry->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' :: QUOTE EXCEPTION :: '.$e->getMessage()."\n".$e->getTraceAsString());

            return response()->json(['error' => 'Failed to submit quote'], 500);
        }
    }

    public function confirmEnquiry(Request $request)
    {
        $validatedData = $request->validate([
            'enquiry_id' => 'required|exists:safari_bookings,id',
        ]);

        $enquiry = SafariBooking::with(['safari', 'operator'])
            ->where('id', $validatedData['enquiry_id'])
            ->where('is_enquiry', true)
            ->firstOrFail();

        if ($enquiry->traveler_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($enquiry->enquiry_status !== 'quoted') {
            return response()->json(['error' => 'This enquiry has not been quoted yet'], 422);
        }

        DB::beginTransaction();
        try {
            $enquiry->update([
                'enquiry_status' => 'confirmed',
                'total_price' => $enquiry->quoted_total_price,
            ]);

            $checkIn = Carbon::parse($enquiry->check_in_date)->format('M d, Y');
            $checkOut = Carbon::parse($enquiry->check_out_date)->format('M d, Y');

            $messageContent = "✅ Enquiry Confirmed\n\n";
            $messageContent .= "{$enquiry->traveler->first_name} has accepted your quote for:\n";
            $messageContent .= "Safari: {$enquiry->safari->title}\n";
            $messageContent .= "Dates: {$checkIn} - {$checkOut}\n";
            $messageContent .= 'Total: $'.number_format($enquiry->quoted_total_price, 2);

            Message::create([
                'chat_room_id' => $enquiry->chat_room_id,
                'user_id' => Auth::id(),
                'message' => $messageContent,
            ]);

            // Use stored net price, with fallback for legacy quotes
            $payToOperator = $enquiry->quoted_net_price;
            if (! $payToOperator) {
                $setting = Setting::first();
                $platformFeePercentage = $setting->platform_fee ?? 13;
                $payToOperator = round($enquiry->quoted_total_price / (1 + ($platformFeePercentage / 100)), 2);
            }

            $bookingData = [
                'safari_id' => $enquiry->safari_id,
                'check_in_date' => $enquiry->check_in_date,
                'check_out_date' => $enquiry->check_out_date,
                'number_of_adults' => $enquiry->no_of_adults,
                'number_of_children' => $enquiry->no_of_children,
                'duration' => $enquiry->duration,
                'total_price' => $enquiry->quoted_total_price,
                'total_adult_price' => $enquiry->quoted_total_price,
                'total_child_price' => 0,
                'operator_adult_price' => $payToOperator,
                'operator_child_price' => 0,
                'hasDiscountAdultPrice' => false,
                'hasDiscountChildPrice' => false,
                'is_enquiry_booking' => true,
                'enquiry_id' => $enquiry->id,
            ];

            session()->put('safari_booking', $bookingData);

            $notifyDetails = [
                'type' => 'Enquiry Confirmed',
                'title' => Auth::user()->first_name.' confirmed your quote for '.$enquiry->safari->title,
                'body' => Auth::user()->full_name.' has accepted your quote and is proceeding to payment.',
                'safariId' => $enquiry->safari_id,
                'chat_room_id' => $enquiry->chat_room_id,
                'sender' => Auth::id(),
            ];
            Notification::send($enquiry->operator, new SendNotification($notifyDetails));

            DB::commit();

            return response()->json([
                'message' => 'Enquiry confirmed',
                'redirect_url' => route('frontend.checkout'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' :: CONFIRM EXCEPTION :: '.$e->getMessage()."\n".$e->getTraceAsString());

            return response()->json(['error' => 'Failed to confirm enquiry'], 500);
        }
    }

    public function declineEnquiry(Request $request)
    {
        $validatedData = $request->validate([
            'enquiry_id' => 'required|exists:safari_bookings,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $enquiry = SafariBooking::with(['safari', 'traveler', 'operator'])
            ->where('id', $validatedData['enquiry_id'])
            ->where('is_enquiry', true)
            ->firstOrFail();

        if ($enquiry->traveler_id !== Auth::id() && $enquiry->operator_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($enquiry->enquiry_status, ['confirmed', 'declined'])) {
            return response()->json(['error' => 'Cannot decline this enquiry'], 422);
        }

        DB::beginTransaction();
        try {
            $enquiry->update([
                'enquiry_status' => 'declined',
                'status' => 'CANCELLED',
                'cancel_reason' => $validatedData['reason'] ?? null,
            ]);

            $declinedBy = Auth::user();
            $isOperator = $enquiry->operator_id === Auth::id();

            $messageContent = "❌ Enquiry Declined\n\n";
            $messageContent .= "The enquiry for {$enquiry->safari->title} has been declined by ";
            $messageContent .= $isOperator ? 'the operator' : 'the traveler';
            if (! empty($validatedData['reason'])) {
                $messageContent .= ".\n\nReason: {$validatedData['reason']}";
            }

            Message::create([
                'chat_room_id' => $enquiry->chat_room_id,
                'user_id' => Auth::id(),
                'message' => $messageContent,
            ]);

            $notifyUser = $isOperator ? $enquiry->traveler : $enquiry->operator;
            $notifyDetails = [
                'type' => 'Enquiry Declined',
                'title' => 'Enquiry for '.$enquiry->safari->title.' has been declined',
                'body' => $declinedBy->full_name.' has declined the enquiry.',
                'safariId' => $enquiry->safari_id,
                'chat_room_id' => $enquiry->chat_room_id,
                'sender' => Auth::id(),
            ];
            Notification::send($notifyUser, new SendNotification($notifyDetails));

            DB::commit();

            return response()->json([
                'message' => 'Enquiry declined',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(' :: DECLINE EXCEPTION :: '.$e->getMessage()."\n".$e->getTraceAsString());

            return response()->json(['error' => 'Failed to decline enquiry'], 500);
        }
    }

    public function getEnquiry(Request $request)
    {
        $request->validate([
            'chat_room_id' => 'required|exists:chat_rooms,id',
        ]);

        $enquiry = SafariBooking::with(['safari', 'traveler', 'operator'])
            ->where('chat_room_id', $request->chat_room_id)
            ->where('is_enquiry', true)
            ->whereIn('enquiry_status', ['pending', 'quoted'])
            ->latest()
            ->first();

        if (! $enquiry) {
            return response()->json(['enquiry' => null]);
        }

        if ($enquiry->traveler_id !== Auth::id() && $enquiry->operator_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'enquiry' => $enquiry,
            'is_operator' => $enquiry->operator_id === Auth::id(),
        ]);
    }

    private function findOrCreateChatRoom(int $userId, int $operatorId): ChatRoom
    {
        $existingRoom = ChatRoom::where('is_group', 0)
            ->whereHas('chatMembers', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereHas('chatMembers', function ($q) use ($operatorId) {
                $q->where('user_id', $operatorId);
            })
            ->first();

        if ($existingRoom) {
            return $existingRoom;
        }

        $chatRoom = new ChatRoom;
        $chatRoom->is_group = 0;
        $chatRoom->save();

        ChatRoom::where('id', $chatRoom->id)->update(['group_id' => 'grp-'.$chatRoom->id]);

        Member::insert([
            ['chat_room_id' => $chatRoom->id, 'user_id' => $userId],
            ['chat_room_id' => $chatRoom->id, 'user_id' => $operatorId],
        ]);

        return $chatRoom;
    }
}
