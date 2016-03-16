<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$ordermap = array(
    'nameofwork'             => 'nameofwork ASC',
    'checkedoutby'           => 'checkedoutby ASC, modifieddate ASC',
    'postproofer'            => 'postproofer ASC, modifieddate ASC',
    'modifieddate'           => 'modifieddate ASC',
    'holder_t_last_activity' => 'holder_t_last_activity ASC'
);

$order = get_enumerated_param($_GET, 'order', 'checkedoutby', array_keys($ordermap));
$state = get_enumerated_param($_GET, 'state', PROJ_POST_FIRST_CHECKED_OUT,
    array(PROJ_POST_FIRST_CHECKED_OUT, PROJ_POST_SECOND_CHECKED_OUT));

if ( $state == PROJ_POST_FIRST_CHECKED_OUT ) {
    $title = _('Books Checked Out for Post Processing');
}
if ( $state == PROJ_POST_SECOND_CHECKED_OUT ) {
    $title = _('Books Checked Out for Post Processing Verification');
    $inPPV = 1;
}

$orderclause = $ordermap[$order];

// ------------------

output_header($title);

echo "<h1>$title</h1>\n";

// ------------------

// Header row

if (isset($inPPV)) {
    $colspecs = array(
        // TRANSLATORS: this is a column header meaning "number"
        'bogus'                  => _('#'),
        'nameofwork'             => _('Name of Work'),
        'postproofer'            => _('PPer'),
        'checkedoutby'           => _('Checked Out By'),
        'modifieddate'           => _('Date Last Modified'),
        'holder_t_last_activity' => _('User Last on Site')
   );
} else {
    $colspecs = array(
        'bogus'                  => _('#'),
        'nameofwork'             => _('Name of Work'),
        'checkedoutby'           => _('Checked Out By'),
        'modifieddate'           => _('Date Last Modified'),
        'holder_t_last_activity' => _('User Last on Site')
    );
}

echo "<table border='1' cellspacing='0' cellpadding='2' style='border: 1px solid #111; border-collapse: collapse' width='99%'>\n";
echo "<tr><td colspan='" .count($colspecs)."' bgcolor='".$theme['color_headerbar_bg']."'><center><font color='".$theme['color_headerbar_font']."'><b>$title</b></font></center></td></tr>";

echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
foreach ( $colspecs as $col_order => $col_header )
{
    $s = $col_header;
    // Make each column-header a link that will sort on that column,
    // except for the header of the column that we're already sorting on.
    if ( $col_order != $order && $col_order != 'bogus' )
    {
        $s = "<a href='checkedout.php?state=$state&order=$col_order'>$s</a>";
    }
    $s = "<th><center>".$s ."</center></th>";
    echo "$s\n";
}
echo "</tr>\n";

// ------------------

// Body

$result = mysql_query("
    SELECT
        nameofwork,
             postproofer,
        checkedoutby,
        modifieddate,
        users.t_last_activity AS holder_t_last_activity
    FROM projects
        LEFT OUTER JOIN users
        ON projects.checkedoutby = users.username
    WHERE state = '$state'
    ORDER BY $orderclause
");

$rownum = 0;
$listing_bgcolors = array($theme['color_listing_bg_1'], $theme['color_listing_bg_2']);
while ( $project = mysql_fetch_object( $result ) )
{
    $rownum++;

    //calc last modified date for project
    $today = getdate($project->modifieddate);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $datestamp = "$month $mday, $year";

    //calc date of user's latest site-activity
    $today = getdate($project->holder_t_last_activity);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $holder_t_last_activity_date = "$month $mday, $year";

    echo "
        <tr bgcolor='" . $listing_bgcolors[$rownum % 2] . "'>
        <td>$rownum</td>
        <td width='200'>$project->nameofwork</td>
      ";
     
      if (isset($inPPV)) { 

            echo "    <td>$project->postproofer</td>";
      }
      echo "       
        <td>$project->checkedoutby</td>
        <td>$datestamp</td>
        <td>$holder_t_last_activity_date</td>
        </tr>
    ";
}

echo "</table>";

// vim: sw=4 ts=4 expandtab
