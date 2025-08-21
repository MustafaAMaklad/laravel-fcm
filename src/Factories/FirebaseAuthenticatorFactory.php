<?php

namespace MustafaAMaklad\Fcm\Factories;

use MustafaAMaklad\Fcm\Contracts\ScopeAuthenticator;
use MustafaAMaklad\Fcm\Services\FirebaseAuthenticator;

class FirebaseAuthenticatorFactory
{
    public function create(): ScopeAuthenticator
    {
        return new FirebaseAuthenticator(config('firebase.credentials'));
    }
}
