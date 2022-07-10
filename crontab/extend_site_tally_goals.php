<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

$res = DPDatabase::query("
    SELECT MAX(date) FROM site_tally_goals
");
[$current_max_date] = mysqli_fetch_row($res);

if (is_null($current_max_date)) {
    // The site_tally_goals table is empty.
    // We should probably assume that the
    // site admins want to keep it that way.
    exit;
}

$sql = sprintf("
    SELECT tally_name, goal
    FROM site_tally_goals
    WHERE date='%s'
", DPDatabase::escape($current_max_date));
$res2 = DPDatabase::query($sql);

$values_list = '';

// Ensure that the table is defined for at least the next 35 days.
$desired_max_date = date('Y-m-d', strtotime('+35 days'));

// What I'd *like* to be able to write:
// for ( $d = $current_max_date+1; $d <= today() + 35; $d++ )

for ($i = 1; ; $i++) {
    $date = date('Y-m-d', strtotime("$current_max_date + $i day"));
    if ($date > $desired_max_date) {
        break;
    }

    mysqli_data_seek($res2, 0) or die(DPDatabase::log_error());
    while ([$tally_name, $goal] = mysqli_fetch_row($res2)) {
        if (!empty($values_list)) {
            $values_list .= ',';
        }
        $values_list .= "( '$date', '$tally_name', $goal )\n";
    }

    $values_list .= "\n";
}

if (empty($values_list)) {
    return;
}

DPDatabase::query("
    INSERT INTO site_tally_goals
    VALUES
    $values_list
");
