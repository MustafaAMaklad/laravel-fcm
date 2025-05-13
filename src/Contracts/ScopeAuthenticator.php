<?php

namespace Src\Contracts;

interface ScopeAuthenticator
{
    public function getAccessToken(string $scope): string;
}