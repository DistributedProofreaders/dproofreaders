<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');
include($relPath.'theme.inc');

theme(_("Project Information"), 'header');

if (!user_is_PP()) {
	echo _("You're not recorded as a post processor. If you feel this is an error, please contact the site administration.");
	exit();
}

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

if (($smooth_dead > time()) AND ($state==PROJ_POST_FIRST_CHECKED_OUT) ) {
    echo_project_info( $projectid, 'proj_post', 0 , 1);
} else {
    echo_project_info( $projectid, 'proj_post', 0 );
}
echo "<BR>";



// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

echo "<ul>\n";

echo "<li>";
echo "<a href='$code_url/tools/proofers/images_index.php?project=$projectid'>";
echo _("View Images Online");
echo "</a>";
echo "</li>";
echo "\n";

function echo_download_zip( $projectid, $link_text, $discriminator )
{
    global $projects_url, $projects_dir, $code_url;

    if ( $discriminator == 'images' )
    {
        // Generate images zip on the fly,
        // so it's not taking up space on the disk.

        $url = "$code_url/tools/download_images.php?projectid=$projectid&amp;dummy={$projectid}images.zip";
        // The 'dummy' parameter is for the benefit of download-software that
        // names the resulting file after the last component of the request URL.

        // Images don't compress much in a zip,
        // so the sum of their individual filesizes
        // is a fair approximation (and hopefully an upper bound)
        // of the size of the resulting zip.
        $filesize_b = 0;
        foreach( glob("$projects_dir/$projectid/*.{png,jpg}", GLOB_BRACE) as $image_path )
        {
            $filesize_b += filesize($image_path);
        }
        $filesize_kb = round( $filesize_b / 1024 );
    }
    else
    {
        $p = "$projectid/$projectid$discriminator.zip";

        $url = "$projects_url/$p";
        $filesize_kb = round( filesize( "$projects_dir/$p") / 1024 );
    }

    echo "<li>";
    echo "<a href='$url'>";
    echo $link_text;
    echo "</a>";
    echo " ($filesize_kb kb)";
    echo "</li>";
    echo "\n";
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


// if checked out for PPing, the project can be uploaded and made available for smoothreading
// by anyone without changing state - it's still checked out to PPer

if ($state==PROJ_POST_FIRST_CHECKED_OUT) {

    echo "<li>";
    $now = time();
    if ($smooth_dead > $now) {
        $deadline = strftime(_("%A, %B %e, %Y"), $smooth_dead);
        echo _("This project has been made available for smoothreading until ")."<b>$deadline</b>";

    } else {
        echo _("Upload project for smoothreading") . ":";

        $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks=";

        echo "<ul>";
        echo "<li>".$link_start."1'>"._("Make available for smoothreading for one week")."</a>";
        echo "<li>".$link_start."2'>"._("Make available for smoothreading for two weeks")."</a>";
        echo "<li>".$link_start."4'>"._("Make available for smoothreading for four weeks")."</a>";
        echo "</ul>";
    }
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

// vim: sw=4 ts=4 expandtab
?>
