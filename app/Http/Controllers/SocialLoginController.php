<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    protected $allowedProviders = ['google', 'apple'];

    public function redirect($provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            abort(403);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            abort(404);
        }

        $socialUser = Socialite::driver($provider)->stateless()->user();
        $email = $socialUser->getEmail();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('index')->with('error', 'Unable to obtain a valid email address from provider.');
        }

        $existingUser = User::where(function ($query) use ($provider, $socialUser) {
                $query->where('provider', $provider)
                    ->where('provider_id', $socialUser->getId());
            })
            ->orWhere('email', $email)
            ->first();

        if ($existingUser) {
            if (!$existingUser->provider || !$existingUser->provider_id) {
                $existingUser->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'profile' => $socialUser->getAvatar() ?? $existingUser->profile,
                ]);
            }

            Auth::login($existingUser);

            return redirect()->route('index');
        }

        $user = User::create([
            'name' => $socialUser->getName() ?? 'User',
            'email' => $email,
            'password' => bcrypt(Str::random(16)),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'profile' => $socialUser->getAvatar(),
        ]);

        Auth::login($user);

        return redirect()->route('index');
    }
}
