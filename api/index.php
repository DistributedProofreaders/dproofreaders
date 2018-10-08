<?php
$relPath="../pinc/";
include_once($relPath.'bootstrap.inc');
include_once($relPath.'User.inc');
include_once($relPath.'misc.inc');
include_once('ApiRouter.inc');
include_once('exceptions.inc');
include_once('v1.inc');

// everything is a JSON response
header("Content-Type: application/json");

handle_cors_headers();

if($maintenance)
{
    throw new ApiException("Site is in maintenance mode");
}

if(!$api_enabled)
{
    throw new ApiException("API is not enabled");
}

api();

# ---------------------------------------------------------------------------

function api()
{
    $username = api_authenticate();

    api_rate_limit($username);

    $query_params = $_GET;
    $path = array_get($query_params, "url", "");
    unset($query_params["url"]);

    $router = ApiRouter::get_router();

    api_output_response($router->route($path, $query_params));
}

function api_authenticate()
{
    global $pguser;

    $api_key = @$_SERVER['HTTP_X_API_KEY'];
    if($api_key)
    {
        try {
            $user = User::load_from_api_key($api_key);
        } catch (NonexistentUserException $exception) {
            throw new UnauthorizedError();
        }
        $pguser = $user->username;
    }

    if(!isset($pguser))
    {
        throw new UnauthorizedError();
    }

    return $pguser;
}

function api_rate_limit($key)
{
    global $api_rate_limit;
    global $api_rate_limit_requests_per_window, $api_rate_limit_seconds_in_window;

    if(!$api_rate_limit)
    {
        return;
    }

    $memcache = new Memcached();
    $memcache->addServer('localhost', 11211);

    // increment (or initialize) our count
    $count = $memcache->get("$key:count");
    $count = $count === FALSE ? 0 : $count + 1;
    if($memcache->set("$key:count", $count) === FALSE)
    {
        // if we can't set the value, memcached probably isn't running
        // regardless, we can't enforce rate limiting so return an error
        throw new ServerError();
    }

    // initialize or reset our expire time
    $expire_time = $memcache->get("$key:expire");
    if($count === 0 || $expire_time < time())
    {
        $expire_time = time() + $api_rate_limit_seconds_in_window;
        $memcache->set("$key:expire", $expire_time);
    }

    // see if we need to reset our count
    $seconds_before_reset = $expire_time - time();
    if($seconds_before_reset <= 0)
    {
        $memcache->set("$key:count", 0);
    }

    // enforce exceeding the limit
    if($count > $api_rate_limit_requests_per_window)
    {
        throw new RateLimitExceeded(
            "Rate limit exceeded, resets in $seconds_before_reset seconds"
        );
    }

    $requests_left_before_reset = $api_rate_limit_requests_per_window - $count;
    header("X-Rate-Limit-Limit: $api_rate_limit_requests_per_window");
    header("X-Rate-Limit-Remaining: $requests_left_before_reset");
    header("X-Rate-Limit-Reset: $seconds_before_reset");
}

function api_get_request_body()
{
    return json_decode(file_get_contents('php://input'));
}

function api_output_response($data, $response_code=200)
{
    http_response_code($response_code);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    exit();
}

function handle_cors_headers()
{
    // Enable CORS for some sites
    $allowed_origins = [
        "http://editor.swagger.io",
    ];
    $origin = @$_SERVER["HTTP_ORIGIN"];
    if(in_array($origin, $allowed_origins))
    {
        header("Access-Control-Allow-Origin: $origin");
    }

    // Set the headers we accept from the client
    $allowed_headers = [
        "X-Api-Key",
        "Content-Type",
    ];
    header("Access-Control-Allow-Headers: " . implode(", ", $allowed_headers));

    header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH");

    // If this was a CORS OPTIONS request, stop now with a 200
    if(@$_SERVER["REQUEST_METHOD"] == "OPTIONS")
    {
        exit();
    }
}

function production_exception_handler($exception)
{
    if($exception instanceof ApiException)
        $response_code = $exception->getStatusCode();
    else
        $response_code = 500;

    api_output_response(["error" => $exception->getMessage()], $response_code);
}

function test_exception_handler($exception)
{
    production_exception_handler($exception);
}
