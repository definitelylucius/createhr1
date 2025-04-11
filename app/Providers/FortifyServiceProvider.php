<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  
     public function boot()
     {
         Fortify::authenticateThrough(function ($request) {
             return array_filter([
                 config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                 RedirectIfTwoFactorAuthenticatable::class,
                 AttemptToAuthenticate::class,
                 PrepareAuthenticatedSession::class,
             ]);
         });
     
         Fortify::twoFactorChallengeView(function () {
             return view('auth.two-factor-challenge');
         });
     }
}
