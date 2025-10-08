<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        // Delete any existing password reset records for this user
        PasswordReset::where('user_id', $user->id)->delete();

        // Create a new password reset record
        $passwordReset = PasswordReset::createForUser($user->id);

        // Send reset email (you would implement this with your preferred mail service)
        try {
            // Mail::to($user->email)->send(new ResetPasswordMail($passwordReset->reset_token));
            
            // For now, just return success (you'll implement email sending later)
            return redirect()->back()->with('status', 'We have emailed your password reset link!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send reset email. Please try again.');
        }
    }
}
