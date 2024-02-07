#!/usr/bin/env php
<?php

function usage()
{
    global $argv, $STDERR;

    fwrite($STDERR, sprintf("
Usage: %s old_config_file.sh configuration.sh > new_config_file.sh

This program will take parameter values in one configuration file and use them
to update the same parameters in a second configuration file. The new combined
file is output on stdout. The program will output on stderr parameters from
old_config_file.sh that are not used or are new in configuration.sh.

Arguments:
    old_config_file.sh - configuration file with the values you wish to retain
    configuration.sh - configuration file with the parameters to update
    new_config_file.sh - configuration file with the updated parameter values
", $argv[0]));
}

function merge_configuration_files($old_filename, $new_filename)
{
    global $STDERR;

    $config_values = load_config_file_values($old_filename);

    [$new_config_parameters, $unused_config_parameters] =
            output_config_file_with_new_values($new_filename, $config_values);

    if (count($new_config_parameters)) {
        fwrite($STDERR, sprintf(
            "The following variables are in %s but not in %s, you may want confirm they are set to the values you want:\n",
            $new_filename,
            $old_filename
        ));
        ksort($new_config_parameters);
        foreach ($new_config_parameters as $parameter => $value) {
            fwrite($STDERR, "    $parameter=$value\n");
        }
        fwrite($STDERR, "\n");
    }

    if (count($unused_config_parameters)) {
        fwrite($STDERR, sprintf(
            "These values are set in %s, but were not found in %s.\n",
            $old_filename,
            $new_filename
        ));
        foreach ($unused_config_parameters as $parameter => $value) {
            fwrite($STDERR, "    $parameter=$value\n");
        }
        fwrite($STDERR, "You should confirm they are not used as values and thus needed in the new configuration file.\n");
        fwrite($STDERR, "\n");
    }
}

function load_config_file_values($filename)
{
    $fh = fopen($filename, "r");
    if (!$fh) {
        fwrite($STDERR, "Unable to open $filename for reading\n");
        exit(1);
    }

    $config_values = [];

    while (!feof($fh)) {
        $line = fgets($fh);

        if (preg_match("/^\s*([A-Za-z0-9_]+)=(.*)/", $line, $matches)) {
            $config_values[$matches[1]] = $matches[2];
        }
    }

    fclose($fh);

    return ($config_values);
}

function output_config_file_with_new_values($filename, $config_values)
{
    $fh = fopen($filename, "r");
    if (!$fh) {
        fwrite($STDERR, "Unable to open $filename for reading\n");
        exit(1);
    }

    $new_config_parameters = [];
    $unused_config_parameters = $config_values;

    while (!feof($fh)) {
        $line = fgets($fh);

        if (preg_match("/^\s*([A-Za-z0-9_]+)=(.*)/", $line, $matches)) {
            if (isset($config_values[$matches[1]])) {
                $line = sprintf("%s=%s\n", $matches[1], $config_values[$matches[1]]);
                unset($unused_config_parameters[$matches[1]]);
            } else {
                $new_config_parameters[$matches[1]] = $matches[2];
            }
        }

        echo $line;
    }

    fclose($fh);

    return ([$new_config_parameters, $unused_config_parameters]);
}

// ---------------------------------------------------------------------------

$STDERR = fopen('php://stderr', 'w+');

if (count($argv) == 3) {
    merge_configuration_files($argv[1], $argv[2]);
} else {
    usage();
    exit(1);
}
