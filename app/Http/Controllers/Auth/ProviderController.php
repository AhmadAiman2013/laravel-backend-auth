<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'google_id' => $googleUser->getId(),
            ], [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),

                'google_token' => $googleUser->token,
                'email_verified_at' => now(),
            ]);

            Auth::login($user);

            return redirect(env('FRONTEND_URL') . '/dashboard');

        } catch (\Exception $e) {
            return redirect(env('FRONTEND_URL') . '/login')->withErrors([
                'message' => 'Something went wrong',
            ]);
        }

    }
}
