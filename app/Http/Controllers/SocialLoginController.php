<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    protected $allowedProviders = ['google', 'facebook', 'apple'];

    public function redirect($provider)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            abort(403);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            abort(404);
        }
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            Auth::login($socialAccount->user);
            return response()->json(['sucees' => 'user registered successfully']);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), 
            ]);
        }
        $user->socialAccounts()->create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
