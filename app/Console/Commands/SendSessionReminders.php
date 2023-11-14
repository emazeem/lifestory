<?php

namespace App\Console\Commands;

use App\Jobs\SendSessionReminderEmailJob;
use Illuminate\Console\Command;
use App\Models\Session;
use App\Models\Setting;
use Carbon\Carbon;

class SendSessionReminders extends Command
{
    protected $signature = 'app:send-session-reminders';
    protected $description = 'Send session reminders before 24 hours';

    public function handle()
    {
        $setting = Setting::whereIn("key", ["hours-before-meeting-to-send-alert", "amount-for-booking-session"])->get()->keyBy('key');

        $hoursSetting = $setting->get("hours-before-meeting-to-send-alert");
        $costSetting = $setting->get("amount-for-booking-session");

        if (!$hoursSetting || !$costSetting) {
            return; // Handle missing settings gracefully
        }

        $hours = intval($hoursSetting->value);
        $now = now();

        $sessions = Session::with('customer')->where("reminder", 0)->where('start_time', '<=', $now->copy()->addHours($hours))
            ->where('start_time', '>=', $now)->get();

        if ($sessions->isEmpty()) {
            return; // No sessions to process
        }

        foreach ($sessions as $session) {
            $startDateTime = Carbon::parse($session->start_time);
            $remainingHours = $now->diffInHours($startDateTime);
            $firstLineDate = $startDateTime->format('l, F j, Y \a\t g:i A');
            $linkDate = $startDateTime->format('h:i A \P\S\T \o\n m-d-Y');

            $data = [
                "cost" => "$" . $costSetting->value,
                "email" => $session->customer->email,
                "start_time" => $session->start_time,
                "join_url" => $session->join_url,
                "firstLineDate" => $firstLineDate,
                "linkDate" => $linkDate,
                "hours" => $remainingHours,
                "hoursText" => $remainingHours > 1 ? "hours" : "hour",
            ];

            dispatch(new SendSessionReminderEmailJob($data));

        }

        // Bulk update the sessions
        Session::whereIn('id', $sessions->pluck('id'))->update(['reminder' => 1]);
        $this->info('Reminders are send successfully.');

    }
}
