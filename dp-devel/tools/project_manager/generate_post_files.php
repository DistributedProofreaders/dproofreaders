<?
$relPath="./../../pinc/";
include_once($relPath.'user_is.inc');
include_once($relPath.'connect.inc');
include_once('sendtopost.php');

$dbConnection = new dbConnect();

if ( !user_is_a_sitemanager() and !user_is_proj_facilitator() )
{
	echo "<p>You are not allowed to run this script.</p>";
	exit();
}

foreach ( array('projectID3ff8d4d07cefe','projectID3ff8d5bf11840') as $projectid )
{
	echo "<p>generating files for $projectid...</p>";
	generate_post_files( $projectid );
}
?>
