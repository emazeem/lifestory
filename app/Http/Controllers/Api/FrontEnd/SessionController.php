<?php

namespace App\Http\Controllers\Api\FrontEnd;

use App\Jobs\ScheduledZoomMeetingEmailJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerProfile;
use App\Traits\ZoomOAuthTrait;
use App\Models\CreaterDetail;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Models\TimeSlots;
use App\Models\Relation;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use DateTime;

class SessionController extends Controller
{
    use CommonTraits;

    use ZoomOAuthTrait;

    public function fetchSlots(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->date));
        $currentTime = now()->format('H:i:s');

        $isToday = $date == now()->format('Y-m-d') ? true : false;

        $timeSlots = TimeSlots::whereDate("slot_date", $date)
            ->whereNull('booked')
            ->when($isToday, function ($query) use ($currentTime) {
                $query->where("from", ">", $currentTime);
            })->get();
        return response()->json([
            "success" => true,
            "slots" => $timeSlots,
            "date" => $date,
            "slot_count" => count($timeSlots) ?? 0,
        ]);
    }

    public function fetchDates()
    {
        $formattedDate = Carbon::now()->format('l, M j, Y');

        $currentDateTime = Carbon::now();
        $currentDate = $currentDateTime->format('m/d/Y, g:i:s A');

        $startDate = null;
        $endDate = now()->subDay();
        
        return response()->json([
            'formattedDate' => $formattedDate,
            'currentDate' => $currentDate,
            'disabledDates' => [['start' => $startDate, 'end' => $endDate]]
        ]);
    }

    public function storyCost()
    {
        $cost = Setting::where('key', 'amount-for-booking-session')->first();
        return $this->sendSuccess("Validation Success", $cost->value);
    }

    public function validation(Request $request)
    {
        $rules = [
            "first_name" => "required|regex:/^[a-z A-Z]+$/",
            "last_name" => "required|regex:/^[a-z A-Z]+$/",
            "email" => "required|email",
            "confirmation_email" => "required|email|same:email",
            "contact" => "required|regex:/^(\+\d{1,3})?\d+$/",
            "city" => 'required|regex:/^[a-z A-Z]+$/',
            "state" => "nullable",
            "country" => "required",
            "zip_code" => "required|regex:/^[a-zA-Z0-9]+$/",
            "day" => 'required|between:1,31',
            "month" => 'required|between:1,12',
            "year" => 'required',
            // "account_for" => "required|in:my_self,behalf_of",
        ];

        $messages = [
            "first_name.required" => "First name is required.",
            "first_name.regex" => "The first name must be alphabets",
            "last_name.required" => "Last name is required.",
            "last_name.regex" => "The last name must be alphabets",
            "email.required" => "Email is required.",
            "confirmation_email.required" => "Confirm Email is required.",
            "confirmation_email.same" => "Confirm Email does not match.",
            "contact.required" => "Phone number is required.",
            "contact.regex" => "The phone may only contain digits and the plus symbol (+) (e.g., +11234567890)",
            "state.required" => "State is required.",
            "city.regex" => "City only contain letters and spaces. Numbers and special characters are not allowed.",
            "country.required" => "Country is required.",
            "zip_code.required" => "Zip code is required.",
            "zip_code.regex" => "The zip code is only contains letters and numbers",
            "date_of_birth.required" => "Date of birth is required.",
            "date_of_birth.before" => "You must be older than 18 years.",
            "day.required" => "Day is required.",
            "month.required" => "Month is required.",
            "year.required" => "Year is required.",
            // "account_for.required" => "Account for is required.",
        ];

        // if ($request->account_for == 'behalf_of')
        // {
        //     $rules += [
        //         "filler_first_name" => "required|regex:/^[a-z A-Z]+$/",
        //         "filler_last_name" => "required|regex:/^[a-z A-Z]+$/",
        //         "filler_email" => "required",
        //         "filler_contact" => "required",
        //         "filler_city" => "nullable",
        //         "filler_state" => "required",
        //         "filler_country" => "required",
        //         "filler_zip_code" => "required",
        //         "relation" => "required",
        //         "filler_date_of_birth" => 'required|date|before:' . $before,
        //     ];

        //     $messages += [
        //         "filler_first_name.required" => "first name is required.",
        //         "filler_first_name.regex" => "The first name must contain only characters from A-Z..",
        //         "filler_last_name.required" => "last name is required.",
        //         "filler_last_name.regex" => "The last name must contain only characters from A-Z..",
        //         "filler_email.required" => "email is required.",
        //         "filler_contact.required" => "Phone number is required.",
        //         "filler_state.required" => "state is required.",
        //         "filler_country.required" => "country is required.",
        //         "filler_zip_code.required" => "Zip code is required.",
        //         "filler_date_of_birth.required" => "date of birth is required.",
        //         "filler_date_of_birth.before" => "You must be older than 18 years.",
        //         "relation.required" => "Relation is required.",
        //     ];
        // }

        $validators = Validator($request->all(), $rules, $messages);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $dateOfBirth = Carbon::create($request->input('year'), $request->input('month'), $request->input('day'));

        // Check if the user is at least 18 years old
        if ($dateOfBirth->age < 18) {
            return $this->sendError('You must be at least 18 years old to proceed.', 421);
        }

        // $user = User::where("email", $request->email)->where('role', \Role::Customer)->first();
        // if ($user) {
        //     if ($user->has('session')) {
        //         return $this->sendError("You have already booked a session", 421);
        //     }
        // }

        $user = User::withTrashed()->where("email", $request->email)->where('role', \Role::Customer)->first();

        if ($user) {
            if ($user->trashed()) {
                // User has been soft deleted
                return $this->sendError("This user's account requires special attention. Please contact the admin for assistance.", 421);
            } else {
                // User exists and is not soft deleted
                if ($user->session) {
                    return $this->sendError("You have already booked a session", 421);
                }
            }
        }

        return $this->sendSuccess("Validation Success", true);
    }

    public function store(Request $request)
    {
        $dt = new Carbon();
        $before = $dt->subYears(18)->format('Y');

        $rules = [
            "first_name" => "required|regex:/^[a-z A-Z]+$/",
            "last_name" => "required|regex:/^[a-z A-Z]+$/",
            "email" => "required|email",
            "confirmation_email" => "required|email|same:email",
            "contact" => "required|regex:/^(\+\d{1,3})?\d+$/",
            "city" => 'required|regex:/^[a-z A-Z]+$/',
            "state" => "nullable",
            "country" => "required",
            "zip_code" => "required|regex:/^[a-zA-Z0-9]+$/",
            "day" => 'required|between:1,31',
            "month" => 'required|between:1,12',
            "year" => 'required',
        ];

        $messages = [
            "first_name.required" => "First name is required.",
            "first_name.regex" => "The first name must be alphabets",
            "last_name.required" => "Last name is required.",
            "last_name.regex" => "The last name must be alphabets",
            "email.required" => "Email is required.",
            "confirmation_email.required" => "Confirm Email is required.",
            "confirmation_email.same" => "Confirm Email does not match.",
            "contact.required" => "Phone number is required.",
            "contact.regex" => "The phone may only contain digits and the plus symbol (+) (e.g., +11234567890)",
            "state.required" => "State is required.",
            "city.regex" => "City only contain letters spaces. Numbers and special characters are not allowed.",
            "country.required" => "Country is required.",
            "zip_code.required" => "Zip code is required.",
            "zip_code.regex" => "Zip code is only contains letters and numbers.",
            "date_of_birth.required" => "Date of birth is required.",
            "date_of_birth.before" => "You must be older than 18 years.",
            "day.required" => "Day is required.",
            "month.required" => "Month is required.",
            "year.required" => "Year is required.",
            // "account_for.required" => "Account for is required.",
        ];

        $validators = Validator($request->all(), $rules, $messages);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $timeSlot = TimeSlots::find($request->slot_id);

        if (!$timeSlot) {
            return $this->sendError("The time slot is not available.", 421, ["booked" => true]);
        } elseif ($timeSlot->booked) {
            return $this->sendError("The time slot you have selected is already booked by someone. Please choose a different time slot.", 421, ["booked" => true]);
        }

        $dateOfBirth = Carbon::create($request->input('year'), $request->input('month'), $request->input('day'));

        $password = Hash::make(predefinedPassword());

        $userExists = User::where("email", $request->email)->exists();

        DB::beginTransaction();

        try {

            if ($userExists) {
                $user = User::where("email", $request->email)->first();
                if ($user->role == \RoleString::SubUser) {
                    $user->role = \Role::Customer;
                    $user->save();
                    $relation = new Relation();
                    $relation->customer_id = $user->id;
                    $relation->subuser_id = $user->id;
                    $relation->relationship = 'Unknown';
                    $relation->save();
                }
            } else {

                $user = User::create([
                    "fname" => $request->first_name,
                    "lname" => $request->last_name,
                    "email" => $request->email,
                    "contact" => $request->contact,
                    "role" => \Role::Customer,
                    "created_for" => $request->account_for,
                    "password" => $password,
                ]);
            }

            if ($request->account_for == 'behalf_of') {
                CreaterDetail::create([
                    "user_id" => $user->id,
                    "contact" => $request->filler_contact,
                    "city" => $request->filler_city,
                    "state" => $request->filler_state,
                    "country" => $request->filler_country,
                    "zip_code" => $request->filler_zip_code,
                    "dob" => $request->filler_date_of_birth,
                ]);
            }

            if (!$userExists) {

                CustomerProfile::create([
                    "user_id" => $user->id,
                    "city" => $request->city,
                    "state" => $request->state,
                    "country" => $request->country,
                    "zip_code" => $request->zip_code,
                    // "dob" => $request->date_of_birth,
                    "dob" => $dateOfBirth,
                ]);
            } else {
                $user->details()->update([
                    "city" => $request->city,
                    "state" => $request->state,
                    "country" => $request->country,
                    "zip_code" => $request->zip_code,
                    // "dob" => $request->date_of_birth,
                    "dob" => $dateOfBirth,
                ]);
            }

            $startDateTime = $request->date . ' ' . $timeSlot->from;
            $endDateTime = $request->date . ' ' . $timeSlot->to;

            $start_time = date('Y-m-d\TH:i:s', strtotime($startDateTime));
            $end_time = date('Y-m-d\TH:i:s', strtotime($endDateTime));

            $fromDateTime = DateTime::createFromFormat('H:i:s', $timeSlot->from);
            $toDateTime = DateTime::createFromFormat('H:i:s', $timeSlot->to);
            $duration = $fromDateTime->diff($toDateTime);
            $totalMinutes = ($duration->h * 60) + $duration->i;

            $meetingData = [
                'topic' => 'Zoom meeting for ' . $user->fullName() . ' - ' . $user->email,
                'type' => 2,
                'start_time' => $start_time,
                'duration' => $totalMinutes,
                'agenda' => 'Session booked for interview with life story',
                'password' => '123456',
                'host_video' => '1',
                'participant_video' => '1',
            ];

            $response = $this->createZoomMeeting($meetingData);

            $session = Session::create([
                'user_id' => $user->id,
                'time_slot_id' => $timeSlot->id,
                'uuid' => $response->uuid,
                'meeting_id' => $response->id,
                'host_email' => $response->host_email,
                'topic' => $response->topic,
                'start_time' => date('Y-m-d H:i:s', strtotime($start_time)),
                'end_time' => date('Y-m-d H:i:s', strtotime($end_time)),
                'duration' => $response->duration,
                'agenda' => $response->agenda,
                'start_url' => $response->start_url,
                'join_url' => $response->join_url,
                'password' => $response->password,
                'status' => $response->status,
            ]);

            $timeSlot->booked = $session->id;
            $timeSlot->save();

            DB::commit();

            $cost = Setting::where('key', 'amount-for-booking-session')->first();

            $startDateTime = Carbon::parse($start_time);
            $formattedStartTime1 = $startDateTime->format('l, F j, Y \a\t g:i A');
            $linkDate = $startDateTime->format('h:i A \P\S\T \o\n m-d-Y');

            $data = [
                "email" => $request->email,
                "cost" => "$".$cost->value,
                "duration" => $response->duration,
                "join_url" => $response->join_url,
                "linkDate" => $linkDate,
                "formattedStartTime1" => $formattedStartTime1,
            ];

            dispatch(new ScheduledZoomMeetingEmailJob($data));

            $admin = User::where("role", \Role::SuperAdmin)->first();
            $sender = [
                "id" => $user->id,
                "profile" => $user->profile,
                "fname" => $user->fname,
                "lname" => $user->lname,
            ];

            $admin->show_notification = 1;
            $admin->save();

            LS_Notification($admin->id, '<b>' . $user->fullName() . " has booked a session on " . date('m-d-Y', strtotime($start_time)) . " at " . date('h:i A', strtotime($start_time)) . '</b>', "session/view/" . $session->id, $sender);

            return response()->json([
                "success" => true,
                "message" => "Success!  Your Lifestory recording has been booked.  You will receive a confirmation email shortly with details about your session as well as a Zoom link.  Thanks for participating in Project Lifestory!",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
