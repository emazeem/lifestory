<?php

namespace Database\Seeders;

use App\Models\TimeSlots;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $startDate = strtotime('next Sunday');
        // for ($i = 0; $i < 7; $i++) {
        //     $day = date('D', strtotime("+$i days", $startDate));
        //     TimeSlots::create(['day'=>$day]);
        // }

        $startDate = strtotime('next Sunday');
        $fullDayNames = [
            'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
        ];
    
        for ($i = 0; $i < 7; $i++) {
            $day = $fullDayNames[date('w', strtotime("+$i days", $startDate))];
            TimeSlots::create(['day' => $day]);
        }
    }
}
