<?php

namespace MustafaAMaklad\Fcm\Channels;

use Illuminate\Notifications\Notification;
use MustafaAMaklad\Fcm\Contracts\FcmServiceClient;

class FcmChannel
{
    public function __construct(protected FcmServiceClient $fcmServiceClient)
    {
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        /** @var \MustafaAMaklad\Fcm\Contracts\FcmNotification $notification */

        $message = $notification->toFcm($notifiable);

        $this->fcmServiceClient->send($message);
    }
}