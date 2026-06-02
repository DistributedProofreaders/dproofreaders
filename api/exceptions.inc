<?php

/**
 * Base exception that all API exceptions should derive from.
 *
 * These exception codes range from 1 to 99
 */
class ApiException extends Exception
{
    public function __construct(
        string $message = "API exception",
        int $code = 1,
        public readonly int $status_code = 400
    ) {
        parent::__construct($message, $code);
    }
}

//---------------------------------------------------------------------------
// Common / expected errors we include in the documentation

class BadRequest extends ApiException
{
    public function __construct(string $message = "Bad request", int $code = 2)
    {
        parent::__construct($message, $code, 400);
    }
}

class UnauthorizedError extends ApiException
{
    public function __construct(string $message = "Unauthorized", int $code = 3)
    {
        parent::__construct($message, $code, 401);
    }
}

class NotFoundError extends ApiException
{
    public function __construct(string $message = "Object not found", int $code = 4)
    {
        parent::__construct($message, $code, 404);
    }
}

class RateLimitExceeded extends ApiException
{
    public function __construct(string $message = "Rate limit exceeded", int $code = 5)
    {
        parent::__construct($message, $code, 429);
    }
}

class InvalidValue extends ApiException
{
    public function __construct(string $message = "Request contained an invalid value for a parameter", int $code = 6)
    {
        parent::__construct($message, $code, 400);
    }
}

class InvalidParam extends ApiException
{
    public function __construct(string $message = "Request contained an invalid parameter", int $code = 12)
    {
        parent::__construct($message, $code, 400);
    }
}

class ForbiddenError extends ApiException
{
    public function __construct(string $message = "Forbidden", int $code = 7)
    {
        parent::__construct($message, $code, 403);
    }
}

//---------------------------------------------------------------------------
// Exceptions that shouldn't happen unless someone is futzing with something
// they shouldn't be, or there's an unexpected problem.

class UnexpectedError extends ApiException
{
    public function __construct(string $message = "Unexpected error", int $code = 7)
    {
        parent::__construct($message, $code);
    }
}

class InvalidAPI extends ApiException
{
    public function __construct(string $url, ?string $part, array $children, int $code = 8)
    {
        if (is_null($part)) {
            $err = "is missing a part";
        } else {
            $err = "has no part $part";
        }

        if (!empty($children)) {
            $dym = " Valid child parts: " .implode(", ", $children) . ".";
        } else {
            $dym = "";
        }
        $message = "API endpoint $url $err.$dym";
        parent::__construct($message, $code, 404);
    }
}

class MethodNotAllowed extends ApiException
{
    public function __construct(string $url, string $invalid, array $valids, $code = 9)
    {
        $valid = implode(", ", $valids);
        $message = "API endpoint $url: Method $invalid not supported. Valid methods: $valid.";
        parent::__construct($message, $code, 405);
    }
}

class NotImplementedError extends ApiException
{
    public function __construct(string $message = "API not implemented", int $code = 10)
    {
        parent::__construct($message, $code);
    }
}

class ServerError extends ApiException
{
    public function __construct(string $message = "An unhandled error happened on the server", int $code = 11)
    {
        parent::__construct($message, $code, 500);
    }
}
