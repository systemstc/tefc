<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $previous = url()->previous();
        if ($request->query('redirect') === 'back' && $previous && !str_contains($previous, '/login') && !str_contains($previous, '/admin-login') && !str_contains($previous, '/register')) {
            session(['url.intended' => $previous]);
        }
        if ($request->is('admin-login')) {
            return view('admin-login');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Enforce account activation & centre verification workflow
            if ($user->status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $centreName = $user->tefc_centre ? ucfirst($user->tefc_centre) : 'Regional';
                return back()->withInput($request->only('email'))->withErrors([
                    'email' => "Your exporter account is pending verification and approval by the {$centreName} TEFC Centre. You will be able to log in once your application has been verified and activated.",
                ]);
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->exporterProfile?->rejection_reason;
                $msg = 'Your registration application was rejected by the TEFC Centre.' . ($reason ? " Reason: {$reason}" : ' Please contact the centre for assistance.');
                return back()->withInput($request->only('email'))->withErrors([
                    'email' => $msg,
                ]);
            }

            if ($user->status === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('email'))->withErrors([
                    'email' => 'Your account is currently inactive. Please contact your organization administrator or the TEFC Centre.',
                ]);
            }

            $request->session()->regenerate();
            if ($user->hasRole('admin')) {
                $default = '/dashboard';
            } elseif ($user->hasRole('center')) {
                $default = '/center/dashboard';
            } else {
                $default = '/exporter-portal';
            }
            return redirect()->intended($default);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
