<?php

declare( strict_types = 1 );

namespace Ocolin\Slack;

use Exception;
use stdClass;

class Slack
{
    /**
     * @var HTTP Guzzle based HTTP handler.
     */
    public readonly HTTP $http;

    /**
     * @var object Object containing API schema data.
     */
    public readonly object $schema;


/*
---------------------------------------------------------------------------- */

    /**
     * @param string|null $token Slack API token.
     * @param string|null $url Slack API base URL.
     * @throws Exception
     */
    public function __construct( ?string $token = null, ?string $url = null )
    {
        $this->http = new HTTP( token: $token, url: $url );
        $this->schema = (object)json_decode(
            (string)file_get_contents( filename: __DIR__ . "/slack.json" ))
        ;
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @param string $group Name of API group.
     * @param string $method Name of API method.
     * @param array<string, mixed> $data Data to send to API end point.
     * @return object Response object from Slack API.
     */
    public function call( string $group, string $method, array $data ) : object
    {
        $group = strtolower( string: $group );

        if( !property_exists(
            object_or_class: $this->schema->groups, property: $group
        )) {
            return self::error( message: "Group {$group} not found" );
        }

        if( !property_exists(
            object_or_class: $this->schema->groups->$group, property: $method
        )) {
            return self::error( message: "Method {$group}.{$method} not found" );
        }

        $uri = "{$group}.{$method}";
        $http_method = $this->schema->groups->$group->$method->method;

        $response = $this->http->$http_method( uri: $uri, data: $data );

        return $response->body;
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @param string $message Error message.
     * @return stdClass Object containing error message.
     */
    protected static function error( string $message ) : object
    {
        $o = new stdClass();
        $o->ok = false;
        $o->error = $message;

        return $o;
    }
}