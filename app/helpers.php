<?php

use App\Models\Relation;
use App\Models\User;

class Role
{
    const Developer = '0';
    const SuperAdmin = '1';
    const Customer = '2';
    const SubUser = '3';
}

class RoleString
{
    const Developer = 'Developer';
    const SuperAdmin = 'Super Admin';
    const Customer = 'Customer';
    const SubUser = 'Sub User';
}


if (!function_exists('predefinedPassword')) {
    function predefinedPassword()
    {
        return 'Lifestoryproject_a1b2c3d4e5f6g7h8i9f10';
    }
}
use App\Models\Transaction;
use App\Notifications\CustNotification;
use Illuminate\Support\Facades\Notification;

function LS_Notification($to, $title, $route, $sender)
{

    $toUser = User::find($to);
    Notification::send($toUser, new CustNotification($title, $route, $sender));
    return true;

    /*$options = array(
'cluster' => 'ap2',
'encrypted' => true
);
$pusher = new Pusher(
Env::get('PUSHER_APP_KEY'),
Env::get('PUSHER_APP_SECRET'),
Env::get('PUSHER_APP_ID'), $options
);
$data=[
'from'=>auth()->user()->id,
'to'=>$to,
'msg'=>$title
];
$channel=Env::get('PUSHER_CHANNEL_NOTIFICATION');
$pusher->trigger($channel, 'App\Events\Notifications',$data);*/

}

if (!function_exists('getCreatePasswordToken')) {
    function getCreatePasswordToken()
    {
        return 'FNF000009';
    }
}

if (!function_exists('getZoomRedirectUri')) {
    function getZoomRedirectUri()
    {
        return url('/') . '/zoom/callback';
    }
}
if (!function_exists('getDataAttributeNotification')) {
    function getDataAttributeNotification($notification)
    {
        $notification = json_decode($notification, true);
        return $notification;
    }
}

if (!function_exists('getStripeSubscriptionSuccessUrl')) {
    function getStripeSubscriptionSuccessUrl()
    {
        return url('/') . '/subscription/successfull';
    }
}

if (!function_exists('getStripeSubscriptionCancelUrl')) {
    function getStripeSubscriptionCancelUrl()
    {
        return url('/') . '/subscription/failed';
    }
}

if (!function_exists('generateTransactionId')) {
    function generateTransactionId()
    {
        $lastInsertedId = Transaction::max('id') + 1;
        return "TRX" . str_pad($lastInsertedId, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('getDateFormat')) {
    function getDateFormat($date)
    {
        return date("m-d-Y",strtotime($date));
    }
}


function getSwitch($user){
    $switch=false;
    if ($user->role==\RoleString::Customer && count($user->customersOfSubUser())>0){
        $switch=true;
    }
    if ($user->role==\RoleString::SubUser){
        //sub users
        if (count($user->customersOfSubUser())==1){
            //has only one customer
            $switch=false;
        }
        if (count($user->customersOfSubUser())>1){
            //has more than one customers
            $switch=true;
        }
    }
    return $switch;
}
function getActiveCustomer($user){
    $customer=auth()->user()->role!=RoleString::SuperAdmin?auth()->user():$user;
    if ($user->role==\RoleString::Customer && count($user->customersOfSubUser())>0){
        $re=Relation::with('customer')->where('subuser_id',$user->id)->where('active',1)->first();
        if($re){
            $customer=$re['customer'];
        }
    }
    if ($user->role==\RoleString::SubUser){
        if (count($user->customersOfSubUser())==1){
            $customer=Relation::where('subuser_id',$user->id)->first()['customer'];
        }
        if (count($user->customersOfSubUser())>1){
            $customer=Relation::with('customer')->where('subuser_id',$user->id)->where('active',1)->first();
            if ($customer){
                $customer=$customer['customer'];
            }else{
                $makeActive=Relation::with('customer')->where('subuser_id',$user->id)->first();
                $makeActive->active=1;
                $makeActive->save();
                $customer=Relation::with('customer')->where('subuser_id',$user->id)->first()['customer'];
            }
        }
    }
    $customer['video'] = $customer->video;
    $customer['details'] = $customer->details;
    return $customer;
}
