<?
include_once($relPath."connect.inc");
class Quiz
{
  function Quiz($id,$name,$pages, $pass_requirements)
  {
    $this->id = $id;
    $this->name = $name;
    $this->pages = $pages;
    $this->pass_requirements = $pass_requirements;
    // Currently supported pass_requirements:
    // ['maximum_age'] => time_in_seconds:
    //     Passes recorded longer than time_in_seconds ago are not valid.
  }

  function user_has_passed($username)
  {
    foreach ($this->pages as $page) 
    {
      // false would be more logical than string "no",
      // but you can't array_search on a boolean.
      $pages_required_results[$page] = "no";
    }

    $result = mysql_query("SELECT * FROM `quiz_passes` WHERE `username` ='$username'");
    while ($attempt = mysql_fetch_object($result))
    {
      if($attempt->result != "pass") continue;
      if(array_key_exists($attempt->quiz_page,$pages_required_results))
      {
        $pages_required_results[$attempt->quiz_page] = $attempt->date;
      }
      else
      {
        //This quiz page isn't relevant to this quiz.
        continue;
      }
    }

    if (isset($this->pass_requirements['maximum_age']))
    {
      foreach ($pages_required_results as $page => $value)
      {
        if($value == "no") continue; // The user hasn't passed the quiz anyway.
        if ((time() - $value) > $this->pass_requirements['maximum_age'])
        {
          $pages_required_results[$page] = "no";
        }
      }
    }

    // At this point, if a user has passed the quiz, the $pages_required_results array should
    // have only pagename => timestamp  rows. If any false values remain, the user has not passed
    // all the pages required, or the result of that page is invalid (e.g. too old).
    if (array_search("no",$pages_required_results) !== false)
    {
      return false;
    }
    else
    {
      return true;
    }
  }
}

# -----------------------------------------------------------------------------

function record_quiz_attempt($username, $quiz_page, $result)
{
  new dbConnect();
  $res = mysql_query("SELECT * FROM `quiz_passes`
  WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  if (mysql_num_rows($res) > 0)
  {
    // The user has already passed this page; update the timestamp
    mysql_query("UPDATE `quiz_passes` SET `date` = '".time()."'
    WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  }
  else
  {
    mysql_query("INSERT INTO `quiz_passes` VALUES('$username','".time()."','$quiz_page','$result')");
  }
}

function user_has_passed_quiz_page($username, $quiz_page, $pass_requirements = array())
{
  $res = mysql_query("SELECT * FROM `quiz_passes`
  WHERE `username` ='$username' AND `quiz_page` ='$quiz_page'");
  if (mysql_num_rows($res) == 0)
      return false;
  if (isset($pass_requirements['minimum_age']))
  {
    if ((time() - $value) > $this->pass_requirements['maximum_age'])
        return false;
  }
  return true;
}
?>
