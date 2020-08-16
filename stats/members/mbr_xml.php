<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'misc.inc'); // xmlencode()
include_once($relPath.'page_tally.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'User.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');

require_login();

$username = @$_GET['username'];
$user = new User($username);
$forum_profile= get_forum_user_details($username);

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml; charset=$charset");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";
echo "<memberstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"memberstats.xsd\">\n";
    
$now = time();
$daysInExistence = floor(($now - $user->date_created)/86400);


//User info
if ($user->u_privacy == PRIVACY_PRIVATE)
{
    echo "
        <userinfo id='$user->u_id'>
            <username>".xmlencode($user->username)."</username>
            <datejoined>".date("m/d/Y", $user->date_created)."</datejoined>
            <lastlogin>".date("m/d/Y", $user->last_login)."</lastlogin>
            <location>".xmlencode($forum_profile['from'])."</location>
            <occupation>".xmlencode($forum_profile['occ'])."</occupation>
            <interests>".xmlencode($forum_profile['interests'])."</interests>
            <website>".xmlencode($forum_profile['website'])."</website>";


    foreach ( get_page_tally_names() as $tally_name => $tally_title )
    {
        $tallyboard = new TallyBoard( $tally_name, 'U' );

        $current_page_tally = $tallyboard->get_current_tally($user->u_id);
        $currentRank = $tallyboard->get_rank($user->u_id);

        list($bestDayCount,$bestDayTimestamp) =
            $tallyboard->get_info_re_largest_delta($user->u_id);
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
    echo "
        <teaminfo>";
    $user_teams = $user->load_teams();
    if($user_teams) {
        $teams_clause = implode(",", $user_teams);
        $result = select_from_teams("id IN ($teams_clause)");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
                <team>
                <name>".xmlencode($row['teamname'])."</name>
                <activemembers>".Team::active_member_count($row['id'])."</activemembers>
                </team>";
        }
    }
    echo "
        </teaminfo>";
}

echo "
</memberstats>";

// vim: sw=4 ts=4 expandtab
