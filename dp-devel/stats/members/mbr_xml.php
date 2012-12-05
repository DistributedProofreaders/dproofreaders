<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'xml.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'forum_interface.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');

$username = @$_GET['username'];

if (empty($username)) {
    include_once($relPath.'theme.inc');
    theme(_("Error!"), "header");
     echo "<br><center>";
     echo sprintf( _("A username must specified in the following format: %s"), "$code_url/stats/members/mbr_xml.php?username=*****");
     echo "</center>";
    theme("", "footer");
    exit();
}

$curMbr = get_forum_user_details($username);
if (empty($curMbr)) {
        include_once($relPath.'theme.inc');
    theme(_("Error!"), "header");
     echo "<br><center>";
    echo sprintf(_("User '%s' does not exist."), htmlspecialchars($username));
     echo "</center>";
    theme("", "footer");
    exit();
}

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";
echo "<memberstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"memberstats.xsd\">\n";
    

$result = mysql_query("
    SELECT *
    FROM users
    WHERE username = '" . mysql_real_escape_string($username) . "'
    LIMIT 1
");
$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));

$u_id = $curMbr['u_id'];

$now = time();
$daysInExistence = floor(($now - $curMbr['date_created'])/86400);


//User info
if ($curMbr['u_privacy'] == PRIVACY_PUBLIC)
{
    echo "
        <userinfo id='$u_id'>
            <username>".xmlencode($curMbr['username'])."</username>
            <datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
            <lastlogin>".date("m/d/Y", $curMbr['last_login'])."</lastlogin>
            <location>".xmlencode($curMbr['from'])."</location>
            <occupation>".xmlencode($curMbr['occ'])."</occupation>
            <interests>".xmlencode($curMbr['interests'])."</interests>
            <website>".xmlencode($curMbr['website'])."</website>";


    foreach ( $page_tally_names as $tally_name => $tally_title )
    {
        $tallyboard = new TallyBoard( $tally_name, 'U' );

        $current_page_tally = $tallyboard->get_current_tally($u_id);
        $currentRank = $tallyboard->get_rank($u_id);

        list($bestDayCount,$bestDayTimestamp) =
            $tallyboard->get_info_re_largest_delta($u_id);
        $bestDayTime = date("M. jS, Y", ($bestDayTimestamp-1));

        if ($daysInExistence > 0) {
                $daily_Average = $current_page_tally/$daysInExistence;
        } else {
                $daily_Average = 0;
        }

        echo "
            <roundinfo id='$tally_name'>
                <pagescompleted>$current_page_tally</pagescompleted>
                <overallrank>$currentRank</overallrank>
                <bestdayever>
                    <pages>$bestDayCount</pages>
                    <date>$bestDayTime</date>
                </bestdayever>
                <dailyaverage>".number_format($daily_Average)."</dailyaverage>
            </roundinfo>";
    }

    echo "
        </userinfo>";

//Team info
    $result = select_from_teams("id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})");
    echo "
        <teaminfo>";
    while ($row = mysql_fetch_assoc($result)) {
        echo "
            <team>
            <name>".xmlencode($row['teamname'])."</name>
            <activemembers>".$row['active_members']."</activemembers>
            </team>";
    }
    echo "
        </teaminfo>";
}

echo "
</memberstats>";

// vim: sw=4 ts=4 expandtab
