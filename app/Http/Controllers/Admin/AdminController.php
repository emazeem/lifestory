<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use CommonTraits;
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator($request->all(), [
            "password" => ["required","min:3","confirmed","regex:/^(?=.*[A-Z])(?=.*[0-9]).+$/"],
            "old_password" =>"required|min:3",
        ], [
            "password.required" => "Password is required.",
            "old_password.required" => "Old password is required.",
            "password.regex" => "The password must contain at least one uppercase letter and one number."
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages(), 422);
        }

        $user = auth()->user();
        if (!password_verify($request->old_password, $user->password)) {
            return $this->sendError("Old password is incorrect", 421);
        }
        if ($request->password==$request->old_password) {
            return $this->sendError("New and old password can not be same", 421);
        }

        $user->update([
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "success" => true,
            "message" => "Passwords updated successfully",
        ]);

    }

}
