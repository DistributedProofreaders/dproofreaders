<?php
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');

$test_host='http://www.pgdp.org';	
$test_site_code_url="$test_host/c";	
$dev_list='dproofreaders-devel@lists.sourceforge.net';
$commits_list='dproofreaders-commits@lists.sourceforge.net';
$sa_list='dp-sa @ pgdp.net';

theme('Development Process Guidelines', 'header');

echo "
<h4>Quick links</h4>

<table>
<tr>
    <td>Task Center</td>
    <td><a href='$code_url/tasks.php'>$code_url/tasks.php</a></td>
</tr>
<tr>
    <td>Site Admins' mail alias</td>
    <td><a href='mailto:$sa_list'>$sa_list</a></td>
</tr>
<tr>
    <td>developers' mailing list</td>
    <td><a href='mailto:$dev_list'>$dev_list</a></td>
</tr>
<tr>
    <td>cvs commits mailing list</td>
    <td><a href='mailto:$commits_list'>$commits_list</a></td>
</tr>
<tr>
    <td>developers' Jabber chat room</td>
    <td>pgdpdev@muc.jabber.org</td>
</tr>
<tr>
    <td>general Jabber chat room</td>
    <td>pgdp@muc.jabber.org</td>
</tr>
<tr>
    <td>your sandbox</td>
    <td>$test_host/~your_id/c</td>
</tr>
<tr>
    <td>test site</td>
    <td><a href='$test_site_code_url'>$test_site_code_url</a></td>
</tr>
<tr>
    <td>production site</td>
    <td><a href='$code_url'>$code_url</a></td>
</tr>
</table>
";
?>

<h2>Development Process Guidelines</h2>

<p>
The purpose of these guidelines is to increase the efficiency
 and satisfaction of the DP developers,
 by reducing wasted effort and site code breakage.
</p>

<ol>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
<b>Use the Task Center</b>
 to document what you are working on and the progress you are making.
If you wish to start working on a problem/feature,
 check the Task Center to see if there is an existing Task
 which is related to or covers what you wish to work on.
 If there is, and it hasn't been assigned to anyone,
 assign it to yourself.
If there isn't an existing Task, please create one.
</p>

<p>
Be aware that, if a task is a Feature Request,
 it isn't necessarily a feature that lots of people want.
In fact, many people might actively <i>not</i> want it.
If you think a feature might be controversial or unpopular,
 feel free to discuss it on the developers' list.
</p>

</li>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
Note that sometimes,
 different tasks will involve similar or overlapping changes,
 so assigning yourself a task doesn't guarantee 
 that you can work in blissful isolation.
So <b>use the developers' list</b> as a back-up
 (or occasionally an alternative)
 to the coordination provided by the Task Center,
 and also as a forum for extended or wide-ranging conversations
 that don't fit well in task comments.
In particular, be sure to mail to the list
 if the scope of your changes is not apparent
 from a task in the Task Center.
</p>

<p>
If you need to get some quick feedback, try
 <b>the developers' chat room</b>.
</p>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
<b>Develop your code using a CVS "working copy" on your home system
 and test changes in a "sandbox" on the test host.</b>
</p>

<p>
Before you start working on a change,
 update your CVS working copy
 and the snapshot in your sandbox
 from the CVS master on sourceforge.
</p>
 
<p>
After that, do your editing in your working copy
 and use <code>scp</code> to copy <i>changed</i> files
 from your working copy to your sandbox.
(If you need help with <code>scp</code> please contact pourlean in Jabber.)
Note: If you copy the CVS versions of pinc/v_site.inc or pinc/udb_user.php
 into your sandbox, it will stop working.
If you need to change either of these files, seek help from a Site Admin.
</p>

<p>
If you do not have a sandbox set up on the test host
 and would like one, please form an orderly queue in pourlean's mailbox.
She can create an account for you and walk you through the setup.
</p>

<p>
Note that, so far, these are only <i>code</i> sandboxes:
 personal copies of the site code.
They do not give you complete isolation,
 in that they (and the test site) share a single database
 and a single projects hierarchy.
So be careful.
</p>

<p>
It's a good idea to <b>get other people to test your code</b>
 while it's still just in your sandbox
 (not yet committed to CVS).
Announce your changes to the developers' mailing list,
 including a list of modified files,
 a description of the changed functionality,
 and a link into your sandbox.
(If your changes address a task in the Task Center,
 it would be handy to include a link to that too.)
Jabberwockies in the general DP chat room are often good test subjects.
Make sure they understand what specifically needs testing.
</p>
</li>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
Once initial testing is done, <b>commit your changes to CVS</b>.
When supplying a comment for CVS,
 please add any relevant Task Center task IDs
 (e.g., "Fixed off-by-one error in 'your neighborhood'. (task #333)",
 or "Implements project-filtering feature requested in task #456.").
If you commit multiple files at once,
 please ensure that the comment makes sense for each file.
Lastly, note that a record of your commit will be mailed to
 the 'commits' mailing list,
 so make it a good one!
</p>

<p>
Then go to your sandbox and refresh it from CVS,
 which should pull in your changes.
Do a quick check to ensure that the commit process
 hasn't introduced any problems
 (e.g., you committed the wrong file,
 or you didn't commit all the right files,
 or there was a revision conflict that you didn't resolve well).
</p>
</li>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
If everything still works in your sandbox, contact the Site Admins
 to have your code <b>installed to the test site</b>.
A Site Admin will make your changes visible at <? echo $test_site_code_url; ?>
 as time permits.
</p>

<p>
You can then call for more thorough testing of the change
 in the DP Forums if needed.
</p>

<p>
If problems arise during testing in the test site,
 hopefully you can fix them quickly.
If not, we can back out your changes on the test site, and
 you can continue to work on the fix in your sandbox/home system.
</p>

<p>
Once your change is working on the test site,
 please mark any associated tasks in the Task Center as implemented or fixed.
However, leave them open and at 90% progress
 until the change is visible on the live site.
</p>
</li>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

<li>
<p>
If your change warrants immediate <b>installation on the production site</b>,
 contact an Site Admin,
 and they can make your change visible to the DP community.
The associated tasks can then be closed in the Task Center.
</p>
</li>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

</ol>

<p>
This process is open to improvement and comments.
If it doesn't work for you,
 or you can see places where it can be improved,
 please contact the developers' list or the Site Admins.
</p>

<br>

<?
theme('', 'footer');
?>
