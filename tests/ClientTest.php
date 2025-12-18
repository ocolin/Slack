<?php

declare( strict_types = 1 );

namespace Ocolin\Slack\Tests;

use Ocolin\Slack\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public static Client $client;

    public function testGet(): void
    {
        $output = self::$client->get(
            method: 'emoji.list', query: [ 'include_categories' => false ]
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'ok', $output );
        self::assertEquals( 1, $output->ok );
        //print_r( $output );
    }

    public function testPost() : void
    {
        $output = self::$client->post(
            method: 'api.test',
            //params: [ 'error' => 'This is a test']
        );
        //print_r( $output );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'ok', $output );
        self::assertEquals( 1, $output->ok );
    }

    public function testPostError() : void
    {
        $output = self::$client->post(
            method: 'api.test',
        params: [ 'error' => 'This is a test']
        );
        //print_r( $output );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'ok', $output );
        self::assertEquals( 0, $output->ok );
    }


    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }
}