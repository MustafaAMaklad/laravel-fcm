<?php

namespace MustafaAMaklad\Fcm\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FcmToken extends Model
{
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive(Builder $query): Builder
    {
        // return $query->where('active', true);
        return $query;
    }
}