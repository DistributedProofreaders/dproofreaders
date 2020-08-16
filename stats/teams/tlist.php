<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc');

require_login();

$order = get_enumerated_param(
        $_GET, 'order', 'teamname', array('id', 'teamname', 'member_count') );
$direction = get_enumerated_param(
        $_GET, 'direction', 'asc', array('asc', 'desc') );
$tname = array_get($_REQUEST, 'tname', null);
$texact = array_get($_REQUEST, 'texact', null);

$tstart = get_integer_param( $_GET, 'tstart', 0, 0, null );

if ($tname) {
    if ($texact == 'yes')
    {
        $where_body = sprintf("teamname='%s'",
            mysqli_real_escape_string(DPDatabase::get_connection(), normalize_whitespace($tname)));
    }
    else
    {
        $where_body = sprintf("teamname LIKE '%%%s%%'",
            mysqli_real_escape_string(DPDatabase::get_connection(), normalize_whitespace($tname)));
    }

    $tResult = select_from_teams($where_body, "ORDER BY $order $direction LIMIT $tstart,20");
    $tRows = mysqli_num_rows($tResult);
    if ($tRows == 1)
    {
        $row = mysqli_fetch_assoc($tResult);
        metarefresh(0, "tdetail.php?tid=" . $row["id"]);
    }
    $tname = "tname=" . urlencode($tname) . "&amp;";
} else {
    $tResult=select_from_teams("", "ORDER BY $order $direction LIMIT $tstart,20");
    $tRows=mysqli_num_rows($tResult);
    $tname = "";
}

$user = User::load_current();
$user_teams = $user->load_teams();

$name = _("Team List");

output_header($name);
echo "<h1>" . html_safe($name) . "</h1>\n";

echo "<p><a href='new_team.php'>"._("Create a New Team")."</a></p>";

//Display of user teams
echo "<table class='themed theme_striped'>\n";
echo "<tr>";
    echo "<th></th>";
    if ($order == "teamname" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<th><a href='tlist.php?".$tname."tstart=$tstart&amp;order=teamname&amp;direction=$newdirection'>"._("Team Name")."</a></th>";
    if ($order == "member_count" && $direction == "desc") { $newdirection = "asc"; } else { $newdirection = "desc"; }
        echo "<th class='center-align'><a href='tlist.php?".$tname."tstart=$tstart&amp;order=member_count&amp;direction=$newdirection'>"._("Total Members")."</a></th>";
    echo "<th class='center-align'>"._("Options")."</th>";
echo "</tr>\n";
if (!empty($tRows)) {
    while ($row = mysqli_fetch_assoc($tResult)) {
        $tid = $row["id"];
        echo "<tr>";
        echo "<td class='center-align'><a href='tdetail.php?tid=$tid'><img src='$team_icons_url/".$row['icon']."' width='25' height='25' alt='".attr_safe($row['teamname'])."'></a></td>\n";
        echo "<td><a href='tdetail.php?tid=$tid'>" . html_safe($row['teamname']) . "</a></td>\n";
        echo "<td class='center-align'>".$row['member_count']."</td>\n";
        echo "<td class='center-align'>";
        if(!in_array($tid, $user_teams)) {
            echo "<a href='../members/jointeam.php?tid=$tid'>"._("Join")."</a></td>";
        } else {
            echo "<a href='../members/quitteam.php?tid=$tid'>"._("Quit")."</a></td>";
        }
        echo "</tr>\n";
    }
} else {
    echo "<tr><td colspan='4' class='center-align'><b>"._("No more teams available.")."</b></td></tr>\n";
}

echo "<tr><td colspan='2' class='left-align'>";
if (!empty($tstart)) {
    echo "<b><a href='tlist.php?".$tname."order=$order&amp;direction=$direction&tstart=".($tstart-20)."'>"._("Previous")."</a></b>";
}
echo "&nbsp;</td><td colspan='2' class='right-align'>&nbsp;";
if ($tRows == 20) {
    echo "<b><a href='tlist.php?".$tname."order=$order&amp;direction=$direction&amp;tstart=".($tstart+20)."'>"._("Next")."</a></b>";
}
echo "</td></tr>\n";
echo "</table>";

// vim: sw=4 ts=4 expandtab
