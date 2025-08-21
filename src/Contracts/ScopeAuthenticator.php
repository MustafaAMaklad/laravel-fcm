<?php

namespace MustafaAMaklad\Fcm\Contracts;

interface ScopeAuthenticator
{
    public function getAccessToken(string $scope): string;
}