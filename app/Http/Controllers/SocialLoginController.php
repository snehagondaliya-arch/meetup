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

        $existingUser = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($existingUser) {
            return redirect()->route('index')
            ->with('error', 'Account already exists with this email or social account.');
        }

        $user = User::create([
            'name' => $socialUser->getName() ?? 'User',
            'email' => $socialUser->getEmail(),
            'password' => bcrypt(Str::random(16)),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'profile' => $socialUser->getAvatar(),
        ]);

        Auth::login($user);

        return redirect('/index');
    }
}
