<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // html_safe()

require_login();

// Create xml data for all authors unless either
//   an author_id is supplied (only that author)
//   or
//   a timestamp is supplied (only authors edited after that time)
$author_id      = get_integer_param($_GET, 'author_id', null, 0, null, true);
$modified_since = get_integer_param($_GET, 'modified_since', null, 0, null, true);

if ($author_id) {
    $clause = sprintf("WHERE author_id = %d", $author_id);
    $wrap_in_big_tag = false;
}
else if ($modified_since) {
    // Pad timestamp with zeroes.
    // This means a date, e.g. 20040810, will be sent to
    // the parser as a timestamp, e.g. 20040810000000
    $modified_since = str_pad($modified_since, 14, '0');
    $clause = sprintf("WHERE last_modified >= %d", $modified_since);
    $wrap_in_big_tag = true;
}
else {
    $clause = '';
    $wrap_in_big_tag = true;
}

header("Content-Type: text/xml; charset=$charset");
echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";

$sql = "SELECT * FROM authors $clause";
$result = DPDatabase::query($sql);

if ($wrap_in_big_tag)
    echo "<authors>\n";

while ($row = mysqli_fetch_array($result))
    echo create_author_data($row);

if ($wrap_in_big_tag)
    echo '</authors>';

function create_author_data($sql_row) {
    $xml = "<author id=\"{$sql_row['author_id']}\" last_modified=\"{$sql_row['last_modified']}\">\n";
    $xml .= "<lastname>{$sql_row['last_name']}</lastname>\n";
    $xml .= "<othernames>{$sql_row['other_names']}</othernames>\n";
    $xml .= "<birth>\n";
    $xml .= create_birth_or_death_data('b', $sql_row);
    $xml .= "</birth>\n";
    $xml .= "<death>\n";
    $xml .= create_birth_or_death_data('d', $sql_row);
    $xml .= "</death>\n";
    $xml .= "</author>\n";
    return $xml;
}

// $bd is 'b' or 'd'
function create_birth_or_death_data($bd, $sql_row) {
    $res = '<date year="' . $sql_row[$bd . 'year' ] .
              '" month="' . $sql_row[$bd . 'month'] .
                '" day="' . $sql_row[$bd . 'day'  ];
    if ($sql_row[$bd . 'comments'] != '')
        $res .= '" comments="' . html_safe($sql_row[$bd . 'comments']);
    $res .= "\">\n";
    return $res;
}

// vim: sw=4 ts=4 expandtab
