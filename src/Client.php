<?php

/**
 * Pushover: A simple PHP client for Slack API services.
 *
 * @author  Colin Miller <ocolin@staff.cruzio.com>
 * @copyright Copyright(c) 2025 Colin Miller
 * @license MIT (opensource.org)
 * @version 3.0
 */

declare( strict_types = 1 );

namespace Ocolin\Slack;

use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private HTTP $http;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $token API token. Leave blank to use SLACK_TOKEN
     * environment variable.
     * @param bool $verify Verify SSL connection. Defaults to false.
     * @param int $timeout Set HTTP timeout. Defaults to 20 seconds.
     */
    public function __construct(
        ?string $token = null,
           bool $verify = false,
            int $timeout = 20,
    )
    {
        $this->http = new HTTP(
              token: $token,
             verify: $verify,
            timeout: $timeout,
        );
    }



/* GET REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $method SLack API method to call.
     * @param array<string,string|int|float>|object $query GET Query parameters.
     * @return object Slack API response object.
     * @throws GuzzleException
     */
    public function get( string $method, array|object $query = [] ): object
    {
        return $this->http->get( method: $method, query: $query )->body;
    }


/* POST REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $method SLack API method to call.
     * @param array<string,string|int|float>|object $query GET query parameters.
     * @param array<string,string|int|float>|object $params POST body parameters.
     * @return object Slack API response object.
     * @throws GuzzleException
     */
    public function post(
              string $method,
        array|object $query = [],
        array|object $params = []
    ): object
    {
        return $this->http->post(
            method: $method,
            params: $params,
             query: $query,
        )->body;
    }
}