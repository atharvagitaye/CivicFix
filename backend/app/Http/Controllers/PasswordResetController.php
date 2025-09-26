<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function requestReset(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = \App\Models\User::where('email', $request->email)->first();
        $token = bin2hex(random_bytes(32));
        \App\Models\PasswordReset::create([
            'token' => $token,
            'is_used' => false,
            'created_at' => now(),
            'user_id' => $user->id,
        ]);
        // TODO: Send token to user via email
        return response()->json(['message' => 'Password reset token generated. Please check your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        $reset = \App\Models\PasswordReset::where('user_id', $user->id)
            ->where('token', $request->token)
            ->where('is_used', false)
            ->first();
        if (!$reset) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $reset->is_used = true;
        $reset->save();
        return response()->json(['message' => 'Password has been reset successfully.']);
    }
}
