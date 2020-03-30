<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'User.inc');
include_once($relPath.'project_states.inc');

require_login();

$username = $pguser;
if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
{
    $username = array_get( $_GET, 'username', $pguser );
    if($username && !User::is_valid_user($username))
    {
        die("Invalid username.");
    }
}

$view_modes = [
    "suspect" => [
        "label" => _("Projects with suspect clearances"),
        "description" => _("Showing projects with suspect clearances that have not been posted or deleted."),
    ],
    "all" => [
        "label" => _("All projects"),
        "description" => _("Showing all of your projects that have not been posted or deleted."),
    ],
];

// if no username is specified, only allow showing suspect projects
if(!$username)
{
    unset($view_modes['all']);
}

$view_mode = get_enumerated_param($_GET, "show", "suspect", array_keys($view_modes));

$title = _("Check Clearances");
output_header($title, NO_STATSBAR);

if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
{
    echo "<div id='pm_links' class='sidebar-color'>";
    echo "<form action='#' method='get'><p>";
    echo _("See projects for another user") . "<br>";
    echo "<input type='text' name='username' value='$username' required>";
    echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
    echo "</p></form>\n";
    echo "<p><a href='?username='>" . _("See all projects with suspect clearances") . "</a></p>";
    echo "</div>";
}

echo "<h1>$title</h1>";

show_page_menu($view_modes, $view_mode, $username);

echo "<p>" . $view_modes[$view_mode]["description"] . "</p>";

echo "<table class='themed theme_striped' style='width: auto;'>";
echo "<tr>";
echo "<th>" . _("Title") . "</th>";
echo "<th>" . _("Author") . "</th>";
echo "<th>" . _("Project Manager") . "</th>";
echo "<th>" . _("State") . "</th>";
echo "<th>" . _("Clearance") . "</th>";
echo "</tr>";

$res = get_table_query_resource($username, $view_mode);
while ($row = mysqli_fetch_assoc($res))
{
    $projectid = $row['projectid'];
    echo "<tr>";
    echo "<td><a href='$code_url/project.php?id=$projectid'>" . $row['nameofwork'] . "</a></td>";
    echo "<td>" . $row['authorsname'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['state'] . "</td>";
    echo "<td>" . $row['clearance'] . "</td>";
    echo "</tr>";
}
echo "</table>";

//---------------------------------------------------------------------------

function show_page_menu($all_view_modes, $view_mode, $username)
{
    global $pguser;

    $qs_username = "";
    if($pguser != $username)
        $qs_username = "username=$username&amp;";

    echo "<div class='tabs'>";
    echo "<ul>";

    foreach($all_view_modes as $setting => $setting_values)
    {
        $label = $setting_values["label"];
        if($view_mode == $setting)
            echo "<li class='current-tab'><a>$label</a></li>";
        else
            echo "<li><a href='?${qs_username}show=$setting'>$label</a></li>";
    }

    echo "</ul>";
    echo "</div>";
    echo "<div style='clear: both;'></div>";
}

function get_table_query_resource($username, $view_mode)
{
    $sql = "
        SELECT projectid, nameofwork, authorsname, username, state, clearance
        FROM projects
        WHERE state NOT IN ('proj_submit_pgposted','project_delete')
    ";

    if($username)
    {
        $sql .= sprintf("
            AND username = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $username));
    }

    if($view_mode == 'suspect')
    {
        $pattern1 = '^20[0-9]{12}[a-z]+\.?$';
        $pattern2 = '^gbn[0-9]';

        $okay_clearance_condition = "
            clearance RLIKE '$pattern1' OR clearance RLIKE '$pattern2'
        ";
        $sql .= "AND NOT($okay_clearance_condition) ";
    }

    $sql .= "ORDER BY username, " .  sql_collater_for_project_state("state");

    return dpsql_query($sql);
}

// vim: sw=4 ts=4 expandtab
