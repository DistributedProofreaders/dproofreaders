<?php

// Base exception that all API exceptions should derive from
class ApiException extends Exception
{
    function getStatusCode()
    {
        return 400;
    }
}

//---------------------------------------------------------------------------
// Common / expected errors we include in the documentation

class BadRequest extends ApiException {}

class UnauthorizedError extends ApiException
{
    function __construct()
    {
        parent::__construct("Unauthorized");
    }

    function getStatusCode()
    {
        return 401;
    }
}

class NotFoundError extends ApiException
{
    function __construct($message="Object not found")
    {
        parent::__construct($message);
    }

    function getStatusCode()
    {
        return 404;
    }
}

class RateLimitExceeded extends ApiException
{
    function getStatusCode()
    {
        return 429;
    }
}

//---------------------------------------------------------------------------
// Exceptions that shouldn't happen unless someone is futzing with something
// they shouldn't be, or there's an unexpected problem.

class UnexpectedError extends ApiException {}

class InvalidAPI extends ApiException
{
    function __construct()
    {
        parent::__construct("Invalid API path");
    }

    function getStatusCode()
    {
        return 404;
    }
}

class MethodNotAllowed extends ApiException
{
    function __construct()
    {
        parent::__construct("API endpoint doesn't support this method");
    }

    function getStatusCode()
    {
        return 405;
    }
}

class NotImplementedError extends ApiException
{
    function __construct()
    {
        parent::__construct("API not implemented");
    }
}

class ServerError extends ApiException
{
    function __construct()
    {
        parent::__construct("An unhandled error happened on the server");
    }

    function getStatusCode()
    {
        return 500;
    }
}
