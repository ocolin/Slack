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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\GlobalType\GT;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var Client Guzzle HTTP client.
     */
    private Client $client;

    /**
     * Slack API URL
     */
    private const API_URL = 'https://slack.com/api/';


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $token API Auth bearer token.
     * @param bool $verify Verify SSL connection to server.
     * @param int $timeout HTTP timeout to server.
     */
    public function __construct(
        ?string $token = null,
           bool $verify = false,
            int $timeout = 20,
    )
    {
        $token = $token ?? GT::envString( name: 'SLACK_TOKEN' );

        $this->client = new Client([
            'base_uri' => self::API_URL,
            'timeout'  => $timeout,
            'verify'   => $verify,
            'http_errors' => false,
            'http_timeout' => $timeout,
            'headers' => [
                'Authorization' => "Bearer $token",
                'Content-Type'  => 'application/json; charset=utf-8',
                'User-Agent'    => 'Ocolin Slack PHP Client 3.0',
            ]
        ]);
    }


/* HTTP GET REQUESTS
----------------------------------------------------------------------------- */

    /**
     * @param string $method Slack API method to call.
     * @param array<string,string|int|float>|object $query Query parameters if any.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function get(
              string $method,
        array|object $query = [] ) : Response
    {
        $method = $this->trim_Method( method: $method );

        return $this->format_Response( response: $this->client->get(
            uri: $method, options: [ 'query' => $query ])
        );
    }



/* HTTP POST REQUESTS
----------------------------------------------------------------------------- */

    /**
     * @param string $method Slack API method to call.
     * @param array<string,string|int|float>|object $params POST body parameters.
     * @param array<string,string|int|float>|object $query URL query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function post(
              string $method,
        array|object $params = [],
        array|object $query = []
    ) : Response
    {
        $method = $this->trim_Method( method: $method );

        return $this->format_Response( response: $this->client->post(
            uri: $method, options: [
                'query' => $query,
                'json' => $params,
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                ]
            ]
        ));
    }



/* FORMAT API HTTP RESPONSE
----------------------------------------------------------------------------- */

    /**
     * Format Guzzle HTTP response to an object.
     *
     * @param ResponseInterface $response Guzzle HTTP response.
     * @return Response Formatted API response.
     */
    public function format_Response( ResponseInterface $response ): Response
    {
        $output = new Response();
        $output->status         = $response->getStatusCode();
        $output->headers        = $response->getHeaders();
        $output->status_message = $response->getReasonPhrase();
        $output->body           = (object)json_decode(
            json: $response->getBody()->getContents()
        );

        return $output;
    }



/* REMOVE DUPLICATE SLASHES IN URL
----------------------------------------------------------------------------- */

    /**
     * If both the base URL and the end point path have root slash, remove
     * the one from end point to eliminate a double slash in the final URL.
     *
     * @param string $method Method being sent to API server.
     * @return string Formatted method.
     */
    private function trim_Method( string $method ) : string
    {
        if(
            str_starts_with( haystack: $method, needle: '/' ) AND
            str_ends_with( haystack: self::API_URL, needle: '/' )
        ) {
            return trim( string: $method, characters: '/' );
        }

        return $method;
    }
}