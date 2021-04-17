<?php
$relPath = "../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()

require_login();

$clausemap = [
    'nameofwork' => 'nameofwork ASC',
    'modifieddate' => 'modifieddate ASC',
    'PPer' => 'postproofer ASC',
    'PM' => 'username ASC',
];
$order = get_enumerated_param($_GET, 'order', 'nameofwork', array_keys($clausemap));
$orderclause = $clausemap[$order];

// ------------------

$title = _("Books Available for PPV");
output_header($title);

echo "<h1>$title</h1>\n";

// ------------------

// Header row

$colspecs = [
    'bogus' => _('#'),
    'nameofwork' => _('Name of Work'),
    'PM' => _('Project Manager'),
    'PPer' => _('Post-Processed By'),
    'modifieddate' => _('Date Last Modified'),
];

echo "<table class='themed theme_striped'>\n";
echo "<tr>";
foreach ($colspecs as $col_order => $col_header) {
    $s = $col_header;
    // Make each column-header a link that will sort on that column,
    // except for the header of the column that we're already sorting on.
    if ($col_order != $order && $col_order != 'bogus') {
        $s = "<a href='PPV_avail.php?order=$col_order'>$s</a>";
    }
    echo "<th>$s</th>";
}
echo "</tr>\n";

// ------------------

// Body

$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT
        nameofwork,
             username,
        postproofer,
        modifieddate
    FROM projects
    WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'
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

    echo "
        <tr>
        <td>$rownum</td>
        <td>" . html_safe($project->nameofwork) . "</td>
        <td style='white-space: nowrap;'>$project->username</td>
        <td style='white-space: nowrap;'>$project->postproofer</td>
        <td style='white-space: nowrap;'>$datestamp</td>
        </tr>
    ";
}

echo "</table>";
