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

function strposgen($haystack,$needle,$cs)
{
  if ($cs)
    return strpos($haystack,$needle);
  else
    return stripos($haystack,$needle);
}

function multilinertrim($x)
{
  $arr = explode("\n",$x);
  foreach($arr as $line)
  {
    $out[] = rtrim($line);
  }
  return implode("\n",$out);
}

function numberofoccurances($haystack, $needle, $cs)
{
  if (!$cs)
  {
    $needle = strtolower($needle);
    $haystack =  strtolower($haystack);
  };
  return substr_count($haystack, $needle);
};

function diff($s1, $s2)
{
  $arr1 = explode("\n",$s1);
  $arr2 = explode("\n",$s2);
  if (count($arr2) > count($arr1))
  {
    $arrdummy = $arr1;
    $arr1 = $arr2;
    $arr2 = $arrdummy;
  };
  foreach($arr1 as $key => $line1)
  {
    if (isset($arr2[$key]))
    {
      if ($line1 != $arr2[$key])
        return $line1 . "\n" . $arr2[$key];
    }
    else
    {
      if ($line1 != "")
      	return $line1;
    };
  }
}

function finddiff()
{
  global $text;
  global $solutions;
  global $showsolution;
  
  foreach ($solutions as $solution)
  {
    $d = diff($text,$solution);
    if ($d == "")
      return FALSE;
  };
  echo "<h2>Difference with expected text</h2>";
  echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p>";
  if (count($solutions) == 1)
  {
    echo "<p>This is the first differing line:<br>";
    echo "<pre>\n";
    echo $d;
    echo "\n</pre></p>";
  }
  else
  {
    if ($showsolution)
    {
      echo "<p>This is the expected text:<br>";
      echo "<pre>\n";
      echo $solutions[0];
      echo "\n</pre></p>";
    };
  };
  return TRUE;
}

function error_check()
{
  global $tests;
  global $text;
  $text = multilinertrim(stripslashes($_POST['output']));
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
    if ($value["type"]=="expectedtext") 
    {
      $found = FALSE;
      foreach ($value["searchtext"] as $expected)
      {
        if (in_string($expected,$text,$value["case_sensitive"]))
        {
          $found = TRUE;
        };
      };
      if (!$found)
      {
        return $value["error"];
      };
    };
    if ($value["type"]=="expectedlinebreaks") 
    {
      $len = strlen($value["starttext"]);
      if ($value["case_sensitive"])
      {
        $part = strstr($text,$value["starttext"]);
        $part= substr($part, $len, strpos($part,$value["stoptext"]) - $len);
      }
      else
      {
        $part = stristr($text,$value["starttext"]);
        $part= substr($part, $len, stripos($part,$value["stoptext"]) - $len);
      };
      $num = numberofoccurances($part, "\n", TRUE);
      if ($num < $value["number"])
      {
        return $value["errorlow"];
      };
      if ($num > $value["number"])
      {
        return $value["errorhigh"];
      };
    };
    if ($value["type"]=="multioccurrence") 
    {
      if (numberofoccurances($text, $value["searchtext"], $value["case_sensitive"]) > 1)
      {
        return $value["error"];
      };
    };
    if ($value["type"]=="wrongtextorder") 
    {
      $p1 = strposgen($text,$value["firsttext"],$value["case_sensitive"]);
      $p2 = strposgen($text,$value["secondtext"],$value["case_sensitive"]);
      if ($p1 && $p2 && ($p1 > $p2))
      {
        return $value["error"];
      };
    };
    if ($value["type"]=="longline") 
    {
      $arr = explode("\n", $text);
      foreach($arr as $line)
      {
        if (strlen($line) > $value["lengthlimit"])
        {
          return $value["error"];
        };
      };
    };
// todo:
//
// diff
//
  };

  return "";
};

// A margin
echo "<div style='margin: .5em;'>";

$error_found = error_check();
if ($error_found == "")
{
  $d = finddiff();
  if (!$d)
  {
    echo $solved_message;
    echo "<p>";
    echo $links_out;
  };
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