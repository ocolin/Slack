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

class Response
{
    /**
     * @var int HTTP status code.
     */
    public int $status;

    /**
     * @var string HTTP status message.
     */
    public string $status_message;

    /**
     * @var array<string[]> HTTP headers.
     */
    public array $headers;

    /**
     * @var object API response output.
     */
    public object $body;
}