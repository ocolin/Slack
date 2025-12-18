<?php

declare( strict_types = 1 );

namespace Ocolin\Slack\Tests;

use Ocolin\Slack\HTTP;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    public static HTTP $http;

    public function testGetGood() : void
    {
        $output = self::$http->get( method: 'emoji.list', query: [ 'include_categories' => false ] );
        //print_r( $output );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'status', $output );
        self::assertObjectHasProperty( 'status_message', $output );
        self::assertObjectHasProperty( 'headers', $output );
        self::assertObjectHasProperty( 'body', $output );

        self::assertIsInt( $output->status );
        self::assertIsString( $output->status_message );
        self::assertIsArray( $output->headers );
        self::assertIsObject( $output->body );

        self::assertEquals( 200, $output->status );
        self::assertEquals( 'OK', $output->status_message );

        self::assertObjectHasProperty( 'ok', $output->body );
        self::assertEquals( 1, $output->body->ok );
    }

    public function testPostGood() : void
    {
        $output = self::$http->post( method: 'api.test' );
        //print_r( $output );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'status', $output );
        self::assertObjectHasProperty( 'status_message', $output );
        self::assertObjectHasProperty( 'headers', $output );
        self::assertObjectHasProperty( 'body', $output );

        self::assertIsInt( $output->status );
        self::assertIsString( $output->status_message );
        self::assertIsArray( $output->headers );
        self::assertIsObject( $output->body );

        self::assertEquals( 200, $output->status );
        self::assertEquals( 'OK', $output->status_message );

        self::assertObjectHasProperty( 'ok', $output->body );
        self::assertEquals( 1, $output->body->ok );
    }

    public static function setUpBeforeClass(): void
    {
        self::$http = new HTTP();
    }
}