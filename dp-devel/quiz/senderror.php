<?
$relPath='../../../pinc/';
include_once('small_theme.inc');



$feedb = $_GET[feedb];
$date = gmdate("j/n/y Hi");
$comment = $_GET[comment];
$tutstep = $_GET[tutstep];
$text = $_GET[text];

$filename = '../feedback.txt';
$fp = fopen($filename, "a");
$string = "\n$date GMT | Stage $tutstep | feedb: $feedb | Comment: $comment
$text
";
$write = fputs($fp, $string);
fclose($fp);

echo "Your feedback has been sent. Thankyou for your input<br>\n";
echo "<a href='./main.php'>Return to the tutorial</a>";
