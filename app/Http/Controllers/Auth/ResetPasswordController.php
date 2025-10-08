<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Find the password reset record
        $passwordReset = PasswordReset::findValidToken($request->token);

        if (!$passwordReset) {
            return redirect()->back()
                ->withErrors(['token' => 'This password reset token is invalid or has expired.'])
                ->withInput($request->only('email'));
        }

        // Verify the email matches
        if ($passwordReset->user->email !== $request->email) {
            return redirect()->back()
                ->withErrors(['email' => 'This email does not match the reset token.'])
                ->withInput($request->only('email'));
        }

        // Update the user's password
        $user = $passwordReset->user;
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset record
        $passwordReset->delete();

        // Log the user in
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Your password has been reset successfully!');
    }
}
