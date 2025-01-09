<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    // activate account

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return redirect('/')->with('error', 'Invalid activation token');
        }

        return view('auth.activate', ['token' => $token]);
    }


    // set password
    public function setPassword(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Invalid activation token');
        }

        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
                'confirmed',
            ],
        ]);

        $user->password = Hash::make($request->password);
        $user->activation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return redirect('login')->with('success', 'Account activated. You can now log in');
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activation_token' => Str::random(60),

        ]);

        // event(new Registered($user));`
        $user->notify(new AccountActivation($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function resendActivationEmail($user)
    {
        $user->notify(new AccountActivation($user));
    }
}
