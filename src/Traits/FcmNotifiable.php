<?php

namespace Src\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Src\Models\FcmToken;

trait FcmNotifiable
{
    public function fcmTokens(): MorphMany
    {
        return $this->morphMany(FcmToken::class, 'tokenable');
    }

    public function routeNotificationForFcm(): ?string
    {
        return $this->fcmTokens()->active()->latest()->first()?->token ?? null;
    }
}