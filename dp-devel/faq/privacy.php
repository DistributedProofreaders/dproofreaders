<?php

// This file can be accessed directly in which case it needs appropriate
// HTML tags, and it can be include()d into other files, such as
// addproofer.php. We determine which is the case by checking if $relPath
// is set or not.
if(!isset($relPath))
{
    $file_is_included = FALSE;
    $relPath="./../pinc/";
    include_once($relPath.'base.inc');
    include_once($relPath.'slim_header.inc');
    include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

    undo_all_magic_quotes();

    slim_header(_("Privacy Statement"));
}
else
{
    $file_is_included = TRUE;
}
?>

<h2>Privacy Statement</h2>

<h3>Usage of information</h3>

<p>This information will allow the project managers to provide feedback to you directly
or have users send to you a message via the phpBB forum. An introductory e-mail will
also be sent to you.</p>

<h3>Removal of information</h3>

<p>Requests to remove the information beforehand can be sent to the web site manager or
will be removed based on its age.</p>

<h3>Access of information</h3>

<p>Only the web site manager
<?php
if ($testing)
{
    echo "<font style='color: red'>";
    echo "and (because this is a testing site) developers with login access to this host";
    echo "</font>";
    echo "\n";
}
?>
will have full access to the information in this form.
Project managers will have access to e-mail you directly if needed. Any public
information you fill out in the phpBB forum or profile will be accessible to other
users.</p>

<h3>Tracking information</h3>

<p>The tracking information we collect at this time is the date your account was created, 
your last login date and some statistical data: number of pages you have
completed, your best day ever, team memberships, and the highest rank you've achieved. 
This tracking information will be available to end users; however, you can have your
name displayed as 'anonymous' for some of these data by setting &quot;Anonymous 
Statistics&quot; to &quot;Yes&quot; in your Preferences.</p>

<p>The site uses phpBB forums for discussions among the members. The data you fill out at 
this forum will also be used on the rest of the site.</p>

<?php
// If file wasn't include()d, we need to close the body and html tags
// that were opened by slim_header();
if(!$file_is_included)
{
    slim_footer();
}
// vim: sw=4 ts=4 expandtab
