<?php

namespace App\Factories;

use Src\Contracts\ScopeAuthenticator;
use Src\Services\FirebaseAuthenticator;

class FirebaseAuthenticatorFactory
{
    public function create(): ScopeAuthenticator
    {
        return new FirebaseAuthenticator(config('firebase.credentials'));
    }
}
