<?php

namespace Database\Seeders;

use App\Models\Setting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Setting::truncate();
        Setting::create([
            'key' => 'amount-for-booking-session',
            'value' => '1',
        ]);
        Setting::create([
            'key' => 'amount-for-booking-subscription',
            'value' => '1',
        ]);
        Setting::create([
            'key' => 'trial-period-of-customer-in-days',
            'value' => '1',
        ]);
        Setting::create([
            'key' => 'hours-before-meeting-to-send-alert',
            'value' => '1',
        ]);



    }
}
