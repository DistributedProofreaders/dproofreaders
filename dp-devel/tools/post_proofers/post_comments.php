<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');
include($relPath.'theme.inc');

theme('Project Information', 'header');

if (!user_is_PP()) {
	echo "You're not recorded as a PPer. If you feel this is an error, please contact site administration.";
	exit();
}

/* $_GET $projectid, $proofstate, $proofing */

$projectid = $_GET['project'];

echo_project_info( $projectid, 'proj_post', 0 );
echo "<BR>";

// Inefficient to do this twice (in echo_project_info and here).
// Get echo_project_info to take $project arg.
//
$project = mysql_fetch_assoc(mysql_query("
    SELECT * FROM projects WHERE projectid = '$projectid'
"));

$state = $project['state'];

// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

echo "<ul>\n";

echo "<li>";
echo "<a href='$projects_url/$projectid/images.html'>";
echo _("View Images Online");
echo "</a>\n";

function echo_download_zip( $projectid, $link_text, $discriminator )
{
    global $projects_url;

    echo "<li>";
    echo "<a href='$projects_url/$projectid/$projectid$discriminator.zip'>";
    echo $link_text;
    echo "</a>\n";
}

echo_download_zip( $projectid, _("Download Zipped Images"), 'images' );

if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
{
    echo_download_zip( $projectid, _("Download Zipped Text"), '' );

    echo_download_zip( $projectid, _("Download Zipped TEI Text"), '_TEI' );
}
elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
{
    echo_download_zip( $projectid, _("Download Zipped Text"), '_second' );
}
elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
{
    echo_download_zip( $projectid, _("Download Zipped Text"), '_corrections' );
}

// ----------------------------------

echo "<li>";
echo _("Change Project State") . ":";

echo "<form name='$projectid' method='get' action='$code_url/tools/changestate.php'>";
echo "<input type='hidden' name='project' value='$projectid'>\n";
echo "<input type='hidden' name='curr_state' value='$state'>\n";

if ($state==PROJ_POST_FIRST_AVAILABLE)
{
    $serious_code=PROJ_POST_FIRST_CHECKED_OUT;
    $serious_label= _("Check Out Book");
    $serious_question= _("Are you sure you want to check this book out for post processing?");
}
elseif ($state==PROJ_POST_FIRST_CHECKED_OUT)
{
    $serious_code=PROJ_POST_FIRST_AVAILABLE;
    $serious_label=_("Return to Available");
    $serious_question=_("Are you sure you want to make this book available to others for post processing?");
}
elseif ($state==PROJ_POST_SECOND_AVAILABLE)
{
    $serious_code=PROJ_POST_SECOND_CHECKED_OUT;
    $serious_label= _("Check Out Book");
    $serious_question=_("Are you sure you want to check this book out for verifying post processing?");
}
elseif ($state==PROJ_POST_SECOND_CHECKED_OUT)
{
    $serious_code=PROJ_POST_SECOND_AVAILABLE;
    $serious_label=_("Return to Available");
    $serious_question=_("Are you sure you want to make this book available to others to verify and lose your work?");
}
elseif ($state==PROJ_CORRECT_AVAILABLE)
{
    $serious_code=PROJ_CORRECT_CHECKED_OUT;
    $serious_label= _("Check Out Book");
    $serious_question=_("Are you sure you want to check this book out to review corrections?");
}
elseif ($state==PROJ_CORRECT_CHECKED_OUT)
{
    $serious_code=PROJ_CORRECT_AVAILABLE;
    $serious_label=_("Return to Available");
    $serious_question=_("Are you sure you want to make this book available to others for reviewing corrections?");
}
echo "<select name='request' onchange=\"";
echo "if (this.value=='$serious_code'){di=confirm('$serious_question');if(di){this.form.submit();}}else {this.form.submit();}";
echo "\">\n";

echo "<option selected>"._("Select")."...</option>\n";

echo_option( $serious_code, $serious_label );

if ($state == PROJ_POST_FIRST_CHECKED_OUT)
{
    $label = _("Upload for Verification");
    echo_option( PROJ_POST_SECOND_AVAILABLE, $label);
}
elseif ($state == PROJ_CORRECT_CHECKED_OUT)
{
    $label = _("Posted to Project Gutenberg");
    echo_option(PROJ_SUBMIT_PG_POSTED, $label);
}

echo "</select></form>\n";

echo "</ul>\n";

// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

function echo_option($code,$label)
{
    echo "<option value=\"$code\">$label</option>\n";
}

theme('', 'footer');

?>
