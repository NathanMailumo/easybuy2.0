<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AuthModel;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin;

class AuthController extends Controller
{
    public function dashboard()
    {
       if (Auth::check()) {
        $user = Auth::user();
        return $user->role === 'seller' 
            ? redirect()->route('seller.dashboard') 
            : redirect()->route('buyer.dashboard');
    }
    return view('welcome');
    }

   
    public function showLogin()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|max:30',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            // $user = Auth::user();
            return redirect()->intended(route('admin.index'));
        }
        
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'seller') {
                return redirect()->intended(route('seller.dashboard'));
            }

            if ($user->role === 'buyer') {
                return redirect()->intended(route('buyer.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        };
        return back()->withErrors([
            'email' => 'Invalid email or password entered.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        // return view('dashboard');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showReset()
    {
        return view('reset');
    }

    public function reset(Request $request)
    {
        $validateEmail = $request->validate([
            'email' => 'required|email|string|exists:users,email',
        ], [
            'email.exists' => 'Invalid email',
        ]);

        $code = random_int(100000, 999999);

        // Store or update the reset code record
        PasswordReset::updateOrCreate(
            ['email' => $validateEmail['email']],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // send otp mail to user through mailtrap
        Mail::to($validateEmail['email'])->send(new SendOtpMail($code));

        // Store code in session flash data for testing alert display
        return redirect()->route('auth.verify', ['email' => $validateEmail['email']])
            ->with('success', 'A verification code has been sent to your email.');
    }

    public function showVerify(Request $request)
    {
        return view('verify', ['email' => $request->query('email')]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'code' => 'required|digits:6',
        ]);

        $records = PasswordReset::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$records || $records->expires_at->isPast()) {
            # code...
            return back()->withErrors([
                'code' => 'The code is invalid',
            ])->withInput();
        }
        $records->delete();

        return redirect()->route('auth.password.create', ['email' => $request->email]);
    }

    public function showCreate()
    {
        return view('create');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|string|min:8|confirmed', // Automatically matches 'password_confirmation'
        ]);

        // 2. Hash and update password in DB
        AuthModel::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect back to login with success feedback
        return redirect()->route('login')->with('success', 'Your password has been reset successfully!');
    }

}
