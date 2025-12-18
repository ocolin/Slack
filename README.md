# PHP Slack Client

## Description

This is a very small light-weight PHP client for querying the Slack API server. It has no default methods and is just a simple HTTP interface to make your own calls with.

It is meant to be used in conjunction with the API docs found here:

https://docs.slack.dev/reference/methods/

You need to simply paste the desired Slack API method, include any required query parameters, POST body object (if making a POST request), and use the get() or post() method depending on what the Slack method requires.

## Installation

```composer require ocolin/slack```

## Requirements

In order to use the tool you must provide a Bearer token in order to authenticate with the Slack API server. There are two ways this can be done.

- Supplying a token to the constructor
- Using an environment variable.

## Instantiating

### Constructor arguments

- token - Slack authentication token. Optional if using environment variable.
- verify - Verify the SSL connection. Defaults to false.
- timeout - Set HTTP timeout in seconds. Defaults to 20 seconds.

### EXAMPLE: Using environment variable

```php

$_ENV['SLACK_TOKEN'];
$slack = new Ocolin\Slack\Client();
```

### EXAMPLE: Using all constructor arguments

```php
$slack = new Ocolin\Slack\Client(
      token: 'yourtokengoeshere',
     verify: true,
    timeout: 10
);
```

## Making API calls.

Once you have your class instantiated, you are ready to make calls. SLack API takes two types of requests, POST and GET. The Post function has parameters for both query and body parameters as some methods may use both at the same time. Both GET and POST parameters can be an associative array or an object.

### EXAMPLE: GET Request

```php

$reqponse = $slack->get( 
    method: 'emoji.list'
    query: [ 'include_categories' => false ]
);
```

Expected output:

```php
stdClass Object
(
    [ok] => 1
    [emoji] => stdClass Object
        (
            [bowtie] => https://emoji.slack-edge.com/T04KULNSASH/bowtie/f3ec6f2bb0.png
            [squirrel] => https://emoji.slack-edge.com/T04KULNSASH/squirrel/465f40c0e0.png
            [glitch_crab] => https://emoji.slack-edge.com/T04KULNSASH/glitch_crab/db049f1f9c.png
            [piggy] => https://emoji.slack-edge.com/T04KULNSASH/piggy/b7762ee8cd.png
            [cubimal_chick] => https://emoji.slack-edge.com/T04KULNSASH/cubimal_chick/85961c43d7.png
            [dusty_stick] => https://emoji.slack-edge.com/T04KULNSASH/dusty_stick/6177a62312.png
            [slack] => https://emoji.slack-edge.com/T04KULNSASH/slack/7d462d2443.png
            [pride] => https://emoji.slack-edge.com/T04KULNSASH/pride/56b1bd3388.png
            [thumbsup_all] => https://emoji.slack-edge.com/T04KULNSASH/thumbsup_all/50096a1020.png
            [slack_call] => https://emoji.slack-edge.com/T04KULNSASH/slack_call/b81fffd6dd.png
            [shipit] => alias:squirrel
            [white_square] => alias:white_large_square
            [black_square] => alias:black_large_square
            [simple_smile] => https://a.slack-edge.com/80588/img/emoji_2017_12_06/apple/simple_smile.png
        )

    [cache_ts] => 1560987000.000000
)
```

### EXAMPLE: Post Request

In order to test parameters, we are invoking a test error.

```php

$response = $slack->post(
    method: 'api.test',
    params: [ 'error' => 'This is a test' ]
);
```

Expected output:

```php
stdClass Object
(
    [ok] => 
    [error] => This is a test
    [args] => stdClass Object
        (
            [error] => This is a test
            [token] => lkjskjkjsdf-sdfgdfdkq60G4K8
        )

    [warning] => missing_charset
    [response_metadata] => stdClass Object
        (
            [warnings] => Array
                (
                    [0] => missing_charset
                )

        )

)
```