<?php

namespace App\Http\Controllers\Api\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use App\Models\Setting;
use App\Models\TimeSlots;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        $totalCompletedBookings = Session::where('status','completed')->count();
        $totalUpcomingBookings  = Session::where('start_time', '>', Carbon::now())->where("status","!=","completed")->count();
        $upcomingBookings = Session::where('start_time', '>', Carbon::now())
        ->with('customer')
        ->where("status","!=","completed")
        ->orderBy('start_time', 'asc')
        ->get();
        $CompletedInterviews = Session::with('customer')
        ->where("status","completed")->latest()->get();
        $totalSubusers    = User::where("role", \Role::SubUser)->where("role", "!=", \Role::Developer)->count();
        

        $totalCustomers = User::where("role", \Role::Customer)
        ->whereHas('video')
        ->where("is_active", 1)
        ->count();

        $totalSubusers    = User::where("role", \Role::SubUser)
        ->where("is_active", 1)->count();
        $totalBookedSessions = Session::count();
        $admin = User::where("role", \Role::SuperAdmin)->first();
        $credit = $admin->transactions()->sum("credit");
        $debit = $admin->transactions()->sum("debit");
        $totalRevenue = $credit - $debit;

        if ($request->option == 'this month') {
            $total_users = User::where("role", \Role::Customer)
                ->whereDate('created_at', '>=', now()->startOfMonth())
                ->whereDate('created_at', '<=', now()->endOfMonth())
                ->whereHas('video')
                ->where("is_active",1)
                ->count();
            $total_amount = $admin->transactions()->whereDate('created_at', '>=', now()->startOfMonth())
                ->whereDate('created_at', '<=', now()->endOfMonth())->sum('credit')
             - $admin->transactions()->whereDate('created_at', '>=', now()->startOfMonth())
                ->whereDate('created_at', '<=', now()->endOfMonth())->sum('debit');
        } elseif ($request->option == 'previous month') {
            $total_users = User::where("role", \Role::Customer)
                ->whereDate('created_at', '>=', now()->subMonth()->startOfMonth())
                ->whereDate('created_at', '<=', now()->subMonth()->endOfMonth())
                ->whereHas('video')
                ->where("is_active",1)
                ->count();

            $total_amount = $admin->transactions()->whereDate('created_at', '>=', now()->subMonth()->startOfMonth())
                ->whereDate('created_at', '<=', now()->subMonth()->endOfMonth())
                ->sum('credit')
             - $admin->transactions()->whereDate('created_at', '>=', now()->subMonth()->startOfMonth())
                ->whereDate('created_at', '<=', now()->subMonth()->endOfMonth())
                ->sum('debit');
        } elseif ($request->option == 'this year') {
            $total_users = User::where("role", \Role::Customer)
                ->whereDate('created_at', '>=', now()->startOfYear())
                ->whereDate('created_at', '<=', now()->endOfYear())
                ->whereHas('video')
                ->where("is_active",1)
                ->count();

            $total_amount = $admin->transactions()->whereDate('created_at', '>=', now()->startOfYear())
                ->whereDate('created_at', '<=', now()->endOfYear())
                ->sum('credit')
             - $admin->transactions()->whereDate('created_at', '>=', now()->startOfYear())
                ->whereDate('created_at', '<=', now()->endOfYear())
                ->sum('debit');
        } else {
            $year  = date("Y", strtotime($request->date));
            $month = date("m", strtotime($request->date));

            $total_users = User::where("role", \Role::Customer)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereHas('video')
                ->where("is_active",1)
                ->count();

            $total_amount = $admin->transactions()->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('credit')
                - $admin->transactions()->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('debit');
        }

        return response()->json([
            "success" => true,
            "data" => [
                "auth_user" => auth()->user(),
                "totalCustomers" => $totalCustomers ?? 0,
                "totalSubusers"   => $totalSubusers ?? 0,
                "bookedSessions" => $totalBookedSessions ?? 0,
                "totalRevenue"   => $totalRevenue ?? 0,
                "revenue" => [
                    "total_users"  => $total_users ?? 0,
                    "total_amount" => $total_amount ?? 0,
                ],
                "upcomingBookings"       => $upcomingBookings ?? 0,
                "completedInterviews"    => $CompletedInterviews ?? 0,
                "totalUpcomingBookings"  => $totalUpcomingBookings ?? 0,
                "totalCompletedBookings" => $totalCompletedBookings ?? 0,
            ],
        ]);
    }
    public function alerts()
    {
        $alerts = array();
        if (!Setting::where("key", "amount-for-booking-session")->exists()) {
            $alerts[] = ["message" => "Amount for booking session must added in configuration section."];
        }

        if (!Setting::where("key", "zoom-access-authorized")->exists()) {
            $alerts[] = ["message" => "Zoom authentication is required."];
        }

        if (TimeSlots::count() == 0) {
            $alerts[] = ["message" => "Schedule your availability"];
        }

        return response()->json([
            "success" => true,
            "alerts" => $alerts,
        ]);
    }
    public function store(Request $request)
    {
        ini_set('post_max_size', '5120M');
        ini_set('upload_max_filesize', '5120M');


    }

}
