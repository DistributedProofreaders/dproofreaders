<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Project Manager\'s Guide','header');
?>

<h1>Project Manager's Guide</h1>

<p>
The
<a href="scan/submitting.php">Scanning, Preprocessing & Submitting Guidelines</a>
describe the life of a Distributed Proofreaders project
up to the point at which the image files and text files
have been uploaded to the DP server.
This document describes everything that happens after that,
from the point of view of a Project Manager (PM).
</p>

<p>
PMs are welcome to do the prep work on their project,
or they can choose to manage a project that someone else has prepared.
<font style='color:red'>
[How do they see what's available to be claimed?]
</font>
</p>

<p>
To become a PM, 
<a href="mailto:charlz@lvcablemodem.com">email Charlz</a>.
</p>

<hr>

<h3>Project Manager's Workflow</h3>

<ol>
<li><a href='#create'>Create the project.</a>
<li><a href='#release'>Release it.</a>
<li><a href='#shepherd'>Shepherd it through proofing.</a>
<li><a href='#postprocess'>Post-process it.</a>
<li><a href='#submit_to_pg'>Submit it to Project Gutenberg.</a>
</ol>

<p>
See also the <a href="DPflow.php">DP Workflow Diagram</a>,
which gives an overview of how material moves through the site
and what the PMs do.
</p>

<ol>
<li><a name='create'><b>Create the project on the DP website.</b></a>
    <blockquote>
    On the main PM page (at the top) is a link to the "Create Project" page.
    After you create your project, it will be listed on your PM page.
    Each project has an "Edit Comments" link.
    You can email Robert or Charlz for a file containing the "standard" comments or make up anything you like.
    HTML is allowed in the comments including links to example pictures.
    </blockquote>
</li>

<li><a name='release'><b>Release the project for proofing.</b></a>
    <blockquote>
    This is done by toggling the "Availability" of the project on the PM's page.
    Please coordinate your release with the other PM's.
    We all take turns and try to keep an even mix of books
    without having too many/few available.
    </blockquote>
</li>

<li><a name='shepherd'><b>Shepherd the project through the site.</b></a>
    <blockquote>
    There are 4 main tasks you will need to perform to assure your project runs smoothly through the site. All these tasks are done via the Project Page using the links provided.<br><br>

    1. <b>Delete duplicate pages.</b> Sometimes a page will be submitted more than once. We think this is caused by the proofer clicking "Save and Do Another" more than once. You can review the submitted text by clicking on the text file name. Delete all but one copy of the file by clicking on the Delete link for that file.<br><br>
    2. <b>Check in MIA's.</b> Sometimes a proofer will request a page but never turn it back in. To make the page availible again, first identify the missing pages. You can do this by scanning down the list and comparing the index number with the file number. When they get out of sync, you've got an MIA. To check them back in, click the link to get your entire file list. During the 1st round this is the "View Master Files" link. During the 2nd round, it is the "View all level 2 files" link. Find your MIA and click on "Check In". Don't get too anxious when checking in MIA's. If your MIA is near the bottom then a proofer may still be working on it.<br><br>
    3. <b>Promote files.</b> When a project finishes the 1st round and you have deleted all duplicates and all the MIA's have been checked in and proofed, click on the "Promote All" link. This will move your project to the second round.<br><br>
    4. <b>Answer proofer's questions.</b> Depending on the project, you may get a lot of question emails. Please be patient with the proofers as many of them will be newbies. If you get a lot of the same questions you may consider modifying your comments.<br><br>

    We are working on a script that will automate task #2 and have talked about automating task #3. Tasks 1 & 4 will probably always be done by the PM. The client has features designed to help prevent duplicates and MIA's. As more people start using the client you should see a reduction in their occurence.
    </blockquote>
</li>

<li><a name='postprocess'><b>Download the output from the site and massage it into a postable e-text.</b>
    <blockquote>
    After all the pages have gone through both rounds of proofing,
    the site will stitch together the resulting page-texts
    (with page separators)
    and add the resulting file to the project's directory.
    You can download this file by selecting <b>D/L</b> on the PM page,
    or <b>Download Zipped Text</b> on the post-processing page.

    <p>
    If you want to post-process the book,
    <font style='color:red'>
    [you would have said so earlier].
    </font>
    Follow the instructions in the
    <a href='post_proof.php'>Post-Proofing FAQ</a>.

    Or you can
    <font style='color:red'>
    [somehow]
    </font>
    make the project available
    for someone else to post-process.
    (They will find it on the post-processing page,
    and check it out by selecting <b>Check Out Book</b>.)
    </blockquote>

<li><a name='submit_to_pg'><b>Submit the finished e-text to Project Gutenberg</b></a>

    <blockquote>
    On your first few projects,
    you probably want to send it to Charlz for posting.
    (Please include your "raw" file that you downloaded from the site,
    as this helps tremendously.)
    He will review it, give you feedback, and submit it to PG.
    After you are confident,
    you can submit them on your own.
    </blockquote>
</li>
</ol>

<hr>
<h4>Document History</h4>

<ul>
<li>2001-12-17: first written by
    Charles Franks
    <a href="mailto:charlz@lvcablemodem.com">charlz@lvcablemodem.com</a>
    and
    Robert Rowe
    <a href="mailto:robert_rowe@yahoo.com">robert_rowe@yahoo.com</a>
<li>2002-06-02: Updated
<li>2003-07-25: jmdyck: eliminated overlap with the
<a href='scan/submitting.php'>Prep Guidelines</a> and the
<a href='post_proof.php'>Post-Proofing FAQ</a>.
Reorganized the remainder.
(It still needs updating, but that should be easier now.)
</ul>

<br>

<?
theme('','footer');
?>
