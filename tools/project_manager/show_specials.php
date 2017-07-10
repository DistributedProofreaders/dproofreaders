<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Details of Special Days/Weeks/Months");

$theme_args['css_data'] = "
table.listing { border-collapse: collapse; width: 90%; margin: auto; }
table.listing td,th { border: 1px solid #999; padding: 2px; }
table.listing td.center { text-align: center; }
table.listing th { border-top: solid black 2px; background-color: #eeeeee; }
table.listing tr.month > td { border: none; }
table.listing h2 { margin: 1em auto auto auto; text-align: left; }";

output_header($title, NO_STATSBAR, $theme_args);

echo "<br><h1>$title</h1>\n";
echo _("The Name column shows what the colour looks like with a link on top, the Comment with ordinary text.")."<br><br>";

$result = mysql_query("SELECT * FROM special_days ORDER BY open_month, open_day");

echo "<br>\n";
echo "<table class='listing'>";

$current_month = -1;

while ( $row = mysql_fetch_assoc($result) )
{
    $month = $row['open_month'];

    // This handles the exceptions for the 'special' special days which aren't really
    // associated with specific dates, but are defined solely to create a queue.
    if ($month == 0 && $current_month != 0)
    {
        $current_month = $month;
        echo "<tr class='month'><td><h2>" . _("Undated Entries") . "</h2></td></tr>\n";
        output_column_headers();
    }

    if ($month != $current_month)
    {
        $current_month = $month;
        echo "<tr class='month'><td><h2>";
        echo strftime("%B", mktime(0, 0, 0, $row['open_month'], 10)) . "</h2></td></tr>\n";
        output_column_headers();
    }

    echo "<tr>";
    echo "<td style='background-color: #" . $row['color'] . ";'>";
    echo "<a href=\"projectmgr.php?show=search&amp;special_day%5B%5D=";
    echo urlencode($row['spec_code']) ."&amp;n_results_per_page=100\" title=\"";
    echo urlencode($row['display_name']) ."\">\n";
    echo html_safe($row['display_name']) . "</a>";
    echo "</td>\n";
    echo "<td style='background-color: #" . $row['color'] . ";'>";
    echo "<div title=\"" . html_safe($row['comment']) ."\">";
    echo html_safe($row['comment']) . "</div></td>\n";
    echo "<td class='center'>";
    if ($current_month != 0 )
        echo html_safe($row['open_day']);
    else
        echo _("N/A"); // Translators: N/A = "Not applicable"
    echo "</td>\n";
    echo "<td>";
    echo "<a href='" . urlencode($row['info_url']) . "'>";
    echo html_safe($row['info_url']) . "</a></td>\n";
    echo "</tr>\n";
}
echo "</table>";
echo "<br>\n";

// =============================================================================

function output_column_headers()
{
    echo "<tr>";
    echo "<th>" . _("Name") . "</th>";
    echo "<th>" . _("Comment") . "</th>";
    echo "<th>" . _("Start Day") . "</th>";
    echo "<th>" . _("More Info") . "</th>";
    echo "</tr>\n";
}

// vim: sw=4 ts=4 expandtab
