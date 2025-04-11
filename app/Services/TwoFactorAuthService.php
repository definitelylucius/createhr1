<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SendTwoFactorCode;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthService
{
    public function generateAndSendCode(User $user)
    {
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
    }

    public function enableTwoFactorAuth(User $user)
    {
        $google2fa = new Google2FA();
        $user->update([
            'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    public function disableTwoFactorAuth(User $user)
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }
}