# Slack
Basic PHP Slack Client

## Environment Variables

This library depends on two environment variables to be set. They can be set the following ways:

- Settings the variables in the calling library.
- Setting the parameters in the constructor of this library
- Setting the parameters in a .env file of this library, See .env.example

### Parameters

```
$_ENV['SLACK_BASE_URL'] - The API URL
$_ENV['SLACK_TOKEN'] - Oauth Bearer token
```

## Creating Instance

### Using environment file

```
use Ocolin\Slack\Slack;

$slack = new Slack();
```

### Providing arguments

```
use Ocolin\Slack\Slack;

$slack = new Slack(
    token: 'abcde',
    url: 'https://slack.com/api/'
);
```

## Making an API call

Making a call requires 3 parameters:

- group - This is the part of the API URI which is grouped into categories.
- method - This is the part of the API URI that determines the method of the API group.
- data - The parameters being sent to the API either via POST or GET params

In the URI, the group and method are seperated by a period. In this library they are specified seperately

### Example

The Api call 'chat.postMessage' consists of the 'chat' group and the 'postMessage' method.
