<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');
include($relPath.'theme.inc');

theme('Project Information', 'header');

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

echo "<li>";
echo "<a href='$projects_url/$projectid/{$projectid}images.zip'>";
echo _("Download Zipped Images");
echo "</a>\n";

if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
{
    echo "<li>";
    echo "<a href='$projects_url/$projectid/$projectid.zip'>";
    echo _("Download Zipped Text");
    echo "</a>\n";

    echo "<li>";
    echo "<a href='$projects_url/$projectid/{$projectid}_TEI.zip'>";
    echo _("Download Zipped TEI Text");
    echo "</a>\n";

    {
        // For a while (2003 Feb-Aug?), sendtopost generated TEI files,
        // but didn't zip them. We could go back and zip them all, or
        // we can do it here, upon request.

        $TEI_base = "$projects_dir/$projectid/{$projectid}_TEI";
        $TEI_txt  = "$TEI_base.txt";
        $TEI_zip  = "$TEI_base.zip";

        if (!file_exists($TEI_zip) && file_exists($TEI_txt) )
        {
            // Create the zip
            // echo "creating the zip...";
            exec("zip -j $TEI_zip $TEI_txt");
        }
    }
}
elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
{
    echo "<li>";
    echo "<a href='$projects_url/$projectid/{$projectid}_second.zip'>";
    echo _("Download Zipped Text");
    echo "</a>";
}
elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
{
    echo "<li>";
    echo "<a href='$projects_url/$projectid/{$projectid}_corrections.zip'>";
    echo _("Download Zipped Text");
    echo "</a>";
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
