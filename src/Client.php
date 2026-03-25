<?php

declare( strict_types = 1 );

namespace Ocolin\Slack;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Ocolin\GlobalType\ENV;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class Client
{
    /**
     * @var GuzzleClient Guzzle HTTP client.
     */
    private GuzzleClient $http;

    /**
     * URL of Slack API.
     */
    private const string API_URL = 'https://slack.com/api/';

    /**
     * Default HTTP options.
     */
    private const array DEFAULTS = [
                'verify'  => true,
                'timeout' => 20,
        'connect_timeout' => 20,
    ];


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $token Slack API Token.
     * @param array<string, string|int|float|bool> $options HTTP Client options.
     * @param GuzzleClient|null $http Used for mocking/testing.
     */
    public function __construct(
             ?string $token = null,
             array $options = [],
        ?GuzzleClient $http = null,
    ) {
        $token = $token ?? ENV::getStringNull( name: 'SLACK_TOKEN' );
        $config = array_merge( self::DEFAULTS, $options );

        if( $token === null ) {
            throw new InvalidArgumentException( message: "Missing Slack API token." );
        }

        $this->http = $http ?? new GuzzleClient([
            'base_uri'          => self::API_URL,
            'timeout'           => $config['timeout'],
            'connect_timeout'   => $config['connect_timeout'],
            'verify'            => $config['verify'],
            'http_errors'       => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json; charset=utf-8',
                'User-Agent'    => 'Ocolin Slack PHP Client 4.0',
            ]
        ]);
    }



/* GET REQUEST
----------------------------------------------------------------------------- */

    /**
     * Sent GET request to Slack.
     *
     * @param string $method Slack API method.
     * @param array<string, string>|object $query Any GET query parameters.
     * @return Response API response data.
     * @throws GuzzleException
     */
    public function get( string $method, array|object  $query = [] ) : Response
    {
        if( is_object( $query )) { $query = (array)$query; }
        $method = ltrim( string: $method, characters: '/' );

        return self::buildResponse(
            guzzle: $this->http->get(
                uri: $method, options: [ 'query' => $query ]
            )
        );
    }



/* POST REQUEST
----------------------------------------------------------------------------- */

    /**
     * Send POST requests to Slack.
     *
     * @param string $method Slack API method.
     * @param array<string, mixed>|object $params POST body parameters.
     * @param array<string, string>|object $query GET query parameters.
     * @return Response API response object.
     * @throws GuzzleException
     */
    public function post(
              string $method,
        array|object $params = [],
        array|object $query = []
    ) : Response
    {
        if( is_object( $params )) { $params = (array)$params; }
        if( is_object( $query ))  { $query  = (array)$query; }
        $method = ltrim( string: $method, characters: '/' );

        return self::buildResponse(
            guzzle: $this->http->post(
                uri: $method, options: [ 'json' => $params, 'query' => $query ]
            )
        );
    }



/* BUILD API RESPONSE OBJECT
----------------------------------------------------------------------------- */

    /**
     * This function converts the Guzzle HTTP data into a DTO object.
     *
     * @param ResponseInterface $guzzle Guzzle HTTP response object.
     * @return Response API response object.
     */
    private static function buildResponse( ResponseInterface $guzzle ) : Response
    {
        $response = new Response();
        $response->status = $guzzle->getStatusCode();
        $body = json_decode( json: $guzzle->getBody()->getContents());

        if( $body instanceof stdClass === false ) {
            $response->ok = false;
            $response->error = "Unable to decode JSON.";

            return $response;
        }

        $response->ok = (bool)$body->ok;
        $response->error = $body->error ?? null;
        $response->warning = $body->warning ?? null;

        if( $response->status === 429 ) {
            $headers = $guzzle->getHeader( 'Retry-After' );
            $response->retry_after = !empty($headers) ? (int)$headers[0] : null;
        }

        $response->body = $body;

        return $response;
    }
}