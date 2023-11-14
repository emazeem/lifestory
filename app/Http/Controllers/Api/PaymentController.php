<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Session;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe as StripeGateway;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    use CommonTraits;

    public function checkCustomer(Request $request)
    {
        try {

            $id = $request->id;
            $session = Session::where("id", $id)->where('uuid', $request->uuid)->first();

            if ($session) {
                return response()->json([
                    "status" => true,
                    "session" => $session,
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => "Customer does not exist",
            ]);
        }
    }
    public function initiatePayment(Request $request)
    {
        $oneTimePayment = Setting::where("key", "amount-for-booking-session")->first();

        $amount = $oneTimePayment->value;
        if ($request->hosting == true) {
            $hostingPayment = Setting::where("key", "amount-for-booking-subscription")->first();
            $amount += $hostingPayment->value;
        }
        $currency = 'USD';

        return response()->json([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }

    public function redirectUrls()
    {
        $setting = Setting::where("key", "stripe-subscription-key")->first();
        $parts = explode("@@@@@", $setting->value);
        $stripe = [
            "success_url" => getStripeSubscriptionSuccessUrl(),
            "cancel_url" => getStripeSubscriptionCancelUrl(),
            "price_id" => $parts[1],
            "auth_email" => auth()->user()->email,
        ];

        return response()->json([
            "success" => true,
            "stripe" => $stripe,
        ]);
    }

    public function storeToken(Request $request)
    {

        $session = Session::where("meeting_id", $request->meeting_id)->first();
        $user = $session->customer;
        StripeGateway::setApiKey(env('STRIPE_SECRET_KEY', null));
        if (!$user->stripe_customer_id) {
            $customer = Customer::create(['email' => $user->email]);
            $user->stripe_customer_id = $customer['id'];
            $user->save();
        }
        $token = $request->id;
        //$token = 'tok_visa';
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $card = $stripe->customers->createSource(
            $user->stripe_customer_id,
            ['source' => $token]
        );

        $bank = new BankAccount();
        $bank->user_id = $user->id;
        $bank->stripe_card_id = $card['id'];
        $bank->card_number = $card['last4'];
        $bank->card_type = $card['brand'];
        $bank->expiry = $card['exp_month'] . '/' . $card['exp_year'];
        if (count(BankAccount::where('user_id', $user->id)->get()) == 0) {
            $bank->preferred = 1;
        }

        $bank->save();

        $admin = User::where("role", \Role::SuperAdmin)->first();

        if ($request->type == 'video' || $request->type == 'video-and-hosting') {
            //video payment
            $videoAmount = Setting::where("key", "amount-for-booking-session")->first();
            $videoAmount = $videoAmount->value;
            $charge = Charge::create([
                'amount' => $videoAmount * 100,
                'customer' => $user->stripe_customer_id,
                'currency' => $request->currency,
            ]);
            if ($charge->status == "succeeded") {
                $transaction_id = generateTransactionId();
                Transaction::create([
                    "transaction_id" => $transaction_id,
                    "user_id" => $user->id,
                    "debit" => $videoAmount,
                    "description" => $videoAmount . $request->currency . " charged for booking a session",
                ]);

                Transaction::create([
                    "transaction_id" => $transaction_id,
                    "user_id" => $admin->id,
                    "credit" => $videoAmount,
                    "description" => $videoAmount . $request->currency . " received for booking session",
                ]);

                $session->update(["payment" => 1]);
            }
        }

        if ($request->type == 'video-and-hosting') {
            if (count($user->subscriptions) == 0) {
                $subscriptionAmount = Setting::where("key", "amount-for-booking-subscription")->first();
                $subscriptionAmount = $subscriptionAmount->value;
                $description = $subscriptionAmount . "USD charged for subscription by " . $user->fullName();
                $charge = Charge::create([
                    'amount' => $subscriptionAmount * 100,
                    'currency' => 'USD',
                    'customer' => $user->stripe_customer_id,
                ]);
                if ($charge->status == "succeeded") {
                    foreach ($user->subscriptions as $subscription) {
                        $subscription->update(['is_active' => 0]);
                    }
                    $subscription = Subscription::create([
                        "user_id" => $user->id,
                        "card_id" => $bank->id,
                        "charge_id" => $charge['id'],
                        "is_active" => 1,
                        "started_at" => now(),
                        "ended_at" => date('Y-m-d H:i:s', strtotime('+1 month', time())),
                    ]);

                    $transaction_id = generateTransactionId();

                    Transaction::create([
                        "reference_id" => $subscription->id,
                        "transaction_id" => $transaction_id,
                        "user_id" => $user->id,
                        "debit" => $subscriptionAmount,
                        "description" => $description,
                    ]);

                    Transaction::create([
                        "transaction_id" => $transaction_id,
                        "user_id" => $admin->id,
                        "credit" => $subscriptionAmount,
                        "description" => $description,
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Payment successful']);
    }

    public function checkPayment(Request $request)
    {
        $session = Session::where("meeting_id", $request->meeting_id)->first();
        if ($session) {
            return response()->json(['success' => true, 'session' => $session]);
        } else {
            return response()->json(['success' => false, 'message' => 'Session does not exist.']);
        }

    }

}
