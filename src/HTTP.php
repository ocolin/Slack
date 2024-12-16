<?php

declare( strict_types = 1 );

namespace Ocolin\Slack;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\Env\EasyEnv;
use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class HTTP
{
    /**
     * @var string Slack API Base URL.
     */
    public string $base_uri;

    /**
     * @var Client Guzzle HTTP client.
     */
    public Client $client;

    /**
     * @var string[] Request headers.
     */
    public array $headers;


/*
---------------------------------------------------------------------------- */

    /**
     * @param Client|null $client Guzzle optional client.
     * @param string|null $token Slack authentication token.
     * @param string|null $url Base URL of Slack API.
     * @throws Exception
     */
    public function __construct(
        ?Client $client = null,
        ?string $token  = null,
        ?string $url    = null
    )
    {
        self::loadEnv( token: $token );
        $this->base_uri  = $url ?? $_ENV['SLACK_BASE_URL'];
        $this->headers = self::default_Headers();
        $this->client = $client ?? new Client([
            'base_uri'      => $this->base_uri,
            'verify'        => false,
            'http_errors'   => false,
        ]);
    }


/*
---------------------------------------------------------------------------- */

    /**
     * @param string $uri
     * @param array<string, string> $data Query parameters
     * @return object Response object.
     * @throws GuzzleException
     */
    public function get( string $uri, array $data = [] ) : object
    {
        $options = [
            'headers' => $this->headers,
            'query' => Query::build( $data )
        ];

        $request = $this->client->request(
            method: 'GET',
            uri: $this->base_uri . $uri,
            options: $options
        );

        return self::returnResults( request: $request );
    }


/*
---------------------------------------------------------------------------- */

    /**
     * @param string $uri URI of API request
     * @param array<string,mixed>|object $data Body of API request.
     * @return object Response object from API.
     * @throws GuzzleException
     */
    public function post( string $uri, array|object $data = [] ) : object
    {
        $request = $this->client->request(
            method: 'POST',
            uri: $this->base_uri . $uri,
            options: [
                'headers' => $this->headers,
                'body' => json_encode( value: $data )
            ]
        );

        return self::returnResults( request: $request );
    }



/*
---------------------------------------------------------------------------- */

    private static function returnResults(  ResponseInterface $request ) : object
    {
        $response = new stdClass();
        $response->status = $request->getStatusCode();
        $response->status_message = $request->getReasonPhrase();
        $response->body = json_decode( json: (string)$request->getBody()->getContents());

        return $response;
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @param string|null $token Authentication token.
     * @return void
     * @throws Exception
     */
    public function loadEnv( ?string $token = null, ?string $url = null ) : void
    {
        if( $token !== null ) {
            $_ENV['SLACK_TOKEN'] = $token;
        }

        if( $url !== null ) {
            $_ENV['SLACK_BASE_URL'] = $url;
        }

        if( empty( $_ENV['SLACK_TOKEN'] ) OR empty( $_ENV['SLACK_BASE_URL'] )) {
            EasyEnv::loadEnv( path: __DIR__ . '/../.env', append: true );
        }
    }



/* GENERATE DEFAULT HEADERS
---------------------------------------------------------------------------- */

    /**
     * @return string[] Array of headers.
     */
    private static function default_Headers() : array
    {
        return [
            'Authorization' => 'Bearer ' . $_ENV['SLACK_TOKEN'],
            'Content-type'  => 'application/json; charset=utf-8',
            'User-Agent'    => 'Slack API Client 1.0',
        ];
    }
}