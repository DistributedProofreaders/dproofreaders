<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'special_colors.inc');

require_login();

$title = _("Special Days");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n";

echo "<p>" . _("If a Special Day has been disabled, <span class='small'>[Disabled]</span> will be displayed following the name of the special day in the \"Name\" column.") . "</p>\n";

$sql = "
    SELECT *
    FROM special_days
    ORDER BY open_month, open_day
";
$result = DPDatabase::query($sql);

echo "<table class='list_special_days show_special_days'>";

$current_month = -1;

while ($row = mysqli_fetch_assoc($result)) {
    $month = $row['open_month'];

    // This handles the exceptions for the 'special' special days which aren't really
    // associated with specific dates, but are defined solely to create a queue.
    if ($month == 0 && $current_month != 0) {
        $current_month = $month;
        echo "<tr class='month'><td colspan='5'><h2>" . _("Undated Entries") . "</h2></td></tr>\n";
        output_column_headers();
    }

    if ($month != $current_month) {
        $current_month = $month;
        echo "<tr class='month'><td colspan='5'><h2>";
        echo strftime("%B", mktime(0, 0, 0, $row['open_month'], 10)) . "</h2></td></tr>\n";
        output_column_headers();
    }

    [$style, $symbol_cell] = get_special_day_cell_parts($row, false);
    echo "<tr>";
    echo "<td style='$style'>$symbol_cell</td>";
    echo "<td>";
    echo "<a href=\"$code_url/tools/search.php?show=search&amp;special_day%5B%5D=";
    echo urlencode($row['spec_code']) ."&amp;n_results_per_page=100\" title=\"";
    echo urlencode($row['display_name']) ."\">\n";
    echo html_safe($row['display_name']) . "</a>";
    if ($row['enable'] == 0) {
        $maybe_disabled = _("Disabled");
        echo "<br><span class='small'>[" . $maybe_disabled . "]</span>";
    }
    echo "</td>\n";
    echo "<td>";
    echo "<div title=\"" . html_safe($row['comment']) ."\">";
    echo html_safe($row['comment']) . "</div></td>\n";
    echo "<td class='center'>";
    if ($current_month != 0) {
        echo html_safe($row['open_day']);
    } else {
        // TRANSLATORS: N/A = "Not applicable"
        echo _("N/A");
    }
    echo "</td>\n";
    echo "<td>";
    echo "<a href='" . attr_safe($row['info_url']) . "'>";
    echo html_safe($row['info_url']) . "</a></td>\n";
    echo "</tr>\n";
}
echo "</table>";
echo "<br>\n";

// =============================================================================

function output_column_headers()
{
    echo "<tr>";
    echo "<th>" . _("Symbol") . "</th>";
    echo "<th>" . _("Name") . "</th>";
    echo "<th>" . _("Comment") . "</th>";
    // TRANSLATORS: Start Day is the day of the month that the special day occurs, such as 1 for New Year's Day
    echo "<th>" . _("Start Day") . "</th>";
    echo "<th>" . _("More Info") . "</th>";
    echo "</tr>\n";
}
