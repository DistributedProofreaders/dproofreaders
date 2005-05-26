<? 
$relPath='../../pinc/';
include_once('../small_theme.inc');
include './data/qd_' . $_REQUEST['type'] . '.inc';


// A margin
echo "<div style='margin: .5em;'>";

echo $messages[$_REQUEST['error']]["hints"][(int) $_REQUEST['number']]["hint_text"];
echo "<p>";
if (count($messages[$_REQUEST['error']]["hints"]) > (1 + $_REQUEST['number']))
{
  if (isset($messages[$_REQUEST['error']]["hints"][(int) $_REQUEST['number']]["linktext"]))
  {
    echo $messages[$_REQUEST['error']]["hints"][(int) $_REQUEST['number']]["linktext"];
  }
  else
  {
    echo "Desperate? Can't find it?";
  };
  echo " Get more hints <a href='./hints.php?type=" .$_REQUEST['type'] . "&error=" . $_REQUEST['error'] . "&number=" . ($_REQUEST['number'] + 1) . "'>here</a>.<p>";
};

 ?>
 </div>
</body>
</html>
