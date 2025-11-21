<?php

namespace App\Console\Commands;

use App\Mail\AutoPaymentMail;
use App\Models\Payment;
use App\Models\SafariBooking;
use App\Models\SafariBookingState;
use App\Models\Wallet;
use App\Notifications\SendNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class AutoPayCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:auto-charge';
    protected $description = 'Automatically charge pending deposits after 30 days';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug('Running Auto Payment Cron');
        Stripe::setApiKey(config('services.stripe.secret'));
        $dueBookings = SafariBooking::where('payment_type', 'deposit_auto_payment')
            ->where('is_full_paid', 0)
            ->where('status', 'ACTIVE')
            ->whereDate('next_deposit_date', now()->format('Y-m-d'))
            ->get();
        if (count($dueBookings) > 0) {
            foreach ($dueBookings as $booking) {
                try {
                    $paymentIntentData = [
                        'amount' => intval($booking->next_deposit_amount * 100),
                        'currency' => 'USD',
                        'payment_method' => $booking->payment_method_id,
                        'off_session' => true,
                        'confirm' => true,
                        'customer' => $booking->traveler->stripe_customer_id,
                        'description' => $booking?->safari?->title . ' - ' . $booking?->duration . ' Booking'
                    ];
                    $paymentIntent = PaymentIntent::create($paymentIntentData);
                    if ($paymentIntent->status === 'succeeded') {
                        $booking->is_full_paid = 1;
                        $booking->next_deposit_date = now()->format('Y-m-d');
                        $booking->payment_type = 'deposit_auto_payment';
                        $booking->save();

                        Payment::create([
                            'traveler_id' => $booking->traveler_id,
                            'booking_id' => $booking->id,
                            'payment_intent_id' => $paymentIntent->id,
                            'amount' => $booking->next_deposit_amount,
                            'payment_method' => $booking->payment_method_id,
                            'payment_status' => 'completed',
                            'payment_details' => json_encode($paymentIntent)
                        ]);

                        SafariBookingState::create([
                            'booking_id' => $booking->id,
                            'traveler_id' => $booking->traveler_id,
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
                        $latestChargeId = $paymentIntent['latest_charge'];
                        $receiptUrl = $latestChargeId ? Charge::retrieve($latestChargeId)->receipt_url : null;


                        $notifyDetails = [
                            "type" => 'Auto balance payment completed',
                            "title" => $booking->traveler->first_name . ' completed the balance payment for safari ' . $booking->safari->title,
                            "body"  => $booking->traveler->full_name . ' has paid the remaining balance for your safari.',
                            "safariId" => $booking->safari->id,
                            "sender" => $booking->traveler_id,
                        ];
                        $notify_users = $booking->operator;
                        Notification::send($notify_users, new SendNotification($notifyDetails));

                        // Traveler notification
                        $travelerNotify = $notifyDetails;
                        $travelerNotify['title'] = 'Your balance payment was successful for safari ' . $booking->safari->title;
                        $travelerNotify['body']  = 'You have successfully paid the remaining balance for your safari.';
                        Notification::send($booking->traveler, new SendNotification($travelerNotify));

                        $data = [
                            'receiptUrl' => $receiptUrl,
                            'operator' => $booking->operator,
                            'traveler' => $booking->traveler,
                            'booking' => $booking
                        ];
                        Mail::to($booking->traveler)->queue(new AutoPaymentMail($data));


                        Log::debug("Auto payment successful for booking ID {$booking->id}");
                    } else {
                        Log::error("Auto payment failed for booking ID {$booking->id} :: " . $paymentIntent->error->message);
                    }
                } catch (\Exception $e) {
                    Log::error("Auto payment failed for booking ID {$booking->id} :: " . $e->getMessage());
                }
            }
        } else {
            Log::debug("No due bookings found.");
        }
    }
}
