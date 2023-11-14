<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Models\TimeSlots;
use App\Models\Session;
use Carbon\Carbon;
use DateTime;

class TimeSlotController extends Controller
{
    use CommonTraits;
    public function fetchAll()
    {

        $timeSlots = TimeSlots::whereNotNull("parent_id")->get();

        $events = [];

        foreach ($timeSlots as $timeSlot) {
            $startDate = Carbon::parse($timeSlot->slot_date)->format('Y-m-d');
            $startDateTime = $startDate . ' ' . $timeSlot->from;

            $endDate = Carbon::parse($timeSlot->slot_date)->format('Y-m-d');
            $endDateTime = $endDate . ' ' . $timeSlot->to;

            $description = "";
            $date = "";
            if ($timeSlot->booked) {
                $session = Session::find($timeSlot->booked);
                if($session) {
                    $description = "Booked by " . $session->customer ? $session->customer->fullName() : '';
                    $date = getDateFormat($session->start_time);
                }
            }

            $timeSlot->month = date('F', strtotime($timeSlot->slot_date));
            $timeSlot->slot_date = date('Y-m-d', strtotime($timeSlot->slot_date));

            $events[] = [
                'id' => $timeSlot->id,
                'title' => 'Time Slot',
                'start' => $startDateTime,
                'class' => $timeSlot->booked ? 'booked' : '',
                'end' => $endDateTime,
                'session_id' => $timeSlot->booked,
                'para' => $description,
                'date' => $date,
                'slot' => $timeSlot,
            ];
        }

        return response()->json([
            "success" => true,
            "events" => $events,
        ]);
    }
    public function fetch()
    {
        $days = TimeSlots::with('slots')->whereNull('parent_id')->select('id', 'day')->get();
        return response()->json($days);
    }
    public function edit(Request $request)
    {
        $slot = TimeSlots::find($request->id);
        $slot->month = date('F', strtotime($slot->slot_date));

        return response()->json([
            "status" => true,
            "slot" => $slot,
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validators = Validator($request->all(), [
    //         "date" => 'required|date|after_or_equal:today',
    //         "start_time" => "required|before:end_time|different:end_time",
    //         "end_time" => "required",
    //     ], [
    //         "date.required" => "Select the date",
    //         "date.date" => "Select a valid date",
    //         "date.after_or_equal" => "The date should be today or after today",
    //         "start_time.required" => "The start time is required.",
    //         "start_time.before" => "The start time must be less than end time.",
    //         "start_time.different" => "The start time must be different from the end time.",
    //         "end_time.required" => "The end time is required.",
    //     ]);
    //     if ($validators->fails()) {
    //         return $this->sendError($validators->messages(),422);
    //     }

    //     $date = date('Y-m-d',strtotime($request->date));
    //     $start = $request->start_time;
    //     $end = $request->end_time;

    //     $existingRecord = TimeSlots::whereDate('date', $date)
    //     ->where(function ($query) use ($start, $end) {
    //         $query->whereBetween('from', [$start, $end])
    //             ->orWhereBetween('to', [$start, $end])
    //             ->orWhere(function ($query) use ($start, $end) {
    //                 $query->where('from', '<=', $start)
    //                     ->where('to', '>=', $end);
    //             });
    //     })
    //     ->first();

    // if ($existingRecord) { // Assuming you have a 'date' field in the parent record
    //     $from = date('h:i: A', strtotime($existingRecord->from));
    //     $to = date('h:i: A', strtotime($existingRecord->to));
    //     return $this->sendError('Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $date . '. Please choose a different time range.', 421);
    // }

    //     TimeSlots::create([
    //         "date" => $request->date,
    //         "from" => $request->start_time,
    //         "to" => $request->end_time,
    //     ]);

    //     $message = "Time slote added successfully";

    //     return $this->sendSuccess($message);
    // }

    public function store(Request $request)
    {
        $validators = Validator($request->all(), [
            "month" => "required",
            "days" => "required",
            "days.*" => "numeric",
            "start_time" => "required|before:end_time|different:end_time",
            "end_time" => "required",
        ], [
            "month.required" => "Select the month",
            "day_ids.required" => "Select any day",
            "day_ids.*.numeric" => "Invalid day is selected.",
            "start_time.required" => "The start time is required.",
            "start_time.before" => "The start time must be less than end time.",
            "start_time.different" => "The start time must be different from the end time.",
            "end_time.required" => "The end time is required.",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $dayIds = $request->days;
        $start = $request->start_time;
        $end = $request->end_time;
        $year = date('Y');
        $month = $request->month;
        $currentDate = Carbon::now();

        $overlaps = [];

        if ($request->id) {
            $slot_date = date('Y-m-d', strtotime($request->slot_date));

            $existingRecord = TimeSlots::where("id", "!=", $request->id)
                ->whereDate('slot_date', $slot_date)
                ->where(function ($query) use ($start, $end) {
                    $query->where(function ($q) use ($start, $end) {
                        $q->where('from', '>=', $start)
                            ->where('from', '<', $end);
                    })->orWhere(function ($q) use ($start, $end) {
                        $q->where('to', '>', $start)
                            ->where('to', '<=', $end);
                    });
                })->first();

            if ($existingRecord) {
                $day = $existingRecord->parent->day;
                $from = $existingRecord->from;
                $to = $existingRecord->to;
                return $this->sendError('Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $day . '. Please choose a different time range.', 421);
            }

            $timeSlot = TimeSlots::find($request->id);
            $date     = new DateTime($request->slot_date);
            $slotDay  = TimeSlots::where("day", $date->format('l'))->first();

            if ($date->format('Y-m-d') >= $currentDate->format('Y-m-d')) {

                $timeSlot->update([
                    "parent_id" => $slotDay->id,
                    "slot_date" => $slot_date,
                    "from" => $request->start_time,
                    "to" => $request->end_time,
                ]);
                return $this->sendSuccess("Time slots updated successfully");

            }
            else {
                return $this->sendError("You have selected the past date for the slot", 421);
            }
        } else {

            $month = date('n', strtotime($month));
            foreach ($dayIds as $dayId) {
                $slot = TimeSlots::find($dayId);
                $targetDay = $slot->day;

                $dayIndex = array_flip(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])[$targetDay];

                $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                for ($day = 1; $day <= $maxDays; $day++) {
                    if (date('w', strtotime("$year-$month-$day")) == $dayIndex) {
                        $slotDate = Carbon::createFromDate($year, $month, $day);

                        $existingOverlappingSlot = TimeSlots::where('parent_id', $dayId)
                            ->whereDate('slot_date', date('Y-m-d', strtotime($slotDate)))
                            ->where(function ($query) use ($start, $end) {
                                $query->where(function ($q) use ($start, $end) {
                                    $q->where('from', '>=', $start)
                                        ->where('from', '<', $end);
                                })->orWhere(function ($q) use ($start, $end) {
                                    $q->where('to', '>', $start)
                                        ->where('to', '<=', $end);
                                });
                            })->first();

                        if (!$existingOverlappingSlot) {
                            if ($slotDate->format('Y-m-d') >= $currentDate->format('Y-m-d')) {
                                TimeSlots::create([
                                    "slot_date" => $slotDate,
                                    "parent_id" => $dayId,
                                    "from" => $start,
                                    "to" => $end,
                                ]);
                            }
                        } else {
                            $overlaps[] = [
                                "day" => $targetDay,
                                "date" => $slotDate->format('Y-m-d'),
                                "from" => $start,
                                "to" => $end,
                            ];
                        }
                    }
                }
            }

            if (count($overlaps) > 0) {
                // Construct a message indicating the overlapping slots
                $message = "Overlapping time slots detected for the following slots.";
                $message .= "\n- So the overlapping time slots are not added.";
                foreach ($overlaps as $overlap) {
                    $fromTime = Carbon::parse($overlap['from'])->format('h:i A');
                    $toTime = Carbon::parse($overlap['to'])->format('h:i A');

                    $message .= "\n- Day: {$overlap['day']}, Date: {$overlap['date']}, From: {$fromTime}, To: {$toTime}";
                }
                return $this->sendError($message, 421);
            } else {
                return $this->sendSuccess("Time slots added successfully");
            }
        }
    }

    // public function store(Request $request)
    // {
    //     $validators = Validator($request->all(), [
    //         "month"      => "required",
    //         "days"       => "required",
    //         "days.*"     => "numeric",
    //         "start_time" => "required|before:end_time|different:end_time",
    //         "end_time"   => "required",
    //     ], [
    //         "month.required" => "Select the month",
    //         "day_ids.required" => "Select any day",
    //         "day_ids.*.numeric" => "Invalid day is selected.",
    //         "start_time.required" => "The start time is required.",
    //         "start_time.before" => "The start time must be less than end time.",
    //         "start_time.different" => "The start time must be different from the end time.",
    //         "end_time.required" => "The end time is required.",
    //     ]);
    //     if ($validators->fails()) {
    //         return $this->sendError($validators->messages(),422);
    //     }

    //     $selectedMonth = $request->input('month');
    //     $currentYear = date('Y');
    //     $completeDate = Carbon::createFromFormat('Y F', $currentYear . ' ' . $selectedMonth);
    //     $completeDateFormatted = $completeDate->format('Y-m-d H:i:s');

    //     $dayIds = $request->days;
    //     $start  = $request->start_time;
    //     $end    = $request->end_time;
    //     $year   = date('Y');
    //     $month  = $request->month;

    //     if ($request->id)
    //     {

    //         $existingRecord = TimeSlots::where('parent_id', $request->days)
    //                 ->where("id","!=",$request->id)
    //                 ->where(function ($query) use ($start, $end) {
    //                     $query->whereBetween('from', [$start, $end])
    //                         ->orWhereBetween('to', [$start, $end])
    //                         ->orWhere(function ($query) use ($start, $end) {
    //                             $query->where('from', '<=', $start)
    //                                 ->where('to', '>=', $end);
    //                         });
    //                 })
    //                 ->first();

    //             if ($existingRecord) {
    //                 $day = $existingRecord->parent->day;
    //                 $from = $existingRecord->from;
    //                 $to = $existingRecord->to;
    //                 return $this->sendError('Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $day . '. Please choose a different time range.',421);

    //             }

    //         $timeSlot = TimeSlots::find($request->id);
    //         $timeSlot->update([
    //             "parent_id" => $request->days,
    //             "from" => $request->start_time,
    //             "to" => $request->end_time,
    //         ]);
    //         $message="Time slot updated successfully";
    //     }
    //     else{
    //         foreach ($dayIds as $dayId)
    //         {
    //             $existingRecord = TimeSlots::where('parent_id', $dayId)
    //                 ->where(function ($query) use ($start, $end) {
    //                     $query->whereBetween('from', [$start, $end])
    //                         ->orWhereBetween('to', [$start, $end])
    //                         ->orWhere(function ($query) use ($start, $end) {
    //                             $query->where('from', '<=', $start)
    //                                 ->where('to', '>=', $end);
    //                         });
    //                 })
    //                 ->first();

    //             if ($existingRecord)
    //             {
    //                 $day = $existingRecord->parent->day;
    //                 $from = $existingRecord->from;
    //                 $to = $existingRecord->to;
    //                 return $this->sendError('Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $day . '. Please choose a different time range.',421);

    //             }
    //         }

    //         foreach ($dayIds as $dayId)
    //         {

    //             $firstDayOfMonth = Carbon::createFromDate($year, Carbon::parse("$month 1")->month);
    //             $slot = TimeSlots::where("parent_id",$dayId)->first();
    //             $dayName = $slot->day;

    //             $dayOfWeek = Carbon::parse($dayName)->dayOfWeek;

    //             $dates = [];

    //             // Loop through the days of the month and find the occurrences of the specified day
    //             for ($day = 1; $day <= $firstDayOfMonth->daysInMonth; $day++)
    //             {
    //                 $currentDate = $firstDayOfMonth->copy()->addDays($day - 1);
    //                 if ($currentDate->dayOfWeek === $dayOfWeek)
    //                 {
    //                     $dates[] = $currentDate->format('Y-m-d');
    //                     if ($currentDate->dayOfWeek === $dayOfWeek) {
    //                         TimeSlots::create([
    //                             "slot_date" => $currentDate->format('Y-m-d'),
    //                             "parent_id" => $dayId,
    //                             "from" => $request->start_time,
    //                             "to" => $request->end_time,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //         $message="Time slot added successfully";
    //     }

    //     return $this->sendSuccess($message);
    // }

    public function delete(Request $request)
    {
        TimeSlots::find($request->id)->delete();
        return $this->sendSuccess("Time slot deleted successfully");
    }

}
