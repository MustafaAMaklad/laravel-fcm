<?php 

namespace MustafaAMaklad\Fcm\Traits;

trait ShouldSync
{
    protected bool $sync = true;

    public function shouldSync(): bool
    {
        return $this->sync;
    }

    public function dontSync(): self
    {
        $this->sync = false;

        return $this;
    }

    public function sync(): self
    {
        $this->sync = true;

        return $this;
    }
}