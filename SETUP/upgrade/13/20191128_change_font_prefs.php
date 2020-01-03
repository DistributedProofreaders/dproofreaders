<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'prefs_options.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

// Here are the old font face mappings we want to move to a new
// x_fnf_other column.
$old_font_mapping = [
    3 => 'Arial',
    1 => 'Courier',
    4 => 'Lucida',
    7 => 'Lucida Console',
    8 => 'Monaco',
    2 => 'Times',
];

// Not included are the following, which we want to treat differently
//   0 => '' == the browser default which we leave alone
//   5 => 'monospace' == we change to 0
//   6 => 'DPCustomMono2' == we leave alone

// ------------------------------------------------------------

echo "Adding varchar columns to user_profiles...\n";
$sql = "
    ALTER TABLE user_profiles
        ADD COLUMN v_fntf_other varchar(32) default '' AFTER v_fntf,
        ADD COLUMN h_fntf_other varchar(32) default '' AFTER h_fntf;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Changing 'monospace' to 'browser default' which are now synonymous...\n";
$sql = "
    UPDATE user_profiles SET v_fntf = 0 where v_fntf = 5;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$sql = "
    UPDATE user_profiles SET h_fntf = 0 where h_fntf = 5;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Populating new columns with prior font selection...\n";

foreach($old_font_mapping as $index => $string)
{
    $sql = sprintf("
        UPDATE user_profiles
        SET v_fntf_other = '%s', v_fntf = 1
        WHERE v_fntf = $index
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $string));

    echo "$sql\n";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

    $sql = sprintf("
        UPDATE user_profiles
        SET h_fntf_other = '%s', h_fntf = 1
        WHERE h_fntf = $index
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $string));

    echo "$sql\n";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ------------------------------------------------------------

$current_fonts = array_flip(get_available_proofreading_font_faces());
$dvsm_index = $current_fonts['DejaVu Sans Mono'];

echo "Updating default font to DejaVu Sans Mono...\n";

$sql = "
    ALTER TABLE user_profiles ALTER v_fntf SET DEFAULT $dvsm_index
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$sql = "
    ALTER TABLE user_profiles ALTER h_fntf SET DEFAULT $dvsm_index
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
