<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Mail; // MUST ADD THIS
use Illuminate\Support\Facades\Hash; // MUST ADD THIS
use App\Mail\ResetPasswordMail;      // MUST ADD THIS

class AuthController extends Controller
{

    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable',
            'address' => 'nullable',
            'type' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 1. បង្កើត User ថ្មី
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. គ្រប់គ្រងការ Upload រូបភាព (Handle image upload)
        $imagePath = null;
        if ($request->hasFile('image')) {
            // រក្សាទុករូបភាពក្នុង folder 'profiles' នៃ disk 'public'
            $imagePath = $request->file('image')->store('profiles', 'public');
        }

        // 3. បង្កើត Profile ដោយប្រើ $imagePath ដែលទទួលបាន
        $user->profile()->create([
            'phone'   => $request->phone,
            'address' => $request->address,
            'image'   => $imagePath, // ប្រើអថេរ $imagePath ដែលជា string path
            'type'    => $request->type,
        ]);

        return response()->json([
            'user' => $user->load('profile'),
            'message' => 'User registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to verify the credentials and create a token
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'user' => JWTAuth::user()->load('profile'),
        ]);
    }



    // create forgotpassword
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generate a 6-digit code
        $otp = rand(100000, 999999);

        // Store or Update OTP in database
        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );

        // Send via Mail (Create a Mailable: php artisan make:mail ResetPasswordMail)
        Mail::to($request->email)->send(new ResetPasswordMail($otp));

        return response()->json(['message' => 'OTP sent to your email.']);
    }


    // How to resetpassword
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed'
        ]);

        $resetData = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$resetData || $resetData->created_at->addMinutes(15)->isPast()) {
            return response()->json(['error' => 'Invalid or expired OTP.'], 422);
        }

        // Update User Password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Clean up OTP
        $resetData->delete();

        return response()->json(['message' => 'Password reset successfully.']);
    }
}
