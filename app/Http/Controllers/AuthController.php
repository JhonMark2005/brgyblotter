<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCustom;
use App\Services\BrevoMailer;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('user')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');

        if (empty($username) || empty($password)) {
            return redirect()->route('login')->with('error', 'Username and password are required.');
        }

        $user = User::where('username', $username)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->route('login')->with('error', 'Invalid username or password.');
        }

        $request->session()->regenerate();

        session([
            'user' => [
                'id'        => $user->id,
                'username'  => $user->username,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ],
            'last_activity' => time(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->full_name . '!');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPassword()
    {
        if (session('user')) return redirect()->route('dashboard');
        return view('auth.forgot');
    }

    public function forgotPassword(Request $request)
    {
        $username = trim($request->input('username', ''));
        $email    = trim($request->input('email', ''));

        if (empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('forgot.password')->with('error', 'Please enter your username and email address.');
        }

        $user = User::where('username', $username)->first();

        // Both username AND email must match — always show success to prevent enumeration
        if ($user && !empty($user->email) && strtolower($user->email) === strtolower($email)) {
            $token    = PasswordResetCustom::createForUser((int) $user->id);
            $resetUrl = url('/reset-password?token=' . $token);
            BrevoMailer::sendPasswordReset($user->email, $user->full_name, $resetUrl);
        }

        return redirect()->route('forgot.password')->with('success', 'If the details match, a reset link has been sent. Check your inbox.');
    }

    public function showResetPassword(Request $request)
    {
        if (session('user')) return redirect()->route('dashboard');

        $token = $request->query('token', '');
        $reset = $token ? PasswordResetCustom::findValid($token) : null;

        return view('auth.reset', [
            'token' => $token,
            'valid' => (bool) $reset,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $token    = $request->input('token', '');
        $password = $request->input('password', '');
        $confirm  = $request->input('confirm', '');

        $reset = $token ? PasswordResetCustom::findValid($token) : null;

        if (!$reset) {
            return redirect()->route('login')->with('error', 'This reset link is invalid or has expired.');
        }

        if (strlen($password) < 6) {
            return redirect()->to('/reset-password?token=' . $token)->with('error', 'Password must be at least 6 characters.');
        }

        if ($password !== $confirm) {
            return redirect()->to('/reset-password?token=' . $token)->with('error', 'Passwords do not match.');
        }

        $user = $reset->user;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        PasswordResetCustom::markUsed($token);

        if (!empty($user->email)) {
            BrevoMailer::sendPasswordResetConfirmation($user->email, $user->full_name);
        }

        return redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.');
    }
}
