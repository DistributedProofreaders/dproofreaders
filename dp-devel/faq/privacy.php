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

<p>
When you register, <?php echo $site_name; ?> will send
a confirmation/introduction message to your e-mail address.
You may also receive optional e-mail alerts from our discussion forums
and occasional notifications and feedback related to your proofreading activities.
<?php echo $site_name; ?> will also invite you by e-mail
to vote in periodic Board Trustee elections
and could contact you for site-related purposes
authorized by <?php echo $site_name; ?> management.
</p>

<h3>Access to your information</h3>

<p>Only the <?php echo $site_name; ?> site administrators
<?php
if ($testing)
{
    echo "<font style='color: red'>";
    echo "and (because this is a testing site) developers with login access to this server";
    echo "</font>";
    echo "\n";
}
?>
will have access to the Real Name you provide.
If, in your forum profile,
you do not opt to make your e-mail address accessible to all volunteers,
it will be accessible only to our site administrators.
</p>

<p>
During the various production phases,
your User Name and your <?php echo $site_name; ?> work
may be viewable by other volunteers.
In addition, volunteers delegated to an evaluation or mentoring role
will be able to review the work performed under your User Name.
</p>

<p>
Your User Name and any public information you provide in your forum profile
will be accessible
to other <?php echo $site_name; ?> volunteers and to unregistered viewers.
Your posts on our discussion forums will be viewable by other volunteers.
Certain clearly-designated forums are also viewable by unregistered guests to the forums.
Your user name and any information you post on the wiki
are visible to both volunteers and unregistered viewers.
</p>

<p>
Volunteers may opt not to display their Real or User Name in the credits of a publication
while it is being worked on at <?php echo $site_name; ?> and
when it is distributed through Project Gutenberg or other publication channels.
</p>

<h3>Tracking information</h3>

<p>
<?php echo $site_name; ?> tracks
your User Name,
the date your account was created
and your last login date.
This tracking information will be available to other volunteers.
<?php echo $site_name; ?> automatically receives and records information
from your computer and browser, including your IP address.
This is accessible to our administrative staff only.
</p>

<p>
The system also tracks
the number of pages you have completed,
your best day ever,
team memberships,
proofreading roles,
and the highest rank you've achieved.
In your <?php echo $site_name; ?> site profile,
you can control whether this information is viewable by
everyone, registered users only, or yourself only.
</p>

<h3>Sharing Information</h3>

<p>
<?php echo $site_name; ?> never makes commercial use of information
entered on or for its website.
It does not share volunteer information other than as indicated above.
</p>

<?php
// If file wasn't include()d, we need to close the body and html tags
// that were opened by slim_header();
if(!$file_is_included)
{
    slim_footer();
}
// vim: sw=4 ts=4 expandtab
