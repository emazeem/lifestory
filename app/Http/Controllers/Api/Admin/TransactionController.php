<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Stripe\StripeClient;
use App\Models\Transaction;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    use CommonTraits;
    public function userTransactions($user_id)
    {
        $transactions = Transaction::where("user_id",$user_id)->latest()->get();
        return response()->json($transactions);
    }
    public function fetchAll(Request $request)
    {
        $admin = User::where("role", \Role::SuperAdmin)->first();
        $search  = $request->search;
        $transactions = $admin->transactions()
        ->when($search,function($query) use ($search){
            $query->where("transaction_id","like","%$search%")
            ->orWhere("credit","like","%$search%")
            ->orWhere("debit","like","%$search%")
            ->orWhere("created_at","like","%$search%")
            ->orWhereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('fname', 'like', "%$search%")
                ->orWhere('lname', 'like', "%$search%");
            });
        })
        ->with('user')
        ->get();
        return $this->sendSuccess("Transactions fetched successfully", $transactions);
    }
    public function fetchSubscriptions(){
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $data=[];
        foreach ($stripe->invoices->all([]) as $invoice) {
            //dd($invoice);
            $data[]=[
              'invoice_id'=>$invoice->id,
              'created'=>date('F d,y h:i A',$invoice->created),
              'start'=>date('F d,y h:i A',$invoice->lines->data[0]->period->start),
              'end'=>date('F d,y h:i A',$invoice->lines->data[0]->period->end),
              'customer_email'=>$invoice->customer_email,
              'customer_name'=>$invoice->customer_name,
              'customer_id'=>$invoice->customer,
              'amount'=>$invoice->lines->data[0]->amount,
              'description'=>$invoice->lines->data[0]->description,
            ];

        }
        return response()->json($data);
        //return response()->json($stripe->invoices->all([]));
    }

}
