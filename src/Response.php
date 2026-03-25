<?php

declare( strict_types = 1 );

namespace Ocolin\Slack;

class Response
{

    /**
     * @var bool Slacks error response. True for success, false for errors.
     */
    public bool $ok;

    /**
     * @var int HTTP status code. Either 200 OK, or 429 for rate limiting.
     */
    public int $status;

    /**
     * @var ?object API response data.
     */
    public ?object $body = null;

    /**
     * @var string|null Error messaging used if OK is false.
     */
    public ?string $error = null;

    /**
     * @var string|null Used for warning messages.
     */
    public ?string $warning = null;

    /**
     * @var int|null How long to wait if rate limiting occurs.
     */
    public ?int $retry_after = null;
}