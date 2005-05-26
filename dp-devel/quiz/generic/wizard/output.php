<?
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');

theme(_('Quiz Wizard'),'header');


function ssqs($x)
{
  return str_replace('\\\'','\'',$x);
}

function enl($x)
{
  $out = str_replace("\r\n",'\n',$x);
  return str_replace("\n",'\n',$out);
}

function sdbsn($x)
{
  return str_replace('\\\\n','\\n',$x);
}


function make_output()
{
  $out  = '<?PHP' . "\n\n";
  $out .= 'function quizsolved()';
  $out .= "{\n";
  $out .= "}\n\n";
  $out .= '$browser_title                = "' . ssqs($_SESSION['quiz_data']['browser_title']) . "\";\n";
  $out .= '$welcome                      = "' . ssqs($_SESSION['quiz_data']['welcome']) . "\";\n";
  $out .= '$ocr_text                     = "' . enl(ssqs($_SESSION['quiz_data']['ocr_text'])) . "\";\n";
  $out .= '$solutions                    = array("';
  $out .= enl(ssqs($_SESSION['quiz_data']['solutions'][0])) . '"';
  if(isset($_SESSION['quiz_data']['solutions'][1]))
    $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][1])) . '"';
  if(isset($_SESSION['quiz_data']['solutions'][2]))
    $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][2])) . '"';
  if(isset($_SESSION['quiz_data']['solutions'][3]))
    $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][3])) . '"';
  $out .= ");\n";
  if ($_SESSION['quiz_data']['showsolution'])
    $out .= '$showsolution                 = TRUE;' . "\n";
  else
    $out .= '$showsolution                 = FALSE;' . "\n";
  $out .= '$solved_message               = "' . ssqs($_SESSION['quiz_data']['solved_message']) . "\";\n";
  $out .= '$links_out                    = "' . ssqs($_SESSION['quiz_data']['links_out']) . "\";\n\n\n";
  $out .= '// error messages' . "\n\n";  
  foreach($_SESSION['quiz_data']['messages'] as $name => $err)
  {
    $out .= '$messages["';
    $out .= ssqs($name);
    $out .= '"] = array("message_text" => "';
    $out .= ssqs($err['message_text']);
    $out .= '"';
    if ($err['challengetext'] != "")
    {
      $out .= ', "challengetext" => "';
      $out .= ssqs($err['challengetext']);
      $out .= '"';
    };
    if ($err['feedbacktext'] != "")
    {
      $out .= ', "feedbacktext" => "';
      $out .= ssqs($err['feedbacktext']);
      $out .= '"';
    };
    $out .= ', "hints" => array(';
    if (isset($err['hints'][0]))
    {
      $out .= 'array("hint_text" => "';
      $out .= ssqs($err['hints'][0]['hint_text']);
      $out .= '"';
      if ($err['hints'][0]['linktext'] != "")
      {
        $out .= ', "linktext" => "';
        $out .= ssqs($err['hints'][0]['linktext']);
        $out .= '"';
      };
      $out .= ')';
      if (isset($err['hints'][1]))
      {
        $out .= ', array("hint_text" => "';
        $out .= ssqs($err['hints'][1]['hint_text']);
        $out .= '"';
        if ($err['hints'][1]['linktext'] != "")
        {
          $out .= ', "linktext" => "';
          $out .= ssqs($err['hints'][1]['linktext']);
          $out .= '"';
        };
        $out .= ')';
      };
    };
    $out .= "));\n";    
  };
  $out .= "\n\n";
  $out .= "// error checks\n\n";
  foreach($_SESSION['quiz_data']['tests'] as $key => $test)
  {
    $out .= '$tests[] = array("type" => "' . $test['type'] .'"';
    if (($test['type'] == 'forbiddentext') || ($test['type'] == 'multioccurrence'))
    {
      $out .= ', "searchtext" => "' . ssqs(sdbsn($test['searchtext'])) .'", "case_sensitive" => ';
      if ($test['case_sensitive'])
        $out .= 'TRUE';
      else
        $out .= 'FALSE';
      $out .= ', "error" => "';
      $out .= $test['error'];
      $out .= '"';
    }
    elseif ($test['type'] == 'expectedtext')
    {
      $out .= ', "searchtext" =>  array(';
      foreach(ssqs(sdbsn($test['searchtext'])) as $numsearch => $search)
      {
        if ($numsearch != 0)
          $out .= ', ';
        $out .= '"' . $search . '"';
      }
      $out .= '), "case_sensitive" => ';
      if ($test['case_sensitive'])
        $out .= 'TRUE';
      else
        $out .= 'FALSE';
      $out .= ', "error" => "';
      $out .= $test['error'];
      $out .= '"';    
    }
    elseif ($test['type'] == 'wrongtextorder')
    {
      $out .= ', "firsttext" => "' . ssqs(sdbsn($test['firsttext'])) .'", "secondtext" => "' . ssqs(sdbsn($test['secondtext'])) .'", "case_sensitive" => ';
      if ($test['case_sensitive'])
        $out .= 'TRUE';
      else
        $out .= 'FALSE';
      $out .= ', "error" => "';
      $out .= $test['error'];
      $out .= '"';
    }
    elseif (($test['type'] == 'markupmissing') || ($test['type'] == 'markupcorrupt'))
    {
      $out .= ', "opentext" => "' . ssqs(sdbsn($test['opentext'])) .'", "closetext" => "' . ssqs(sdbsn($test['closetext'])) .'", "case_sensitive" => ';
      if ($test['case_sensitive'])
        $out .= 'TRUE';
      else
        $out .= 'FALSE';
      $out .= ', "error" => "';
      $out .= $test['error'];
      $out .= '"';
    }
    elseif ($test['type'] == 'expectedlinebreaks')
    {
      $out .= ', "starttext" => "' . ssqs(sdbsn($test['starttext'])) . '", "stoptext" => "' . ssqs(sdbsn($test['stoptext'])) . '", "number" => ' . ssqs(sdbsn($test['number'])) .', "case_sensitive" => ';
      if ($test['case_sensitive'])
        $out .= 'TRUE';
      else
        $out .= 'FALSE';
      $out .= ', "errorlow" => "';
      $out .= $test['errorlow'];
      $out .= '"';
      $out .= ', "errorhigh" => "';
      $out .= $test['errorhigh'];
      $out .= '"';
    }
    elseif ($test['type'] == 'longline')
    {
      $out .= ', "lengthlimit" => ' . ssqs(sdbsn($test['lengthlimit']));
      $out .= ', "error" => "';
      $out .= $test['error'];
      $out .= '"';
    };
    $out .= ');' . "\n";    
  };
    
  $out .= "\n\n?>";
  return htmlspecialchars($out);
}

