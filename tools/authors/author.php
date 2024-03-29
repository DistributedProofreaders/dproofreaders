<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('authors.inc');
include_once('menu.inc');

require_login();

$author_id = get_integer_param($_GET, 'author_id', null, null, null, false);

$sql = sprintf("SELECT * FROM authors WHERE author_id=%d", $author_id);
$result = DPDatabase::query($sql);
$row = mysqli_fetch_assoc($result);
if (!$row) {
    output_header('');
    echo "<p class='error'>", sprintf(_('Author id %d not found'), $author_id), "</p>\n";
    exit;
}
$last_name = $row["last_name"];
$other_names = $row["other_names"];
$birth = format_date_from_sqlset($row, 'b');
$decease = format_date_from_sqlset($row, 'd');

// Start outputting
output_header(_('Author') . ': ' . $last_name . ($other_names != '' ? ", $other_names" : ''));

echo '<h1>' . _('Author') . '</h1>';

echo_menu($author_id);

echo_author($last_name, $other_names, $birth, $decease, $author_id);
