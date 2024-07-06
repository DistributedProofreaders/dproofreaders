<?php
// This script is expected to be run from the CLI. When the requested job
// supports it, the job is run entirely from the CLI context as that doesn't
// tie up an Apache connection. However, some jobs that touch the filesystem
// require additional configuration changes to ensure correct file permissions.
// The script will run these jobs through the web context directly.
//
// For the CLI script to get the most accurate status of the proxied connections
// we want to get header responses back from the web server as quickly as
// possible. This lets us detect timeouts and gather other stream-based metadata.
// To that end we disable deflate plugin for fast-flushing, we want to do this
// before we send headers so do it before the include.
if (php_sapi_name() != "cli") {
    apache_setenv('no-gzip', '1');
}

$relPath = dirname(__FILE__) . "/../pinc/";
include_once($relPath."base.inc");

// now attempt to flush the headers configured by base.inc back to the client
flush();

require_localhost_request();

if (php_sapi_name() == "cli") {
    $input_array = $argv;
    $class_key = "1";
    $stdout_on_success_key = "2";
} else {
    $input_array = $_GET;
    $class_key = "class";
    $stdout_on_success_key = "stdout_on_success";
}

$class = get_param_matching_regex($input_array, $class_key, null, "/^[a-zA-Z]\w+$/");
$stdout_on_success = get_enumerated_param($input_array, $stdout_on_success_key, "false", ["true", "false"]) == "true";

spl_autoload_register(function ($class_name) {
    global $relPath;
    $inc_file = "$relPath/../crontab/$class_name.inc";
    if (is_file($inc_file)) {
        include_once($inc_file);
    }
});

// try to instantiate our class
try {
    $job = new $class();
} catch (Exception | Error $exception) {
    throw new RuntimeException("Unable to instantiate job $class");
}

// confirm it's a valid BackgroundJob
if (! is_subclass_of($job, "BackgroundJob")) {
    throw new RuntimeException("$job is not a valid BackgroundJob");
}

// If we're not in a web context already, determine if the job requires one
// and proxy it through the web server
if (php_sapi_name() == "cli" && $job->requires_web_context) {
    $url = "$code_url/crontab/run_background_job.php?" . http_build_query([
        "class" => $class,
        "stdout_on_success" => $stdout_on_success ? "true" : "false",
    ]);
    $stream_options = [
        "http" => [
            "timeout" => 3600, // an hour
        ],
    ];
    $context = stream_context_create($stream_options);
    if (!$fh = @fopen($url, "r", false, $context)) {
        throw new RuntimeException("$class job failed opening proxied connection to web server");
    }
    fpassthru($fh);
    $meta = stream_get_meta_data($fh);
    fclose($fh);

    // Check for non-2xx response codes, reverse the array so we get the last
    // result and not any 3xx redirects.
    foreach (array_reverse($meta["wrapper_data"]) as $header_entry) {
        if (preg_match("/^HTTP.* (\d+)/", $header_entry, $matches)) {
            $response_code = (int)$matches[1];
            if ($response_code < 200 or $response_code >= 300) {
                throw new RuntimeException("$class job returned HTTP response code $response_code proxied through web server");
            }
        }
    }

    // In case the connection timed out
    if ($meta["timed_out"]) {
        throw new RuntimeException("$class job timed out proxied through web server");
    }
} else {
    // run the job directly from here
    $job->stdout_on_error = true;
    $job->stdout_on_success = $stdout_on_success;
    $job->go();
}
