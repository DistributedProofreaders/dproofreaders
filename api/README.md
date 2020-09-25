# API Development

This documentation is for developers to extend the current APIs.

For the API user's guide, see [USERS_GUIDE.md](USERS_GUIDE.md).

For the administrative documentation on the APIs, see
[SETUP/API.md](../SETUP/API.md).

## Design

The DP API uses a RESTful design, where URLs uniquely access resources.

### Routing work to functions

URLs are mapped to methods and functions via the ApiRouter. After handling
authentication and rate limiting, the router is used to:

* ensure that the requested URL path exists
* the URL accepts the requested method
* all parameters passed in the URL are valid (via the use of helpers)

After all of those pass, the validated data and any query parameters are
handed off to the specified function.

#### Adding a new RESTful URL

To add a new RESTful URL, create a function that will handle requests
for the endpoint with a function signature `($method, $data, $query_params)`.
The function could handle multiple methods (GET and POST) or you could
use separate functions for each method. The function should return an
array that will be rendered to the client in JSON.

For example:

```php
function api_dogs($method, $data, $query_params)
{
    return [ "poodle", "labrador" ];
}
```

Then add the RESTful URL, method, and function name to the router with:
```php
$router->add_route("<<METHOD>>", "<<URL>>>", "<<FUNCTION>>");
```

For example:
```php
$router->add_route("GET", "/v1/dogs", "api_dogs");
```

If the function will handle multiple method types, add a new route to
the same URL and function.

If the URL will contain parameters as well, specify them in the URL
starting with `:`. For example: `/v1/dogs/:breed`. Before you add this
URL to the router, you need to add a validator that will take the value
of the parameter, validate it, and return something for the consuming
function to use (usually a string or an object created from the string).

For example:
```php
function validate_breed($breed)
{
    return new DogBreed($breed);
}

function api_dog($method, $data, $query_params)
{
    return [
        "name" => $data[":breed"]->name,
        "size" => $data[":breed"]->size,
    ];
}

$router->add_validator(":breed", "validate_breed");
$router->add_route("GET", "/v1/dog/:breed", "api_dog");
```

### URI-handling function responsibilities

The router handles validating parameters in the URL, but it does not handle
authorization which is the responsibility of the URI-handling function.

If a URI-handling function detects an error, it should throw an exception.

### Error handling via exceptions

The API handles all errors via exceptions. Exceptions are caught and converted
into JSON error messages and HTTP error codes as appropriate. See
`exceptions.inc` for a list of useful throwable exceptions.

## Documentation

The `dp-openapi.yaml` file should always contain the definitive OpenAPI on what
is currently implemented. This file is best edited via
[Swagger Editor](https://editor.swagger.io/). Indeed, that's a great place
to test out the APIs as well.

## References

Some useful references on API design and implementation:

* https://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api
* https://developer.github.com/v3/
