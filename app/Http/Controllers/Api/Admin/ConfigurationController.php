<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Setting;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ZoomOauth;

class ConfigurationController extends Controller
{
    use CommonTraits;
    public function fetchAll()
    {

        $paymentForSession = Setting::where("key","amount-for-booking-session")->first();
        $paymentForSubscription = Setting::where("key","amount-for-booking-subscription")->first();
        $trialPeriodOfCustomer = Setting::where("key","trial-period-of-customer-in-days")->first();
        $daysBeforeMeetingToSendAlert = Setting::where("key","hours-before-meeting-to-send-alert")->first();
        if($daysBeforeMeetingToSendAlert) $daysBeforeMeetingToSendAlert = $daysBeforeMeetingToSendAlert->value;
        else $daysBeforeMeetingToSendAlert = null;
        $zoomAuthorize     = ZoomOauth::where("provider","zoom")->exists();
        if ($zoomAuthorize){
            $zoomRecord     = ZoomOauth::where("provider","zoom")->first();
            $zoomAuthorizedOn=$zoomRecord->updated_at;
            $expir_at = date("Y-m-d H:i:s",strtotime($zoomAuthorizedOn)+$zoomRecord->full_token['expires_in']);
            $zoomAuthorize=time()<strtotime($zoomAuthorizedOn)+$zoomRecord->full_token['expires_in'];
        }

        $zoom = [
            "email_acc"     => env('ZOOM_EMAIL_ACC'),
            "client_id"     => env('ZOOM_CLIENT_ID'),
            "client_secret" => env('ZOOM_CLIENT_SECRET'),
            "redirect_uri"  => getZoomRedirectUri(),
            "authorize"     => $zoomAuthorize,
            "expire_at"      => $expir_at??NULL,
        ];

        return response()->json([
            "success" => true,
            "paymentForSession" => $paymentForSession,
            "paymentForSubscription" => $paymentForSubscription,
            "trialPeriod" => $trialPeriodOfCustomer,
            "day_before_meeting_to_send_alert" => $daysBeforeMeetingToSendAlert,
            "zoom" => $zoom
        ]);
    }
    public function update(Request $request)
    {
        $setting = Setting::where("key",$request->key)->first();
        $value=$request->value;
        if ($request->key=='amount-for-booking-subscription' || $request->key=='amount-for-booking-session'){
            $value=number_format($value,2);
        }
        $setting->update(["value" => $value]);
        return $this->sendSuccess("Configuration updated successfully", true);
    }
}
