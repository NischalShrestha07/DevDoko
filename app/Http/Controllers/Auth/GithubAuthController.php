<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'GitHub sign-in failed. Please try again.');
        }

        $user = User::where('github_id', $githubUser->getId())->first();

        if (! $user) {
            $email = $githubUser->getEmail() ?: $githubUser->getNickname().'@users.noreply.github.com';

            if (User::where('email', $email)->exists()) {
                return redirect()->route('login')->with('error', 'An account already exists with this email. Log in with your password to continue.');
            }

            $user = User::create([
                'name' => $githubUser->getName() ?: $githubUser->getNickname(),
                'email' => $email,
                'password' => Str::password(32),
                'role' => 'user',
                'github_id' => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } else {
            $user->update([
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Signed in with GitHub!');
    }
}
