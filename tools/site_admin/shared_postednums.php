<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'user_is.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

slim_header("Same postednum");

echo "<h1>Cases where multiple projects have the same postednum</h1>\n";

echo "<p>
    When multiple projects have the same postednum,
    this usually means that a single book was split into multiple projects
    to go through the rounds.
    And when this is easy to detect (titles differ only by a digit),
    this page skips over the set.
    However, a shared postednum could also happen
    when a project was mistakenly assigned another project's postednum.
    This page should help find those cases.
</p>
";

$res = DPDatabase::query("
    SELECT postednum, COUNT(*) as c
    FROM projects
    GROUP BY postednum
    HAVING c > 1
    ORDER BY postednum
");
while ([$postednum, $count] = mysqli_fetch_row($res)) {
    if (is_null($postednum)) {
        continue;
    }

    echo "<br>$postednum:\n";

    {
        $res2 = DPDatabase::query("
            SELECT nameofwork
            FROM projects
            WHERE postednum=$postednum
            ORDER BY nameofwork
        ");
        $titles = [];
        while ([$title] = mysqli_fetch_row($res2)) {
            $titles[] = $title;
        }
        [$left, $middles, $right] = factor_strings($titles);
        if (strings_count_up($middles)) {
            echo "skipping '$left&lt;N&gt;$right'...<br>\n";
            continue;
        }
    }

    dpsql_dump_query("
        SELECT FROM_UNIXTIME(modifieddate), state, nameofwork
        FROM projects
        WHERE postednum=$postednum
        ORDER BY nameofwork
    ");
}

/**
 * Determine if the given strings contain numerals '1', '2', '3' that go up
 */
function strings_count_up(array $strings): bool
{
    for ($i = 0; $i < count($strings); $i++) {
        if ($strings[$i] == (''.($i + 1))) {
            // good so far
        } else {
            return false;
        }
    }
    return true;
}
