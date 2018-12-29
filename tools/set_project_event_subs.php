<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');  // validate_projectID()

require_login();

$projectid = validate_projectID('projectid', @$_POST['projectid']);
$return_uri = urldecode($_POST['return_uri']);

$subs = array();
$unsubs = array();
foreach ( $subscribable_project_events as $event => $label )
{
    if (!can_user_subscribe_to_project_event( $pguser, $projectid, $event ))
    {
        continue;
    }

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
        echo "<li><i>" . pgettext("no subscriptions", "none") . "</i></li>\n";
    }
    echo "</ul>\n";
}

echo "<a href='$return_uri'>", _("Click here to return"), "</a>";

// vim: sw=4 ts=4 expandtab
