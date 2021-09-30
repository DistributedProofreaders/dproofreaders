<?php
$relPath = "../pinc/";
include_once($relPath.'bootstrap.inc');
include_once($relPath.'User.inc');
include_once($relPath.'misc.inc');
include_once('ApiRouter.inc');
include_once('exceptions.inc');
include_once('v1.inc');

// everything is a JSON response
header("Content-Type: application/json");

handle_cors_headers();

if ($maintenance) {
    throw new ApiException("Site is in maintenance mode");
}

if (!@$api_enabled) {
    throw new ApiException("API is not enabled");
}

api();

// ---------------------------------------------------------------------------

function api()
{
    $username = api_authenticate();

    api_rate_limit($username);

    $query_params = $_REQUEST;
    $path = array_get($query_params, "url", "");
    unset($query_params["url"]);

    $router = ApiRouter::get_router();

    api_output_response($router->route($path, $query_params));
}

function api_authenticate()
{
    global $pguser;

    $api_key = @$_SERVER['HTTP_X_API_KEY'];
    if ($api_key) {
        // If the api_key is "SESSION" attempt to load the user session
        if ($api_key == "SESSION") {
            dpsession_resume();
        }
        // Otherwise use the API key in the HTTP header
        else {
            try {
                $user = User::load_from_api_key($api_key);
            } catch (NonexistentUserException $exception) {
                throw new UnauthorizedError();
            }
            $pguser = $user->username;
        }
    }

    if (!isset($pguser)) {
        throw new UnauthorizedError();
    }

    return $pguser;
}

function api_rate_limit($key)
{
    global $api_rate_limit;
    global $api_rate_limit_requests_per_window, $api_rate_limit_seconds_in_window;

    if (!$api_rate_limit) {
        return;
    }

    // memcached keys can't contain whitespaces or control characters:
    // https://github.com/memcached/memcached/blob/master/doc/protocol.txt#L41
    // To enforce that, hash the key.
    $key = md5($key);

    $memcache = new Memcached();
    $memcache->addServer('localhost', 11211);

    // initialize or reset our expire time
    $expire_time = $memcache->get("$key:expire");
    if ($expire_time === false || $expire_time < time()) {
        $expire_time = time() + $api_rate_limit_seconds_in_window;
        $memcache->set("$key:expire", $expire_time);
        $count = 0;
    }
    // otherwise get the current value
    else {
        $count = $memcache->get("$key:count");
        if ($count === false) {
            $count = 0;
        }
    }

    $count = $count + 1;

    // increment (or initialize) our count
    if ($memcache->set("$key:count", $count) === false) {
        // if we can't set the value, memcached probably isn't running
        // regardless, we can't enforce rate limiting so log it and return an error
        error_log("api/index.php - Error setting $key:count=$count in memcache");
        throw new ServerError();
    }

    // enforce exceeding the limit
    $seconds_before_reset = $expire_time - time();
    if ($count > $api_rate_limit_requests_per_window) {
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

function api_output_response($data, $response_code = 200)
{
    http_response_code($response_code);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    exit();
}

function api_send_pagination_header($query_params, $total_rows, $per_page, $page)
{
    header("X-Results-Total: $total_rows");

    // don't send a pagination header if everything fits on one page
    if ($total_rows <= $per_page) {
        return;
    }

    $total_pages = round($total_rows / $per_page);

    // create the link base by parsing $query_params
    $params = [];
    foreach ($query_params as $key => $value) {
        if (in_array($key, ["page", "per_page"])) {
            continue;
        }

        if (is_array($value)) {
            foreach ($value as $subkey => $value) {
                $params[] = "{$key}[]=" . urlencode($value);
            }
        } else {
            $params[] = "$key=" . urlencode($value);
        }
    }
    $params[] = "per_page=$per_page";

    // If the URI doesn't include the url param (because the Apache config
    // hasn't been updated) we need to include it here to make valid links.
    $link_base = $_SERVER["SCRIPT_URI"] . "?";
    if (stripos($link_base, $_GET["url"]) === false) {
        $link_base .= "url=" . $_GET["url"] . "&";
    }
    $link_base .= implode("&", $params);

    // determine which relative links we need to include
    $links = [];
    $links["first"] = "page=1";
    if ($page > 1) {
        $links["prev"] = "page=" . ($page - 1);
    }
    if ($page < $total_pages) {
        $links["next"] = "page=" . ($page + 1);
    }
    $links["last"] = "page=$total_pages";

    // build the string
    $link_header = [];
    foreach ($links as $rel => $url) {
        $link_header[] = "<$link_base&$url>; rel=\"$rel\"";
    }
    header("Link: " . implode(", ", $link_header));
}

function handle_cors_headers()
{
    // Enable CORS for some sites
    $allowed_origins = [
        "https://editor.swagger.io",
    ];
    $origin = @$_SERVER["HTTP_ORIGIN"];
    if (in_array($origin, $allowed_origins)) {
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
    if (@$_SERVER["REQUEST_METHOD"] == "OPTIONS") {
        exit();
    }
}

function production_exception_handler($exception)
{
    if ($exception instanceof ApiException) {
        $response_code = $exception->getStatusCode();
    } else {
        $response_code = 500;
    }

    api_output_response(["error" => $exception->getMessage()], $response_code);
}

function test_exception_handler($exception)
{
    production_exception_handler($exception);
}
