<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\blockUser;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{


    public function showVerifyForm()
    {
        return view('auth.verify');
    }



    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user->status == 0) {
            Mail::to($user->email)->send(new blockUser($user));

            return response()->json(['error' => 'error', 'message' => 'Your account has been locked due to too many failed attempts.']);
        } else {
            $otp = rand(10000, 99999);
            $otpRecord = Otp::where('user_id', $user->id)->first();
            $counter = $otpRecord ? $otpRecord->counter + 1 : 1;
            Otp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(1),
                    'counter' => $counter,
                ]
            );
            if ($counter >= 5) {
                $user->status = 0;
                $user->save();
                $otpRecord->delete();
                Mail::to($user->email)->send(new blockUser($user));
                return redirect()->back()->withErrors(['otp' => 'Your account has been locked due to too many failed attempts.']);
            }
            Mail::to($user->email)->send(new OtpMail($otp));
            return response()->json(['status' => 'success', 'message' => 'OTP has been sent to your email.']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'email' => 'required|email',
        ]);

        $otp = $request->input('otp');
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['email' => 'User not found.'], 404);
        }

        $userId = $user->id;
        $otpEntry = Otp::where('user_id', $userId)
            ->where('otp', $otp)
            ->first();

        if ($otpEntry) {
            if ($otpEntry->expires_at->isPast()) {
                return response()->json(['otp' => 'OTP has expired.'], 400);
            }
            auth()->login($user);
            $otpEntry->delete();

            return response()->json(['status' => 'success', 'redirect' => route('home')]);
        } else {
            $otpEntry = Otp::where('user_id', $userId)->first();
            if ($otpEntry) {
                $otpEntry->counter = $otpEntry->counter + 1;
                $otpEntry->save();
            }

            return response()->json(['otp' => 'Invalid OTP.'], 400);
        }
    }


    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->status == 0) {
            return response()->json(['status' => 'error', 'message' => 'Your account has been locked due to too many failed attempts.']);
        }

        $otp = rand(10000, 99999);
        $otpRecord = Otp::where('user_id', $user->id)->first();
        $counter = $otpRecord ? $otpRecord->counter + 1 : 1;

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(1),
                'counter' => $counter,
            ]
        );

        Mail::to($user->email)->send(new OtpMail($otp));

        if ($counter >= 5) {
            $user->status = 0;
            $user->save();
            $otpRecord->delete();
            return response()->json(['status' => 'error', 'message' => 'Your account has been locked due to too many failed attempts.']);
        }
        return response()->json(['status' => 'success', 'message' => 'OTP has been resent to your email.']);
    }
}
