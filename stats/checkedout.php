<?php
$relPath = "../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()

require_login();

$ordermap = [
    'nameofwork' => 'nameofwork ASC',
    'checkedoutby' => 'checkedoutby ASC, modifieddate ASC',
    'postproofer' => 'postproofer ASC, modifieddate ASC',
    'modifieddate' => 'modifieddate ASC',
    'holder_t_last_activity' => 'holder_t_last_activity ASC',
];

$order = get_enumerated_param($_GET, 'order', 'checkedoutby', array_keys($ordermap));
$state = get_enumerated_param($_GET, 'state', PROJ_POST_FIRST_CHECKED_OUT,
    [PROJ_POST_FIRST_CHECKED_OUT, PROJ_POST_SECOND_CHECKED_OUT]);

if ($state == PROJ_POST_FIRST_CHECKED_OUT) {
    $title = _('Books Checked Out for Post Processing');
}
if ($state == PROJ_POST_SECOND_CHECKED_OUT) {
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
    $colspecs = [
        // TRANSLATORS: this is a column header meaning "number"
        'bogus' => _('#'),
        'nameofwork' => _('Name of Work'),
        'postproofer' => _('PPer'),
        'checkedoutby' => _('Checked Out By'),
        'modifieddate' => _('Date Last Modified'),
        'holder_t_last_activity' => _('User Last on Site'),
    ];
} else {
    $colspecs = [
        'bogus' => _('#'),
        'nameofwork' => _('Name of Work'),
        'checkedoutby' => _('Checked Out By'),
        'modifieddate' => _('Date Last Modified'),
        'holder_t_last_activity' => _('User Last on Site'),
    ];
}

echo "<table class='themed theme_striped'>\n";

echo "<tr>";
foreach ($colspecs as $col_order => $col_header) {
    $s = $col_header;
    // Make each column-header a link that will sort on that column,
    // except for the header of the column that we're already sorting on.
    if ($col_order != $order && $col_order != 'bogus') {
        $s = "<a href='checkedout.php?state=$state&amp;order=$col_order'>$s</a>";
    }
    echo "<th>$s</th>";
}
echo "</tr>\n";

// ------------------

// Body

$result = mysqli_query(DPDatabase::get_connection(), "
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
while ($project = mysqli_fetch_object($result)) {
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
        <tr>
        <td>$rownum</td>
        <td>" . html_safe($project->nameofwork) . "</td>
      ";

    if (isset($inPPV)) {
        echo "    <td style='white-space: nowrap;'>$project->postproofer</td>";
    }
    echo "       
        <td style='white-space: nowrap;'>$project->checkedoutby</td>
        <td style='white-space: nowrap;'>$datestamp</td>
        <td style='white-space: nowrap;'>$holder_t_last_activity_date</td>
        </tr>
    ";
}

echo "</table>";
