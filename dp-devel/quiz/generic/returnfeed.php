<? 
$relPath='../../../pinc/';
include_once('../small_theme.inc');
include './data/qd_' . $_REQUEST['type'] . '.php';

function in_string($needle, $haystack, $sensitive = 0) 
{
   if ($sensitive) 
     return (false !== strpos($haystack, $needle))  ? true : false;
   else
     return (false !== stristr($haystack, $needle)) ? true : false;
} 

function stripos($haystack,$needle,$offset = 0)
{
  return(strpos(strtolower($haystack),strtolower($needle),$offset));
}

function error_check()
{
  global $tests;
  $text = $_POST['output'];
  foreach ($tests as $key => $value)
  {
    if ($value["type"]=="forbiddentext") 
    {
      if (in_string($value["searchtext"],$text,$value["case_sensitive"]))
      {
        return $value["error"];
      };
    };
    if ($value["type"]=="markupmissing") 
    {
      if (!in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
      {
        return $value["error"];
      };
    };
    if ($value["type"]=="markupcorrupt") 
    {
      if ((in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
          || (!in_string($value["opentext"],$text,$value["case_sensitive"]) && in_string($value["closetext"],$text,$value["case_sensitive"]))
          || ((!$value["case_sensitive"]) && (stripos($text, $value["closetext"]) < stripos($text, $value["opentext"])))
          || (($value["case_sensitive"]) && (strpos($text, $value["closetext"]) < strpos($text, $value["opentext"])))  )
      {
        return $value["error"];
      };
    };
  };

  return "";
};

// A margin
echo "<div style='margin: .5em;'>";

$error_found = error_check();
if ($error_found == "")
{
  echo $solved_message;
  echo "<p>";
  echo $links_out;
}
else
{
  echo $messages[$error_found]["message_text"];
  echo "<p>";
  if (count($messages[$error_found]["hints"]) > 0)
  {
    if (isset($messages[$error_found]["hints"][0]["linktext"]))
    {
      echo $messages[$error_found]["hints"][0]["linktext"];
    }
    else
    {
      echo "Desperate? Can't find it?";
    };
    echo " Get more hints <a href='./hints.php?type=" .$_REQUEST['type'] . "&error=" . $error_found . "&number=0'>here</a>.<p>";
  };
  if (isset($messages[$error_found]["challengetext"]))
  {
    echo $messages[$error_found]["challengetext"];
  }
  else
  {
    echo "Try to correct that or press 'restart' to restart.";
  };
  echo "<p>";
  if (isset($messages[$error_found]["feedbacktext"]))
  {
    echo $messages[$error_found]["feedbacktext"];
  }
  else
  {
    echo "The algorithm for finding errors in this quiz is a quite simple one. If you feel the message doesn't make any sense, please post a feedback message in <a href='http://www.pgdp.net/phpBB2/viewtopic.php?t=9165' target='_blank'>this forum topic</a>.";
  };
};
 ?>
 </div>
</body>
</html>
