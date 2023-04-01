<?php

// Base exception that all API exceptions should derive from
class ApiException extends Exception
{
    public function __construct($message = "API exception", $code = 0)
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
    public function __construct($message = "Bad request", $code = 1)
    {
        parent::__construct($message, $code);
    }
}

class UnauthorizedError extends ApiException
{
    public function __construct($message = "Unauthorized", $code = 2)
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
    public function __construct($message = "Object not found", $code = 3)
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
    public function __construct($message = "Rate limit exceeded", $code = 10)
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
    public function __construct($message = "Request contained an invalid value for a parameter", $code = 4)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 400;
    }
}

//---------------------------------------------------------------------------
// Exceptions that shouldn't happen unless someone is futzing with something
// they shouldn't be, or there's an unexpected problem.

class UnexpectedError extends ApiException
{
    public function __construct($message = "Unexpected error", $code = 5)
    {
        parent::__construct($message, $code);
    }
}

class InvalidAPI extends ApiException
{
    public function __construct($message = "Invalid API path", $code = 6)
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
    public function __construct($message = "API endpoint doesn't support this method", $code = 7)
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
    public function __construct($message = "API not implemented", $code = 8)
    {
        parent::__construct($message, $code);
    }
}

class ServerError extends ApiException
{
    public function __construct($message = "An unhandled error happened on the server", $code = 9)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode()
    {
        return 500;
    }
}
