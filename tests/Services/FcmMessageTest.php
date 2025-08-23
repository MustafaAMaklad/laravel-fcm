<?php

namespace Tests\Services;

use MustafaAMaklad\Fcm\Services\FcmMessage;
use MustafaAMaklad\Fcm\Tests\TestCase;

class FcmMessageTest extends TestCase
{
    /** @test */
    public function it_builds_an_array(): void
    {
        $message = new FcmMessage();

        $message->toArray();

        $this->assertIsArray($message->toArray());
    }

    /** @test */
    public function it_builds_a_json_string(): void
    {
        $message = new FcmMessage();

        $message->toJson();

        $this->assertJson($message->toJson());
    }

    /** @test */
    public function it_builds_a_message(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->title('Hello')
            ->body('World')
            ->token('token')
            ->id('id')
            ->data(['type' => 'type'])
            ->badge(1)
            ->sound('sound')
            ->apns()
            ->android()
            ->toArray();

        $expected = [
            'message' => [
                'token' => 'token',
                'data' => [
                    'title' => 'Hello',
                    'body' => 'World',
                    'type' => 'type',
                    'id' => 'id'
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => 'Hello',
                                'body' => 'World'
                            ],
                            'badge' => 1,
                            'sound' => 'sound',
                            'content-available' => 1
                        ],
                        'type' => 'type',
                        'id' => 'id',
                    ],
                    'headers' => [
                        'apns-priority' => '10'
                    ],
                ],
                'android' => [
                    'priority' => 'high'
                ]
            ]
        ];

        $this->assertEquals($expected, $built);
    }

    /** @test */
    public function it_completely_overwrites_data(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->data([
                'title' => 'Hello',
                'body' => 'There',
                'type' => 'type',
                'id' => 'id'
            ])
            ->title('Goodbye')
            ->body('Here')
            ->id('di')
            ->type('epyt')
            ->toArray();

        $expected = [
            'title' => 'Goodbye',
            'body' => 'Here',
            'type' => 'epyt',
            'id' => 'di'
        ];

        $this->assertEquals($expected, $built['message']['data']);
    }

    /** @test */
    public function it_partially_overwrites_data(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->data([
                'title' => 'Hello',
                'body' => 'There',
                'type' => 'type',
                'id' => 'id'
            ])
            ->title('Goodbye')
            ->toArray();

        $expected = [
            'title' => 'Goodbye',
            'body' => 'There',
            'type' => 'type',
            'id' => 'id'
        ];

        $this->assertEquals($expected, $built['message']['data']);
    }

    /** @test */
    public function it_completely_overwrites_apns(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->apns([
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Hello',
                            'body' => 'There',
                        ],
                        'badge' => 1,
                        'sound' => 'sound',
                        'content-available' => 1
                    ],
                    'type' => 'type',
                    'id' => 'id'
                ],
                'headers' => [
                    'apns-priority' => '10'
                ],

            ])
            ->apns([
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Goodbye',
                            'body' => 'Here',
                        ],
                        'badge' => 0,
                        'sound' => 'silent',
                        'content-available' => 0
                    ],
                    'type' => 'epyt',
                    'id' => 'di',
                ],
                'headers' => [
                    'apns-priority' => '1'
                ],
            ])
            ->toArray();

        $expected = [
            'aps' => [
                'alert' => [
                    'title' => 'Goodbye',
                    'body' => 'Here',
                ],
                'badge' => 0,
                'sound' => 'silent',
                'content-available' => 0
            ],
            'type' => 'epyt',
            'id' => 'di',
        ];

        $this->assertEquals($expected, $built['message']['apns']['payload']);
    }

    /** @test */
    public function it_partially_overwrites_apns(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->apns([
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Hello',
                            'body' => 'There',
                        ],
                        'badge' => 1,
                        'sound' => 'sound',
                        'content-available' => 1
                    ],
                    'type' => 'type',
                    'id' => 'id'
                ],
                'headers' => [
                    'apns-priority' => '10'
                ],

            ])
            ->apns([
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Goodbye',
                            'body' => 'Here',
                        ],
                    ],
                ],
                'headers' => [
                    'apns-priority' => '1'
                ],
            ])
            ->toArray();

        $expected = [
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => 'Goodbye',
                        'body' => 'Here',
                    ],
                    'badge' => 1,
                    'sound' => 'sound',
                    'content-available' => 1
                ],
                'type' => 'type',
                'id' => 'id'
            ],
            'headers' => [
                'apns-priority' => '1'
            ],
        ];

        $this->assertEquals($expected, $built['message']['apns']);
    }

    /** @test */
    public function it_overwrites_android_config(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->android([
                'priority' => 'high'
            ])
            ->android([
                'priority' => 'normal'
            ])
            ->toArray();

        $this->assertEquals('normal', $built['message']['android']['priority']);
    }

    /** @test */
    public function it_syncs_data_and_apns(): void
    {
        $message = new FcmMessage();

        $built = $message
            ->title('Hello')
            ->body('There')
            ->type('type')
            ->id('id')
            ->toArray();

        $this->assertEquals($built['message']['data']['title'], $built['message']['apns']['payload']['aps']['alert']['title']);
        $this->assertEquals($built['message']['data']['body'], $built['message']['apns']['payload']['aps']['alert']['body']);
        $this->assertEquals($built['message']['data']['type'], $built['message']['apns']['payload']['type']);
        $this->assertEquals($built['message']['data']['id'], $built['message']['apns']['payload']['id']);
    }
}