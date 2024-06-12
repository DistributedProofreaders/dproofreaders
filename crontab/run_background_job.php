<?php
$relPath = dirname(__FILE__) . "/../pinc/";
include_once($relPath."base.inc");

// This script is expected to be run from the CLI. When the requested job
// supports it, the job is run entirely from the CLI context as that doesn't
// tie up an Apache connection. However, some jobs that touch the filesystem
// require additional configuration changes to ensure correct file permissions.
// The script will run these jobs through the web context directly.

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
    if (!readfile($url, false, $context)) {
        throw new RuntimeException("$class job failed proxied through web server");
    }
} else {
    // run the job directly from here
    $job->stdout_on_error = true;
    $job->stdout_on_success = $stdout_on_success;
    $job->go();
}
