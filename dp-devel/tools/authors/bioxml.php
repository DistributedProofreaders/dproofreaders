<?
  $relPath = '../../pinc/';
  include_once($relPath.'v_site.inc');
  include_once($relPath.'connect.inc');
  $db_Connection=new dbConnect();
  $db_link=$db_Connection->db_lk;

  // Create xml data for all bios unless either
  //   an author_id is supplied (only that author's bios)
  //   or
  //   a bio_id is supllied (only that bio)
  //   or
  //   a timestamp is supplied (only bios edited after that time)

  if (isset($_GET['author_id']) && !eregi('[^0-9]', $_GET['author_id'])) {
    $clause = "WHERE author_id = {$_GET['author_id']}";
    $wrap_in_big_tag = true;
  }
  if (isset($_GET['bio_id']) && !eregi('[^0-9]', $_GET['bio_id'])) {
    $clause = "WHERE bio_id = {$_GET['bio_id']}";
    $wrap_in_big_tag = false;
  }
  else if (isset($_GET['modified_since']) && !eregi('[^0-9]', $_GET['modified_since'])) {
    // Pad timestamp with zeroes.
    // This means a date, e.g. 20040810, will be sent to
    // the parser as a timestamp, e.g. 20040810000000
    $last_modified = str_pad($_GET['modified_since'], 14, '0');
    $clause = "WHERE last_modified >= $last_modified";
    $wrap_in_big_tag = true;
  }
  else {
    $clause = '';
    $wrap_in_big_tag = true;
  }

  header("Content-Type: text/xml");
  echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";

  $result = mysql_query("SELECT * FROM biographies $clause");

  if ($wrap_in_big_tag)
    echo "<biographies>\n";

  while ($row = mysql_fetch_array($result))
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
?>