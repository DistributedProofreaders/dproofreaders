<?php

/**
 * Base exception that all API exceptions should derive from.
 *
 * These exception codes range from 1 to 99
 */
class ApiException extends Exception
{
    public function __construct(string $message = "API exception", int $code = 1)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 400;
    }
}

//---------------------------------------------------------------------------
// Common / expected errors we include in the documentation

class BadRequest extends ApiException
{
    public function __construct(string $message = "Bad request", int $code = 2)
    {
        parent::__construct($message, $code);
    }
}

class UnauthorizedError extends ApiException
{
    public function __construct(string $message = "Unauthorized", int $code = 3)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 401;
    }
}

class NotFoundError extends ApiException
{
    public function __construct(string $message = "Object not found", int $code = 4)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 404;
    }
}

class RateLimitExceeded extends ApiException
{
    public function __construct(string $message = "Rate limit exceeded", int $code = 5)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 429;
    }
}

class InvalidValue extends ApiException
{
    public function __construct(string $message = "Request contained an invalid value for a parameter", int $code = 6)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 400;
    }
}

class ForbiddenError extends ApiException
{
    public function __construct(string $message = "Forbidden", int $code = 7)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 403;
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
    public function __construct(string $message = "Invalid API path", int $code = 8)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 404;
    }
}

class MethodNotAllowed extends ApiException
{
    public function __construct(string $message = "API endpoint doesn't support this method", int $code = 9)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 405;
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
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 500;
    }
}
