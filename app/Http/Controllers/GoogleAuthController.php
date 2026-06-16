<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        if (!config('services.google.client_id')) {
            return redirect()->route('home')->with('error', __('Google sign-in is not configured. Please use email or phone to sign in.'));
        }
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', __('Unable to sign in with Google. Please try again.'));
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            Auth::login($user, true);
            return redirect()->intended(route('home'));
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update(['google_id' => $googleUser->getId()]);
            Auth::login($user, true);
            return redirect()->intended(route('home'));
        }

        $user = User::create([
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
            'email' => $googleUser->getEmail(),
            'username' => $this->uniqueUsername($googleUser),
            'google_id' => $googleUser->getId(),
            'password' => Hash::make(Str::random(32)),
            'email_verified' => 1,
            'status' => 'active',
        ]);

        Auth::login($user, true);
        return redirect()->intended(route('home'));
    }

    private function uniqueUsername($googleUser): string
    {
        $base = Str::slug($googleUser->getName() ?: $googleUser->getNickname() ?: 'user');
        $base = preg_replace('/[^a-z0-9]/', '', $base) ?: 'user';
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }
        return $username;
    }
}
