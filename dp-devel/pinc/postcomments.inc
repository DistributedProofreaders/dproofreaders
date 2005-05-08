<?

function get_formatted_postcomments( $projectid )
// Return the PP-ers' comments for a certain project
// They will be HTML-encoded and with line breaks
// converted to <br/> .
{
  $res = mysql_query("SELECT postcomments FROM projects WHERE projectid='$projectid'");
  $postcomments = mysql_result($res, 0, 'postcomments');

  return htmlspecialcharswithnewlines($postcomments);
}

function htmlspecialcharswithnewlines($string)
{
  $string = htmlspecialchars($string);
  $string = str_replace("\n", "<br />", $string);
  return $string;
}

?>