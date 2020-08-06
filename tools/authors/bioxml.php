<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_login();

// Create xml data for all bios unless either
//   an author_id is supplied (only that author's bios)
//   or
//   a bio_id is supllied (only that bio)
//   or
//   a timestamp is supplied (only bios edited after that time)

$author_id      = get_integer_param($_GET, 'author_id', null, 0, null, true);
$bio_id         = get_integer_param($_GET, 'bio_id', null, 0, null, true);
$modified_since = get_integer_param($_GET, 'modified_since', null, 0, null, true);
if (isset($author_id)) {
    $clause = sprintf("WHERE author_id = %d", $author_id);
    $wrap_in_big_tag = true;
}
if (isset($bio_id)) {
    $clause = sprintf("WHERE bio_id = %d", $bio_id);
    $wrap_in_big_tag = false;
}
else if (isset($modified_since)) {
    // Pad timestamp with zeroes.
    // This means a date, e.g. 20040810, will be sent to
    // the parser as a timestamp, e.g. 20040810000000
    $last_modified = str_pad($modified_since, 14, '0');
    $clause = sprintf("WHERE last_modified >= %d", $last_modified);
    $wrap_in_big_tag = true;
}
else {
    $clause = '';
    $wrap_in_big_tag = true;
}

header("Content-Type: text/xml; charset=$charset");
echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";

$sql = "SELECT * FROM biographies $clause";
$result = DPDatabase::query($sql);

if ($wrap_in_big_tag)
    echo "<biographies>\n";

while ($row = mysqli_fetch_array($result))
    echo create_bio_data($row);

if ($wrap_in_big_tag)
    echo '</biographies>';

function create_bio_data($sql_row) {
$bio = $sql_row['bio'];
// in case the bio data contains ']]>' (without quote marks)
// it would be treated as the end of the CDATA block. Thus I split the block in the middle
// of this sequence. I have not seen anything on what to do when ]]> occurs, and this
// is the only solution I can think of.
$bio = preg_replace('/]]>/', ']]]><![CDATA[]>', $bio);

$xml = "<biography id=\"{$sql_row['bio_id']}\" " .
         "author_id=\"{$sql_row['author_id']}\" last_modified=\"{$sql_row['last_modified']}\">";
$xml .= "<![CDATA[$bio]]>";
$xml .= "</biography>\n";
return $xml;
}

// vim: sw=4 ts=4 expandtab
