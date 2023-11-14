<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlots;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            "day_ids" => "required|array",
            "day_ids.*" => "numeric",
            "start_time" => "required|before:end_time|different:end_time",
            "end_time" => "required",
        ], [
            "day_ids.required" => "Select any day",
            "day_ids.*.numeric" => "Invalid day is selected.",
            "start_time.required" => "The start time is required.",
            "start_time.before" => "The start time must be less than end time.",
            "start_time.different" => "The start time must be different from the end time.",
            "end_time.required" => "The end time is required.",
        ]);

        $dayIds = $request->day_ids;
        $start = $request->start_time;
        $end = $request->end_time;

        foreach ($dayIds as $dayId) {
            $existingRecord = TimeSlots::where('parent_id', $dayId)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('from', [$start, $end])
                        ->orWhereBetween('to', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('from', '<=', $start)
                                ->where('to', '>=', $end);
                        });
                })
                ->first();

            if ($existingRecord) {
                $day = $existingRecord->parent->day;
                $from = $existingRecord->from;
                $to = $existingRecord->to;
                return response()->json([
                    'success' => false,
                    'message' => 'Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $day . '. Please choose a different time range.',
                ]);
            }
        }

        foreach ($dayIds as $dayId) {

            TimeSlots::create([
                "parent_id" => $dayId,
                "from" => $request->start_time,
                "to" => $request->end_time,
            ]);

        }

        return response()->json([
            "success" => true,
            "message" => "Time slot added successfully",
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            "edit_slot_id" => "required|numeric|exists:time_slots,id",
            "day_id"       => "required|numeric",
            "start_time"   => "required|before:end_time|different:end_time",
            "end_time"     => "required",
        ]);

        $timeSlot = TimeSlots::find($request->edit_slot_id);

        $dayId = $request->day_id;
        $start = $request->start_time;
        $end   = $request->end_time;

        $existingRecord = TimeSlots::where('parent_id', $dayId)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('from', [$start, $end])
                        ->orWhereBetween('to', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('from', '<=', $start)
                                ->where('to', '>=', $end);
                        });
                })
                ->first();

            if ($existingRecord) {
                $day = $existingRecord->parent->day;
                $from = $existingRecord->from;
                $to = $existingRecord->to;
                return response()->json([
                    'success' => false,
                    'message' => 'Time slot overlap detected. The time slot you are adding overlaps with the existing time slot from ' . $from . ' to ' . $to . ' for ' . $day . '. Please choose a different time range.',
                ]);
            }

        $timeSlot->update([
            "parent_id" => $request->day_id,
            "from"      => $request->start_time,
            "to"        => $request->end_time,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Time slot successfully updated",
        ]);
    }
    public function delete(Request $request)
    {
        TimeSlots::find($request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted',
        ]);
    }

}
