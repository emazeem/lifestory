<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendForgetPasswordEmailJob;
use App\Models\User;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use CommonTraits;
    public function login(Request $request)
    {
        $validators = Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->role == \RoleString::Customer) {
                if ($user->is_active == 0 || !$user->session->payment) {
                    return response()->json(['message' => 'Invalid credentials'], 421);
                }
            }

            $token = $user->createToken('API Token')->plainTextToken;

            if ($user->role == \RoleString::SubUser) 
            {
                if (count($user->customersOfSubUser()) == 1 && getActiveCustomer($user)->disabled) {
                    return response()->json(['message' => 'Your customer is not active'], 421);
                }

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                    'switch' => getSwitch($user),
                ], 200);

            }
            return response()->json([
                'status' => false,
                'token' => $token,
                'user' => $user,
                'switch' => getSwitch($user),
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 421);
        }
    }
    public function createNewPassword(Request $request)
    {
        $validators = Validator($request->all(), [
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/'],
        ], ['password.regex' => 'The password must contain at least one uppercase letter and one number.']);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }
        $user = User::find($request->id);

        if($user && !$user->remember_token)
        {
            return $this->sendError("Link expired or already used", 421);
        }
        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->is_active = 1;
        $user->save();
        return response()->json(['message' => 'Password updated successfully.']);
    }
    public function resetPassword(Request $request)
    {

        $validators = Validator(
            $request->all(),
            ['email' => 'required|email|exists:users'],
            ['email.exists' => 'This account doesn\'t match our record']
        );

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {

            $user->remember_token = Str::random(40);
            $user->save();

            $data = [
                "email" => $user->email,
                "user_name" => $user->fullName(),
                "join_url" => url('create-new-password/' . $user->id . '/' . $user->remember_token),
            ];

            dispatch(new SendForgetPasswordEmailJob($data));
            
        }
        return response()->json(['message' => 'If the email entered is associated with a Life Story account, you will receive an email with password reset instructions.']);
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            try {
                $user->currentAccessToken()->delete();
            } catch (\Throwable $th) {
            }
        }
        return $this->sendSuccess("Logout Successfully!", true);

    }
}
