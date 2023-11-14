<?php

namespace App\Console\Commands;

use Stripe\Stripe as StripeGateway;
use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Setting;
use App\Models\User;
use Stripe\Charge;

class ManageAccount extends Command
{
    protected $signature = 'app:manage-account';
    protected $description = 'Account restriction process.';

    public function handle()
    {
        /**
         * 1. Trial expired account should be blocked
         * 2. Subscription expiring today should auto-renew
         */
        StripeGateway::setApiKey(env('STRIPE_SECRET_KEY', null));

        $subscriptionsExpiringToday=Subscription::where('is_active',1)->whereDate('ended_at',date('Y-m-d'))->get();
        foreach ($subscriptionsExpiringToday as $sub)
        {

            $user   = $sub->user;
            $card   = BankAccount::where('user_id',$user->id)->where('preferred',1)->first();
            $amount = Setting::where("key", "amount-for-booking-subscription")->first()->value;

            $charge = Charge::create([
                'amount'   => $amount*100,
                'currency' => 'USD',
                'customer' => $user->stripe_customer_id,
            ]);

            if($charge->status == "succeeded")
            {
                foreach ($user->subscriptions as $s) {
                    $s->update(['is_active' => 0]);
                }
                $subscription = Subscription::create([
                    "user_id"    => $user->id,
                    "card_id"    => $card->id,
                    "charge_id"  => $charge['id'],
                    "is_active"  => 1,
                    "started_at" => now(),
                    "ended_at"   => date('Y-m-d H:i:s',strtotime('+1 month', time())),
                ]);

                $transaction_id = generateTransactionId();

                Transaction::create([
                    "reference_id"   => $subscription->id,
                    "transaction_id" => $transaction_id,
                    "user_id"        => $user->id,
                    "debit"          => $amount,
                    "description"    => $amount . "USD charged for the purpose of auto-renewal by ".$user->fullName(),
                ]);

                $admin = User::where("role", \Role::SuperAdmin)->first();

                Transaction::create([
                    "transaction_id" => $transaction_id,
                    "user_id"        => $admin->id,
                    "credit"         => $amount,
                    "description"    => $amount . "USD charged for the purpose of auto-renewal by ".$user->fullName(),
                ]);
            }
            else
            {
                $user = User::find($sub->user_id);
                $user->disabled='cancel-subscription - auto-renewal-failed-'.$charge->failure_message;
                $user->save();
            }
        }

        $users=User::select('id','disabled','created_at','role')->where('role',\Role::Customer)->whereNull('disabled')->where('is_active',1)->get();

        foreach ($users as $user)
        {
            $accountCreatedOn        = $user->created_at;
            $trialPeriodOfCustomer = Setting::where("key","trial-period-of-customer-in-days")->first()->value;
            $trialEndsOnInSeconds    = strtotime($accountCreatedOn." +".$trialPeriodOfCustomer." days");
            $trialEndsOn             = date('Y-m-d',$trialEndsOnInSeconds);
            $earlier                 = new \DateTime(date('Y-m-d'));
            $later                   = new \DateTime($trialEndsOn);
            $trialDaysLeft           = $earlier->diff($later)->format("%r%a");

            if ($trialDaysLeft==0 && Subscription::where('is_active',1)->where('user_id',$user->id)->count()==0)
            {
                $user->disabled='cancel-subscription - trial-expired';
                $user->save();
            }
        }
    }
}
