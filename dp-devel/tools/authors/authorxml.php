<?
  $relPath = '../../pinc/';
  include_once($relPath.'v_site.inc');
  include_once($relPath.'connect.inc');
  $db_Connection=new dbConnect();
  $db_link=$db_Connection->db_lk;

  // Create xml data for all authors unless either
  //   an author_id is supplied (only that author)
  //   or
  //   a timestamp is supplied (only authors edited after that time)

  if (isset($_GET['author_id']) && !eregi('[^0-9]', $_GET['author_id'])) {
    $clause = "WHERE author_id = {$_GET['author_id']}";
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

  $result = mysql_query("SELECT * FROM authors $clause");

  if ($wrap_in_big_tag)
    echo "<authors>\n";

  while ($row = mysql_fetch_array($result))
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
    $res .= '" comments="' . htmlspecialchars($sql_row[$bd . 'comments'], ENT_QUOTES);
  $res .= "\" />\n";
  return $res;
}
?>