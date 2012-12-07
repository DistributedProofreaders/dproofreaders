<?php
$relPath="./../pinc/";
include($relPath.'base.inc');
include($relPath.'metarefresh.inc');
include($relPath.'user_project_info.inc');
include($relPath.'Project.inc');  // validate_projectID()

require_login();

$projectid = validate_projectID('projectid', @$_POST['projectid']);
$return_uri = $_POST['return_uri'];

$subs = array();
$unsubs = array();
foreach ( $subscribable_project_events as $event => $label )
{
    if ( @$_POST[$event] == 'on' )
    {
        subscribe_user_to_project_event( $pguser, $projectid, $event );
        $subs[] = $label;
    }
    else
    {
        unsubscribe_user_from_project_event( $pguser, $projectid, $event );
        $unsubs[] = $label;
    }
}

_html_ul(
    _("You are now subscribed to these events for this project:"),
    $subs );

_html_ul(
    _("You are now unsubscribed from these events for this project:"),
    $unsubs );

function _html_ul( $header, $items )
{
    echo $header;
    echo "<ul>\n";
    if ( $items )
    {
        foreach ( $items as $item )
        {
            echo "<li>$item</li>\n";
        }
    }
    else
    {
        echo "<li>(" . _("none") . ")</li>\n";
    }
    echo "</ul>\n";
}

echo "<a href='$return_uri'>", _("Click here to return"), "</a>";

// vim: sw=4 ts=4 expandtab
