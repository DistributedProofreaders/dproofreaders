<?php
include_once($relPath.'v_site.inc');
?>

<p align="center"><font size="+2">Privacy Statement:</font></p>

<p><font size="+1">Usage of information:</font></p>

<p>This information will allow the project managers to provide feedback to you directly
or have users send to you a message via the phpBB forum. An introductory e-mail will
also be sent to you.</p>

<p><font size="+1">Removal of information:</font>

<p>Requests to remove the information beforehand can be sent to the web site manager or
will be removed based on its age.</p>

<p><font size="+1">Access of information:</font></p>

<p>Only the web site manager
<?
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

<p><font size="+1">Tracking information:</font></p>

<p>The tracking information we collect at this time is the date your account was created, 
your last login date and some statistical data: number of pages you have
completed, your best day ever, team memberships, and the highest rank you've achieved. 
This tracking information will be available to end users; however, you can have your
name displayed as 'anonymous' for some of these data by setting &quot;Anonymous 
Statistics&quot; to &quot;Yes&quot; in your Preferences.</p>

<p>The site uses phpBB forums for discussions among the members. The data you fill out at 
this forum will also be used on the rest of the site.</p>
