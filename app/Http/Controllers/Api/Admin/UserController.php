<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCustomerWelcomeEmailJob;
use App\Jobs\SendSubUserWelcomeEmailJob;
use App\Models\BankAccount;
use App\Models\CurrentRole;
use App\Models\CustomerProfile;
use App\Models\Relation;
use App\Models\Session;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CommonTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe as StripeGateway;
use Stripe\StripeClient;

class UserController extends Controller
{
    use CommonTraits;

    public function fetchAll(Request $request)
    {
        $search = $request->search;
        $role = $request->role;

        if ($role == 'customer') {
            $role = \Role::Customer;
        } elseif ($role == 'sub-user') {
            $role = \Role::SubUser;
        }

        $users = User::latest();

        $users->when($role, function ($query) use ($role) {
            $query->where("role", $role);
        });

        $users->where('is_active', 1);

        $users->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where("id", "like", "%$search%")
                    ->orWhere("email", "like", "%$search%")
                    ->orWhere("fname", "like", "%$search%")
                    ->orWhere("lname", "like", "%$search%")
                    ->orWhere("created_at", "like", "%$search%")
                    ->orWhereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%$search%"]);
            });
        });

        $users = $users->paginate(10);

        return response()->json($users);
    }

    public function fetch(Request $request)
    {
        $user = User::with(['details', 'subUsers', 'session'])->find($request->id);
        $user->account_created = Hash::check(predefinedPassword(), $user->password) ? false : true;
        return response()->json($user);
    }

    public function addRelatives(Request $request, $id)
    {
        if ($id != 0) {
            $session = Session::where("user_id", $id)->first();
            $user = User::find($session->user_id);
        } else {
            $user = User::find(auth()->user()->id);
            $session = Session::find($user->session->id);
        }

        if ($user->video && $user->video->getScreenshotUrl()) {
            $screenshot_url = $user->video->getScreenshotUrl();
        } else {
            $screenshot_url = "";
        }

        $validators = Validator($request->all(), [
            '*.fname' => 'required|regex:/^[a-z A-Z]+$/',
            '*.lname' => 'required|regex:/^[a-z A-Z]+$/',
            '*.email' => 'required|email',
            '*.confirmation_email' => 'required|email',
            '*.phone' => 'nullable|max:13|regex:/^(\+\d{1,3})?\d+$/',
            '*.relationship' => 'nullable|string',
        ], [
            '*.fname.required' => 'First name is required at row :index.',
            '*.fname.regex' => 'The first name must be alphabets at row :index.',
            '*.lname.required' => 'Last name is required at row :index.',
            '*.lname.regex' => 'The last name must be alphabets at row :index',
            '*.email.required' => 'Email is required at row :index.',
            '*.email.email' => 'Invalid email format at row :index.',
            '*.confirmation_email.required' => 'Confirm email is required at row :index.',
            '*.confirmation_email.email' => 'Invalid confirm email format at row :index.',
            '*.phone.regex' => 'The phone may only contain digits and the plus symbol (+) (e.g., +11234567890) at row :index.',
            '*.phone.max' => 'The phone must not be greater than 13 digits at row :index.',
            // '*.relationship.required' => 'Relationship is required at row :index.',
        ]);

        if ($validators->fails()) {
            $errorMessages = [];
            foreach ($validators->errors()->all() as $errorMessage) {
                $rowNumber = intval(explode("row ", $errorMessage)[1]);
                $errorMessages[] = str_replace("row {$rowNumber}", "row " . ($rowNumber + 1), $errorMessage);
            }

            return $this->sendError($errorMessages, 421);
        }

        $uniqueEmails = [];

        for ($i = 0; $i < count($validators->valid()); $i++) {
            $subUser = $validators->valid()[$i];

            if ($subUser['email'] != $subUser['confirmation_email']) {
                return $this->sendError(['Email and Confirm Email must match for the same user at row ' . ++$i], 422);
            }

            if (in_array($subUser['email'], $uniqueEmails)) {
                return $this->sendError(['Duplicate entries of email (' . $subUser['email'] . ')'], 422);
            }

            $alreadyUser = User::where("email", $subUser['email'])->first();

            if ($alreadyUser && Relation::where('customer_id', $user->id)->where('subuser_id', $alreadyUser ? $alreadyUser->id : $subUser->id)->count() > 0) {
                return $this->sendError(['You have already added the email (' . $alreadyUser->email . '). in friends and family'], 421);
            }

            $uniqueEmails[] = $subUser['email'];
        }

        $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];

        foreach ($validators->valid() as $user1) {
            $alreadyUser = User::where("email", $user1['email'])->first();

            $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];

            if (!$alreadyUser) {
                $subUser = User::create([
                    "fname" => $user1['fname'],
                    "lname" => $user1['lname'],
                    "email" => $user1['email'],
                    "contact" => $user1['phone'],
                    "role" => \Role::SubUser,
                    "password" => Hash::make(predefinedPassword()),
                ]);

                CustomerProfile::create(['user_id' => $subUser->id]);

                Relation::create([
                    "customer_id" => $user->id,
                    "subuser_id" => $subUser->id,
                    //"relationship" => $user1['relationship'],
                    "relationship" => 'Unknown',
                ]);

                $token = Crypt::encrypt($user->id . "_" . $subUser->id . "_" . getCreatePasswordToken());

                $subUser->remember_token = Str::random(40);
                $subUser->show_notification = 1;
                $subUser->save();

                if ($user->is_active) {

                    $emailData = [
                        'email' => $subUser->email,
                        'customer_name' => $user->fullName(),
                        'family_friend_name' => $subUser->fullName(),
                        'customer_full_name' => $user->fullName(),
                        'recipient_first_name' => $subUser->fname,
                        'customer_first_name' => $user->fname,
                        'password_link' => "{$protocol}://{$host}/create-password/{$token}",
                        'screenshot_url' => $screenshot_url,
                    ];

                    dispatch(new SendSubUserWelcomeEmailJob($emailData));
                }

                LS_Notification($subUser->id, '<b>' . $user->fullName() . "</b> added you to their lifestory ", "home", $user);
            } else {

                Relation::create([
                    "customer_id" => $user->id,
                    "subuser_id" => $alreadyUser->id,
                    //"relationship" => $user1['relationship'],
                    "relationship" => 'Unknown',
                ]);

                if ($user->is_active) {

                    $emailData = [
                        'email' => $alreadyUser->email,
                        'customer_name' => $user->fullName(),
                        'family_friend_name' => $alreadyUser->fullName(),
                        'customer_full_name' => $user->fullName(),
                        'recipient_first_name' => $alreadyUser->fname,
                        'customer_first_name' => $user->fname,
                        'password_link' => "{$protocol}://{$host}/login",
                        'screenshot_url' => $screenshot_url,
                    ];

                    dispatch(new SendSubUserWelcomeEmailJob($emailData));
                }

                LS_Notification($alreadyUser->id, '<b>' . $user->fullName() . "</b> added you to their lifestory ", "home", $user);
            }
        }

        return $this->sendSuccess("Friends and family members added successfully", true);
    }

    public function updateUser(Request $request)
    {
        $validators = Validator($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'fname' => 'required|regex:/^[a-zA-Z \'\\-]+$/',
            'lname' => 'required|regex:/^[a-zA-Z \'\\-]+$/',
            "contact" => "nullable|regex:/^(\+\d{1,3})?\d+$/|max:13",
            'details.country' => 'required',
            'details.state' => 'required',
            'details.city' => 'required|regex:/^[a-z A-Z]+$/',
            'details.zip_code' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'details.dob' => 'required',
        ],
            [
                'id.required' => 'The ID field is required.',
                'id.numeric' => 'The ID must be a numeric value.',
                'id.exists' => 'The selected user does not exist in the database.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'fname.required' => 'The first name field is required.',
                "fname.regex" => "The first name must be alphabets",
                'lname.required' => 'The last name field is required.',
                "lname.regex" => "The first name must be alphabets",
                'details.country.required' => 'The country field is required.',
                'details.state.required' => 'The state field is required.',
                'details.city.required' => 'The city field is required.',
                'details.city.regex' => "City only contain letters and spaces. Numbers and special characters are not allowed.",
                'details.zip_code.regex' => 'The ZIP code only contains letters and numbers.',
                'details.zip_code.min' => 'The ZIP code must be numeric.',
                'details.dob.required' => 'The date of birth field is required.',
                'contact.regex' => "The phone may only contain digits and the plus symbol (+) (e.g., +11234567890)",
                'contact.max' => "The phone number must not be more than 13 digits",
            ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->id);

        $user->update([
            "fname" => $request->fname,
            "lname" => $request->lname,
            "email" => $request->email,
            "contact" => $request->contact,
        ]);

        $details = CustomerProfile::where('user_id', $user->id)->first();
        $details->update([
            "country" => $request->details['country'],
            "state" => $request->details['state'],
            "city" => $request->details['city'],
            "zip_code" => $request->details['zip_code'],
            "dob" => $request->details['dob'],
        ]);

        return $this->sendSuccess("User updated successfully", true);
    }

    public function updateBio(Request $request)
    {
        $validators = Validator($request->all(), [
            'bio' => 'nullable|max:300',
        ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        auth()->user()->details()->update([
            "bio" => $request->bio,
        ]);

        return $this->sendSuccess("Bio updated successfully", true);
    }

    public function transactions()
    {
        $transactions = auth()->user()->transactions()->where('credit', 0)->get();
        return $this->sendSuccess("User transactions fetched successfully", $transactions);
    }
    public function userInfo(Request $request)
    {
        $id = $request->id ? $request->id : auth()->user()->id;
        $user = User::with('details', 'video')->find($id);
        return $this->sendSuccess("User info fetched successfully", $user);
    }

    public function updateProfileImage(Request $request)
    {
        $validators = Validator($request->all(), [
            'profile' => 'required|image|mimes:png,jpeg,jpg',
        ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $image = $request->file('profile');
        $stored_at = 's3';

        $dbName = date("m-d-Y-h_i_s_A") . "-" . $image->getClientOriginalName();
        $originalName = auth()->user()->id . "/" . $dbName;

        if (config('app.switch_storage') == 'local') {
            $image->storeAs('profile_images', $originalName, 'public');
            $url = $dbName;
            $stored_at = 'public';
        } else {
            $path = $image->storeAs('profile_images', $originalName, 's3');
            $image->storeAs('profile_images', $originalName, 's3_backup');
            $url = Storage::disk('s3')->url($path);
        }

        auth()->user()->update(["profile" => $url, "stored_at" => $stored_at]);

        return $this->sendSuccess("Profile image updated successfully", true);
    }

    public function updateDetails(Request $request)
    {
        $dt = new Carbon();
        $before = $dt->subYears(19)->format('Y');

        $validators = Validator($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'contact' => 'nullable|regex:/^(\+\d{1,3})?\d+$/',
        ], [
            'fname.required' => 'First Name is required',
            'lname.required' => 'Last Name is required',
            'contact.regex' => "The phone may only contain digits and the plus symbol (+) (e.g., +11234567890)",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        if (isset($request->details['dob']) && date('Y-m-d', strtotime($request->details['dob'])) > $before) {
            return $this->sendError('You must be 18 years old.', 421);
        }

        $user = User::find($request->id);
        $user->update([
            "fname" => $request->fname,
            "lname" => $request->lname,
            "contact" => $request->contact,
        ]);
        if ($user->details['id']) {
            $user->details()->update([
                "country" => $request->details['country'],
                "state" => $request->details['state'],
                "city" => $request->details['city'],
                "dob" => $request->details['dob'],
            ]);
        } else {
            $details = new CustomerProfile();
            $details->user_id = $user->id;
            $details->country = $request->details['country'];
            $details->state = $request->details['state'];
            $details->city = $request->details['city'];
            $details->save();
        }

        return $this->sendSuccess("Profile setting updated successfully!", true);
    }

    public function fetchSubUsers(Request $request)
    {
        $subUsers = [];

        $id = isset($request->id) ? $request->id : auth()->user()->id;
        $user = User::find($id);

        foreach (getActiveCustomer($user)->subUsers as $subUser) {

            if ($subUser->id != getActiveCustomer(auth()->user())->id) {
                $subUsers[] = $subUser;
            }
        }
        return $this->sendSuccess("Sub users fetched successfully!", $subUsers);

    }

    public function getCustomerOfSubUser()
    {
        $customer = auth()->user()->customerOfSubUser();
        return $this->sendSuccess("Customer of sub user fetched successfully!", $customer);

    }
    public function paymentMethods()
    {
        $banks = auth()->user()->paymentMethods;
        return $this->sendSuccess("Payment Methods of user fetched successfully!", $banks);
    }

    public function checkToken($token)
    {
        try {
            $token = Crypt::decrypt($token);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 421);
        }
        $parts = explode("_", $token);
        $customer_id = $parts[0] ?? $parts[0];
        $subuser_id = $parts[1] ?? $parts[1];
        $suffix = $parts[2] ?? $parts[2];
        if ($suffix == getCreatePasswordToken() && $customer_id && $subuser_id) {
            $data = [
                "customer_id" => $customer_id,
                "subuser_id" => $subuser_id,
            ];
            return $this->sendSuccess("Token verified", $data);
        }
        return $this->sendError("Token not verified", 421);

    }
    public function checkRememberTokenResetPassword(Request $request)
    {

        $validators = Validator($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'token' => 'required|string',
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->id);
        if ($user->remember_token == $request->token) {
            return $this->sendSuccess("Token verified");
        } else {
            return $this->sendError("Token is invalid", 421);
        }

    }

    public function paymentMethodDelete(Request $request)
    {
        BankAccount::find($request->id)->delete();
        $this->sendSuccess('Payment method deleted successfully', true);
    }
    public function cancelSubscription(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->disabled = 'cancel-subscription - ' . $request->reason;
        $user->save();
        $subscription = Subscription::where('user_id', $user->id)->where('is_active', 1)->first();
        $subscription->is_active = 0;
        $subscription->save();
        $this->sendSuccess('You have successfully cancelled subscription.', true);
    }

    public function paymentMethodStore(Request $request)
    {
        StripeGateway::setApiKey(env('STRIPE_SECRET_KEY', null));

        $user = auth()->user();

        if (!$user->stripe_customer_id) {
            $customer = Customer::create(['email' => $user->email]);
            $user->stripe_customer_id = $customer['id'];
            $user->save();
        }

        $token = $request->id;
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $card = $stripe->customers->createSource(
            $user->stripe_customer_id,
            ['source' => $token]
        );

        $bank = new BankAccount();
        $bank->user_id = $user->id;
        $bank->stripe_card_id = $card['id'];
        $bank->card_number = $card['last4'];
        $bank->card_type = $card['brand'];
        $bank->expiry = $card['exp_month'] . '/' . $card['exp_year'];

        if (count(BankAccount::where('user_id', $user->id)->get()) == 0) {
            $bank->preferred = 1;
        }

        $bank->save();

        return response()->json(['success' => true, 'message' => 'Payment method has been added successfully']);
    }
    public function subscriptionAmount()
    {
        $setting = Setting::where("key", "amount-for-booking-subscription")->first();
        return response()->json([
            'success' => true,
            'amount' => $setting->value,
        ]);
    }
    public function subscribe(Request $request)
    {
        $validators = Validator($request->all(), [
            'amount' => 'required|numeric',
            'card_id' => 'required|numeric|exists:bank_accounts,id',
        ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 421);
        }

        StripeGateway::setApiKey(env('STRIPE_SECRET_KEY', null));

        $user = auth()->user();
        $card = BankAccount::find($request->card_id);

        $description = $request->amount . "USD charged for subscription by " . auth()->user()->fullName();

        if (!$user->stripe_customer_id) {
            $customer = Customer::create(['email' => $user->email]);
            $user->stripe_customer_id = $customer['id'];
            $user->save();
        }
        if ($request->resubscribe) {
            $description = $request->amount . "USD charged for re-subscription by " . auth()->user()->fullName();
            $user->disabled = null;
            $user->save();
        }

        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $charge = Charge::create([
            'amount' => $request->amount * 100,
            'currency' => 'USD',
            'customer' => $user->stripe_customer_id,
        ]);

        if ($charge->status == "succeeded") {
            foreach (auth()->user()->subscriptions as $subscription) {
                $subscription->update(['is_active' => 0]);
            }
            $subscription = Subscription::create([
                "user_id" => $user->id,
                "card_id" => $card->id,
                "charge_id" => $charge['id'],
                "is_active" => 1,
                "started_at" => now(),
                "ended_at" => date('Y-m-d H:i:s', strtotime('+1 month', time())),
            ]);

            $transaction_id = generateTransactionId();

            Transaction::create([
                "reference_id" => $subscription->id,
                "transaction_id" => $transaction_id,
                "user_id" => $user->id,
                "debit" => $request->amount,
                "description" => $description,
            ]);

            $admin = User::where("role", \Role::SuperAdmin)->first();

            Transaction::create([
                "transaction_id" => $transaction_id,
                "user_id" => $admin->id,
                "credit" => $request->amount,
                "description" => $description,
            ]);

            return $this->sendSuccess('You are subscribed successfully!');
        }

    }
    public function cardMakePreferred(Request $request)
    {
        foreach (auth()->user()->paymentMethods as $pm) {
            $pm->update(['preferred' => 0]);
        }
        $pm = BankAccount::find($request->id);
        $pm->preferred = 1;
        $pm->save();
        return $this->sendSuccess('Card is preferred successfully!');

    }
    public function subscriptionStatus()
    {
        $accountCreatedOn = auth()->user()->created_at;
        $trialPeriodOfCustomer = Setting::where("key", "trial-period-of-customer-in-days")->first()->value;
        $trialEndsOnInSeconds = strtotime($accountCreatedOn . " +" . $trialPeriodOfCustomer . " days");
        $trialEndsOn = date('Y-m-d', $trialEndsOnInSeconds);
        $earlier = new \DateTime(date('Y-m-d'));
        $later = new \DateTime($trialEndsOn);

        $trialDaysLeft = $earlier->diff($later)->format("%r%a");

        $active = auth()->user()->subscriptions()->where('is_active', 1)->first();
        if ($active) {
            $active->started_at = date('Y-m-d', strtotime($active->started_at));
            $active->ended_at = date('Y-m-d', strtotime($active->ended_at));
        }

        $cancelSubscription = User::where('disabled', 'LIKE', 'cancel-subscription%')->where("id", auth()->user()->id)->first();

        $reason = '';
        if ($cancelSubscription) {
            $reason = explode('cancel-subscription - ', $cancelSubscription->disabled)[1];
        }

        $status = [
            'is_cancelled' => $cancelSubscription ? true : false,
            'reason' => $reason,
            'trial_starts_on' => $accountCreatedOn->format('Y-m-d'),
            'trial_end_on' => $trialEndsOn,
            'trial_days_left' => $trialDaysLeft,
            'is_on_trial' => auth()->user()->subscriptions()->count() == 0 ? true : false,
            'active' => $active,
            'trial_days' => $trialPeriodOfCustomer,
            'remaining_trial' => $trialPeriodOfCustomer,
        ];
        return $this->sendSuccess('Status of subscription', $status);
    }

    public function updateSwitch(Request $request)
    {
        foreach (auth()->user()->customersOfSubUser() as $customer) {
            $relation = Relation::where('customer_id', $customer->id)->where('subuser_id', auth()->user()->id)->first();
            if ($relation && $request->id == $customer->id) {
                $relation->active = 1;
            } else {
                $relation->active = 0;
            }
            $relation->save();
        }
        return $this->sendSuccess('Switched', true);

    }
    public function fetchCustomerForSwitch(Request $request)
    {
        $id = $request->id ? User::find($request->id) : auth()->user();
        $customer = getActiveCustomer($id);
        return $this->sendSuccess('Switched', $customer);

    }

    public function getRoles()
    {
        $user = auth()->user();
        $relationsIds = Relation::Where("subuser_id", $user->id)->pluck("customer_id")->toArray();
        $customers = User::whereIn("id", $relationsIds)->get();
        if (count($customers) > 0) {
            $user['customers'] = $customers;
        } else {
            $user['customers'] = [];
        }
        $currentRole = CurrentRole::where('user_id', auth()->user()->id)->first();
        $current = $currentRole
        ? $currentRole->customer_id
        : auth()->user()->id;

        $user['current'] = $current;
        $user['currentCustomer'] = User::with('details')->find($current);
        return $this->sendSuccess('User roles fetched successfully', $user);
    }
    public function getSwitches(Request $request)
    {

        $user = $request->id ? User::find($request->id) : auth()->user();
        $relationsIds = Relation::where("subuser_id", $user->id)->pluck("customer_id")->toArray();
        $customers = User::whereIn("id", $relationsIds)->get();
        $switches = [];
        if ($user->role == \RoleString::Customer && count($user->customersOfSubUser()) > 0) {
            $customers[] = $user;
            $customerId = [];
            $customerId[] = $user->id;
            foreach ($customers as $customer) {$customerId[] = $customer->id;}
            $switches = User::whereIn('id', array_unique($customerId))->get();
        }
        if ($user->role == \RoleString::SubUser) {
            $switches = $customers;
        }
        return $this->sendSuccess('User switches fetched successfully', $switches);
    }

    public function switchAccount(Request $request)
    {
        if ($request->id == auth()->user()->id) {
            CurrentRole::where('user_id', auth()->user()->id)->delete();
            if (auth()->user()->role == \RoleString::SubUser) {
                $primaryCustomer = Relation::Where("subuser_id", auth()->user()->id)->first();
                $currentRole = new CurrentRole();
                $currentRole->user_id = auth()->user()->id;
                $currentRole->role = 'Sub User';
                $currentRole->customer_id = $primaryCustomer->customer_id;
                $currentRole->save();
            }
            $current = auth()->user()->id;
        } else {
            CurrentRole::where('user_id', auth()->user()->id)->delete();
            $currentRole = new CurrentRole();
            $currentRole->user_id = auth()->user()->id;
            $currentRole->role = 'Sub User';
            $currentRole->customer_id = $request->id;
            $currentRole->save();
            $current = $currentRole->customer_id;
        }
        return $this->sendSuccess('Role switched successfully', $current);
    }

    public function getVideo(Request $request)
    {
        return getActiveCustomer(auth()->user())->video->file;
    }
    public function isSwitch(Request $request)
    {

        return $this->sendSuccess('User need switch fetched successfully!', getSwitch(auth()->user()));
    }

    public function updateCustomer(Request $request)
    {
        $validators = Validator($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'password' => [
                'required',
                'string',
                'min:3',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/',
                // function ($attribute, $value, $fail) use ($request) {
                //     $user = User::findOrFail($request->input('user_id'));
                //     $previousPassword = $user->password;

                //     if (app('hash')->check($value, $previousPassword)) {
                //         $fail('The password must be different from the previous one.');
                //     }
                // },
            ],
            'password_confirmation' => 'required|string|min:3',
        ], ['password.regex' => 'The password must contain at least one uppercase letter and one number.']);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $customer = User::find($request->user_id);

        if (Hash::check(predefinedPassword(), $customer->password)) {
            $message = "Password created successfully";
            $customer->update(["created_at" => now()]);
        } else {
            $message = "Password updated successfully";
        }

        $customer->password = Hash::make($request->password);
        if ($customer->role == \RoleString::SubUser) {
            $customer->is_active = 1;
        }

        $customer->save();

        return $this->sendSuccess($message, true);

    }

    public function delete(Request $request)
    {
        $validators = Validator($request->all(), ['id' => 'required|numeric|exists:users,id']);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->id);
        if ($user->role == \RoleString::SuperAdmin) {
            return $this->sendError('You cannot delete super admin', 422);
        } elseif ($user->role == \RoleString::Customer) {
            $user->delete();
            return $this->sendSuccess('Customer deleted successfully', true);
        } elseif ($user->role == \RoleString::SubUser) {
            $user->delete();
            return $this->sendSuccess('Subuser deleted successfully', true);
        } else {
            return $this->sendError("Something went wrong", false);
        }

    }
    public function deleteFNFMember(Request $request)
    {
        $validators = Validator($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'customer_id' => 'required|numeric|exists:users,id',
        ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->customer_id);
        if ($user->role == \RoleString::Customer) {
            Relation::where("customer_id", $user->id)->where("subuser_id", $request->id)->delete();
            return $this->sendSuccess('Subuser deleted successfully', true);
        } else {
            return $this->sendError("Something went wrong", false);
        }
    }

    public function createCustomerAccount(Request $request)
    {
        $validators = Validator($request->all(), ['id' => 'required|numeric|exists:users,id']);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->id);
        $user->is_active = 1;
        $user->save();

        $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        if ($user->video && $user->video->getScreenshotUrl()) {
            $screenshot_url = $user->video->getScreenshotUrl();
        } else {
            $screenshot_url = "";
        }

        $data = [
            "email" => $user->email,
            "join_url" => url('login'),
        ];

        dispatch(new SendCustomerWelcomeEmailJob($data));

        foreach ($user->subUsers as $subUser) {

            if ($user->id != $subUser->id) {
                $token = Crypt::encrypt($user->id . "_" . $subUser->id . "_" . getCreatePasswordToken());

                $subUser->remember_token = Str::random(40);

                if (!$subUser->is_active) {
                    $password_link = "{$protocol}://{$host}/create-password/{$token}";
                } else {
                    $password_link = "{$protocol}://{$host}/login";
                }

                $emailData = [
                    'email' => $subUser->email,
                    'customer_name' => $user->fullName(),
                    'customer_full_name' => $user->fullName(),
                    'family_friend_name' => $subUser->fullName(),
                    'recipient_first_name' => $subUser->fname,
                    'customer_first_name' => $user->fname,
                    'password_link' => $password_link,
                    'screenshot_url' => $screenshot_url,
                ];

                dispatch(new SendSubUserWelcomeEmailJob($emailData));
            }
        }
        return $this->sendSuccess('Customer account created successfully', true);
    }
    public function FNFUpdate(Request $request)
    {
        $validators = Validator($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'fname' => 'required|regex:/^[a-zA-Z \'\\-]+$/',
            'lname' => 'required|regex:/^[a-zA-Z \'\\-]+$/',
            'contact' => "nullable|regex:/^(\+\d{1,3})?\d+$/|max:13",
        ],
            [
                'id.required' => 'The ID field is required.',
                'id.numeric' => 'The ID must be a numeric value.',
                'id.exists' => 'The selected user does not exist in the database.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'fname.required' => 'The first name field is required.',
                'fname.regex' => "The first name must be alphabets",
                'lname.required' => 'The last name field is required.',
                'lname.regex' => "The first name must be alphabets",
                'contact.regex' => "The phone may only contain digits and the plus symbol (+) (e.g., +11234567890)",
                'contact.max' => "The phone number must not be more than 13 digits",
            ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $user = User::find($request->id);

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->contact = $request->contact;
        $user->save();

        return $this->sendSuccess('Customer account created successfully', true);
    }

}
