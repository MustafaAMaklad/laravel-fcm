<?php

namespace MustafaAMaklad\Fcm\Tests\Feature;

use Illuminate\Support\Facades\Notification;
use MustafaAMaklad\Fcm\Channels\FcmChannel;
use MustafaAMaklad\Fcm\Tests\TestCase;

class FcmChannelTest extends TestCase
{
    /** @test */
    public function it_extends_channel()
    {
        $channel = Notification::channel('fcm');

        $this->assertInstanceOf(FcmChannel::class, $channel);
    }
}