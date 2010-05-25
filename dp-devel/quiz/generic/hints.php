<?php 
$relPath='../../pinc/';
include_once('../small_theme.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);

include "./data/qd_${page_id}.inc";

$error = get_enumerated_param($_REQUEST, 'error', NULL, array_keys($messages));
$number = get_integer_param($_REQUEST, 'number', NULL, 0, count($messages[$error]["hints"])-1);

// A margin
echo "<div style='margin: .5em;'>";

// Display current hint
echo $messages[$error]["hints"][$number]["hint_text"];
echo "<p>";

// If there are any further hints for this message,
// display a link to the next hint.
if (count($messages[$error]["hints"]) > (1 + $number))
{
  if (isset($messages[$error]["hints"][$number]["linktext"]))
  {
    echo $messages[$error]["hints"][$number]["linktext"];
  }
  else
  {
    echo _("Desperate? Can't find it?");
  }
  echo sprintf( _("Get more hints <a href='%s'>here</a>.<p>"), "./hints.php?type=" . $page_id . "&error=" . $error . "&number=" . ($number + 1));
}

 ?>
 </div>
</body>
</html>
