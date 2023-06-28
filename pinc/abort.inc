<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc'); // html_safe()
include_once($relPath.'misc.inc'); // html_safe()

function abort($error_message)
{
    slim_header(_("Error"));

    echo "<p>";
    echo _("We are unable to complete your request.");
    echo "</p>\n";
    echo "<p>";
    echo _("If you want to resolve, report, or get help with this incident, it may be useful to save the information appended below.");
    echo "</p>\n";

    echo "\n";
    provide_escape_links();
    echo "\n";

    echo "<hr>\n";

    echo "<p class='error'>" . html_safe($error_message) . "</p>\n";
    echo "<pre>\n";
    dump_request_info();

    if (0) {
        echo "\n";
        echo "<hr>\n";
        echo "\n";
        debug_print_backtrace();
    }

    echo "</pre>\n";
    exit;
}

function provide_escape_links()
{
    global $code_url;

    echo "<ul>\n";

    // link to project home
    {
        $projectid = get_parameter_value(
            ['projectid', 'projectname', 'project']);
        try {
            validate_projectID($projectid);

            // This link won't work if the error is that the project doesn't exist.
            echo "<li>" . return_to_project_page_link($projectid) . "</li>\n";
        } catch (InvalidProjectIDException $exception) {
            // don't output anything as the project ID is not valid
        }
    }

    // link to round
    {
        $round = get_Round_for_project_state(
            get_parameter_value(['proj_state', 'proofstate']));
        if (is_null($round)) {
            $round = get_Round_for_page_state(
                get_parameter_value(['pagestate', 'page_state']));
        }
        if (!is_null($round)) {
            echo "<li>" . return_to_round_page_link($round->id) . "</li>\n";
        }
    }

    // link to activity hub
    {
        echo "<li>" . return_to_activity_hub_link() . "</li>\n";
    }
    echo "</ul>\n";
}

function get_parameter_value($possible_names)
// Look through request parameters for a parameter with one of the given names
// and a non-empty value. Return the value, or NULL if none found.
{
    foreach ($possible_names as $possible_name) {
        $v = @$_GET[$possible_name];
        if ($v != '') {
            return $v;
        }

        $v = @$_POST[$possible_name];
        if ($v != '') {
            return $v;
        }
    }
    return null;
}

function dump_request_info()
{
    $now = time();
    $date = date('Y-m-d H:i:s', $now);
    echo "time: $now ($date)\n";
    echo "username: " . html_safe($GLOBALS['pguser']) . "\n";
    echo "requested path: " . html_safe($_SERVER['PHP_SELF']) . "\n";

    // If this error occurred when they hit one of the submit buttons
    // in the proofreading interface, echoing the request parameters will
    // give them a last chance to save the page text.

    foreach (['_GET', '_POST'] as $array_name) {
        switch ($array_name) {
            case '_GET':  $array = $_GET; break;
            case '_POST': $array = $_POST; break;
            default: $array = []; assert(false);
        }
        if ($array) {
            echo "$array_name\n";
            echo html_safe(json_encode($array, JSON_PRETTY_PRINT));
            echo "\n\n";
        }
    }
}
