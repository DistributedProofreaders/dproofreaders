<?
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once('sendtopost.php');

$dbConnection = new dbConnect();

foreach ( array('projectID3ff8d4d07cefe','projectID3ff8d5bf11840') as $projectid )
{
	echo "<p>generating files for $projectid...</p>\n";
	flush();
	generate_post_files( $projectid );
	flush();
}
?>
