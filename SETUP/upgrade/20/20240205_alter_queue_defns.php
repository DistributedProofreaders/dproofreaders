<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'release_queue.inc'); // MAX_PROJECTS_TARGET

header('Content-type: text/plain');

// ------------------------------------------------------------

// Get data from the existing table
$sql = "
    SELECT id, round_id, name, release_criterion
    FROM queue_defns
    ORDER BY id
";
$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
$n_rows = mysqli_num_rows($result);

// Make a first pass over the table to check that it can be converted without errors.
echo "Checking $n_rows rows for convertability...\n";
$n_conversion_errors = 0;
while ([$id, $round_id, $name, $release_criterion] = mysqli_fetch_row($result)) {
    $queue_ident = "queue $id (round_id='$round_id', name='$name')";
    // echo "$queue_ident:\n";
    $targets_array = convert($release_criterion, $queue_ident);
}

if ($n_conversion_errors) {
    echo "\n";
    echo "Aborting (leaving the queue_defns table unchanged) due to $n_conversion_errors conversion errors noted above.\n";
    echo "You might be able to modify this script to handle the problematic release_criterion values.\n";
    echo "If not, you'll need to change those values in the table to something this script can handle.\n";
    exit;
}

// ---------

echo "\n";
echo "All release_criterion values can be converted, so proceeding to upgrade the table.\n";
echo "\n";
echo "Adding projects_target and pages_target to queue_defns...\n";
$sql = "
    ALTER TABLE queue_defns
    ADD COLUMN projects_target SMALLINT UNSIGNED NOT NULL AFTER release_criterion,
    ADD COLUMN pages_target MEDIUMINT UNSIGNED NOT NULL AFTER projects_target
";
mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

if ($n_rows > 0) {
    echo "Converting the release_criterion in {$n_rows} rows ...\n";

    mysqli_data_seek($result, 0) or die(DPDatabase::log_error());

    $n_conversion_errors = 0;
    while ([$id, $round_id, $name, $release_criterion] = mysqli_fetch_row($result)) {
        $queue_ident = "queue $id (round_id='$round_id', name='$name')";
        // echo "$queue_ident:\n";

        $targets_array = convert($release_criterion, $queue_ident);

        $setters = 'projects_target = ' . array_get($targets_array, 'projects', 0)
            . ', pages_target = ' . array_get($targets_array, 'pages', 0);
        $sql = "UPDATE queue_defns SET $setters WHERE id=$id\n";
        // echo "$sql\n";
        $res = mysqli_query(DPDatabase::get_connection(), $sql) or die("At $queue_ident, " . mysqli_error(DPDatabase::get_connection()));
    }

    if ($n_conversion_errors) {
        // This can't happen?
        echo "Aborting (leaving 'release_criterion' column in place) due to $n_conversion_errors conversion errors noted above.\n";
        exit;
    }
}

echo "Dropping the release_criterion column ...\n";
$sql = "
    ALTER TABLE queue_defns
    DROP COLUMN release_criterion
";
mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

// ------------------------------------------------------------

/**
 * Convert an expression-style release_criterion
 * into project- and page-targets.
 *
 * @param string $rc
 *   An expression-style release_criterion,
 *   typically a condition involving 'pages' and/or 'projects'.
 *   (e.g., "pages < 60 or projects < 3")
 *
 * @return array
 *   An array that gives the targets for
 *   (possibly a subset of) 'pages' and 'projects'.
 *   (e.g., ['pages' => '60', 'projects' => '3'])
 */
function convert($rc, $queue_ident)
{
    global $n_conversion_errors;

    $rc = trim($rc);

    if (preg_match('/^(1 *= *0|FALSE)$/', $rc)) {
        // The release_criterion is always False.
        // That is, the queue never releases.
        return [];

    } elseif ($rc == "TRUE") {
        // The release_criterion is always True.
        // That is, the queue always releases.
        // Set a ridiculously high limit for projects.
        return ['projects' => MAX_PROJECTS_TARGET];

    } elseif (preg_match('/^(pages|projects) *< *(\d+)$/', $rc, $matches)) {
        return [$matches[1] => $matches[2]];

    } elseif (preg_match('/^\(([^()]+)\)$/', $rc, $matches)) {
        // strip outer parentheses
        return convert($matches[1], $queue_ident);

    } elseif (preg_match('/^(.+) or (.+)$/', $rc, $matches)) {
        $l = convert($matches[1], $queue_ident);
        $r = convert($matches[2], $queue_ident);

        // The two sub-conditions should be about different targets.
        assert(array_intersect_key($l, $r) == []);

        return $l + $r;

    } else {
        echo "Error: For $queue_ident, unable to convert condition '$rc'\n";
        $n_conversion_errors += 1;
        return [];
    }
}
