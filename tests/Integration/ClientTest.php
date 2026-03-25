<?php

declare( strict_types = 1 );

namespace Ocolin\Slack\Tests\Integration;

use Ocolin\Slack\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private static Client $client;

    public function testGetGood() : void
    {
        $output = self::$client->get(
            method: 'users.conversations'
        );
        $this->assertIsObject( $output );
        $this->assertSame( true, $output->ok );
        $this->assertSame( 200, $output->status );
        $this->assertIsObject( $output->body );
    }

    public function testPostGood() : void
    {
        $output = self::$client->post(
            method: 'chat.postMessage',
            params: [
                'channel' => 'C04KULNT1EZ',
                'text'    => 'This is a PHPUnit test'
            ]
        );
        $this->assertIsObject( $output );
        $this->assertSame( true, $output->ok );
        $this->assertSame( 200, $output->status );
        $this->assertIsObject( $output->body );
    }

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }
}