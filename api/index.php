<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once('v1.inc');
header('Content-Type: application/json');

// can remove this if it is in base.inc
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$path = $_REQUEST["q"];

// this is used for testing messages such as simulated emails and debugging
$test_log = '';
function apilog($message)
{
    global $test_log;
    $test_log .= ", " . $message;
}

$data = api_router($path);
$data['log'] = $test_log;

$retval = json_encode($data);
if (!$retval)
{
    $retval = json_encode(array("error" => "json error: " . json_last_error_msg()));
}
echo $retval;
# ---------------------------------------------------------------------------
function api_router($path)
{
    global $utf8_site;

    $path_elements = explode('/', $path);
    $api_version = array_shift($path_elements);
    if ($api_version !== 'v1')
    {
        http_response_code(500);
        return array(
            "error" => "Invalid API version",
        );
    }
    $function_path = array($api_version);
    $params = array();
    $index = 0;
    foreach ($path_elements as $element)
    {
        if ($index % 2 == 0)
        {
            array_push($function_path, $element);
        }
        else
        {
            array_push($params, $element);
        }
        $index += 1;
    }
    array_push($function_path, $_SERVER['REQUEST_METHOD']);
    $function = implode('_', $function_path);
    if (!function_exists($function))
    {
        http_response_code(500);
        return array(
            "error" => "API path $path not found function = $function",
        );
    }
    try
    {
        return $function($params);
    }
    catch (Exception $exception)
    {
        http_response_code(400);
        $message = $exception->getMessage();
        if (!$utf8_site)
        {
            $message = utf8_encode($message);
        }
        return array(
            "error" => "The following error occurred: " . $message,
        );
    }
}