?>
<h2>Done!</h2>
<p>Below you will find some prepared php code. Choose a unique name for your quiz (&lt;yourquizname&gt;),
copy the code in an editor and save it under the name qd_&lt;yourquizname&gt;.inc. Save the image you've prepared 
under the name qi_&lt;yourquizname&gt;.png. For testing the quiz it is highly recommended to contact a developer with
a sandbox on the DP test server (www.pgdp.org). The inc file has to be placed in /quiz/generic/data and the image in 
/quiz/generic/images. When that has happened you can call your quiz under the URL http://www.pgdp.org/~&lt;user&gt;/c/quiz/generic/main.php?type=&lt;yourquizname&gt;, 
where &lt;user&gt; is the username of the sandbox 'owner'.

Once it is on the real site, the URL will be  http://www.pgdp.net/c/quiz/generic/main.php?type=&lt;yourquizname&gt;</p>

<p>You can also <a href="./messages.php">and enter more <a href="./messages.php">error messages</a>
or <a href="./checks.php">error tests</a> if you missed any.</p>

<p>
<table bgcolor="#AAAAAA"><tr><td>
<pre>
<? echo make_output(); ?>
</pre>
</td></tr></table>
</p>


<a href="./start.php">Clear all data and restart quiz wizard.</a>

<?
$_SESSION['quiz_data']['lastpage'] = 'output';

theme("", "footer");
?>
