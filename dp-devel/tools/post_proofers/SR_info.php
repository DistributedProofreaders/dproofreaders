<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');
include($relPath.'theme.inc');

theme(_("Smooth Reading Project Information"), 'header');


/* $_GET $projectid, $proofstate, $proofing */

$projectid = $_GET['project'];


// Inefficient to do this twice (in echo_project_info and here).
// Get echo_project_info to take $project arg.
//
$project = mysql_fetch_assoc(mysql_query("
    SELECT * FROM projects WHERE projectid = '$projectid'
"));

$state = $project['state'];
$smooth_dead = $project['smoothread_deadline'];

// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

echo "<ul>\n";

echo "<li>";
echo "<a href='$code_url/tools/proofers/images_index.php?project=$projectid'>";
echo _("View Images Online");
echo "</a>";
echo "</li>";
echo "\n";

// ----------------------------------


if (($smooth_dead > time()) AND ($state==PROJ_POST_FIRST_CHECKED_OUT) ) {

// if available for smooth reading, the project can be uploaded
// by anyone without changing state - it's still checked out to PPer

   echo "<li>";
   $deadline = strftime(_("%A, %B %e, %Y"), $smooth_dead);
   echo _("This project has been made available for smoothreading until ")."<b>$deadline</b>";
   echo "<br><br>";
   $label =  _("Upload a smooth read version of the project") ;
   $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_done'>";
   echo $link_start.$label."</a><br>";
   echo "</ul>\n";
   echo_project_info( $projectid, 'proj_post', 0 , 1);
} else {
    echo "</ul>\n";
    echo_project_info( $projectid, 'proj_post', 0 );
}
echo "<BR>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
