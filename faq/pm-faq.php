<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Project Manager\'s Guide', NO_STATSBAR);
?>

<h1>Project Manager's Guide</h1>

<p>
The
<a href="cp.php">Content Provider's FAQ</a>
describe the life of a Distributed Proofreaders project up to the point
at which the image files and text files have been uploaded to the DP
server. This document describes everything that happens after that, from
the point of view of a Project Manager (PM).

</p>

<p>
PMs are welcome to do the prep work on their project, or they can choose
to manage a project that someone else has prepared.
<!--
<font style='color:red'>
[How do they see what's available to be claimed?]
</font>
-->
</p>

<p>
To become a PM, send an email to <?php echo $PM_rights_giver_contact; ?>.
</p>

<hr>

<p>Contents:</p>

<ul>

<li>Project Manager's Workflow
<ol>
<li><a href='#create'>Create the project.</a>
<li><a href='#release'>Release it.</a>
<li><a href='#shepherd'>Shepherd it through proofreading.</a>
</ol>
</li>

<li>Project Manager Pages
<ul>
<li><a href='#project_search_page'>Project Search Page</a></li>
<li><a href='#project_listings_page'>Project Listings Page</a></li>
<li><a href='#project_detail_page'>Project Detail Page</a></li>
<li><a href='#pm_preferences'>Project Manager Preferences</a></li>
</ul>
</li>

</ul>

<hr>

<h3>Project Manager's Workflow</h3>

<p>
See also the <a href="DPflow.php">DP Workflow Diagram</a>,
which gives an overview of how material moves through the site
and what the PMs do.
</p>

<ol>
<li><a name='create'><b>Create the project on the DP website.</b></a>
    <blockquote>
        <p>
        On the main PM page (at the top) is a link to the "Create Project" page.
        After you create your project, it will be listed on your PM page. Each
        project has an "Edit Comments" link. HTML is allowed in the comments
        including links to example pictures. You should be able to use any
        characters in the comments.
        </p>
    </blockquote>
</li>

<li><a name='release'><b>Release the project for proofreading.</b></a>
    <blockquote>
    <p>
        This is done by toggling the "Availability" of the project on the PM's
        page. The project will be added to the site's queueing system, which
        tries to ensure an even mix of books in proofreading.
    </p>
    <p>
        In particular, it won't allow more than one book by the same author in
        First Round at the same time. So if you have 4 volumes of a series, all
        by the same author, you can put them all into 'Waiting to be Released'
        at once, but the software will only allow one of them in First Round at
        a time.
    </p>
    </blockquote>
</li>

<li><a name='shepherd'><b>Shepherd the project through the site.</b></a>

    <blockquote>
    <p>
        There are 4 main tasks you will need to perform to assure your project
        runs smoothly through the site. All these tasks are done via the Project
        Page using the links provided.
    </p>

    <ol>

    <li>
    <b>Answer proofreader's questions.</b>
    <p>
        Depending on the project, you may get a lot of questions in the Project
        Forum thread for the project. If you get a lot of the same questions you
        may consider modifying your project comments.
    </p>
    </li>

    <li>
    <b>Delete duplicate pages.</b>
    <p>
        Sometimes a page will be scanned more than once in a project. If you
        have duplicate pages, you can delete the duplicates by using the
        'Delete' button on the Project Detail page.
    </p>
    </li>

    <li>
    <b>Clear effects of round.</b>
    <p>
        If a page has not been proofread well, you have the option of clearing the
        page. This will clear the effects of the round and make the page
        available again. It will also decrement the page count for the proofreader.
        You should send the proofreader feedback if you clear pages from your
        projects.
    </p>
    </li>

    <li>
    <b>Bad page handling.</b>
    <p>
        If a proofreader marks a page bad in the first round, the project will stall until you
        resolve the report. Click on the 'X' on the project details page for the
        project and follow the instructions on the screen.
    </p>
    </li>
    </ol>

</li>

</ol>

<hr>

<h3><a name='project_search_page'>Project Search Page</a></h3>

<p>
This page is the default PM page. That is, it is the page that a PM will
normally be directed to after signing in and after various other
operations. You can also get to it from the other PM pages by clicking
on the "Search Your Projects" link near the top of the page.
</p>

<p>
Matching for fields other than <i>State</i> is case-insensitive and
unanchored. So, for instance, 'jim' matches both 'Jimmy Olsen' and
'piggyjimjams'.
</p>

<p>
If desired, you should be able to select multiple values for
<i>State</i> (e.g., by holding down Ctrl).
</p>

<h3><a name='project_listings_page'>Project Listings Page</a></h3>

<p>
Click on the title of a project to view its details.
</p>
<p>
The "Pages Left" column of the projects table was removed to speed up
assembly of the PM page, and reduce the load on the server. Note that
the "Pages Remaining to be Proofread" for a project is still available,
on its Project Details page.
</p>

<h3><a name='project_detail_page'>Project Detail Page</a></h3>

<h4>Add Text+Images from <?php echo $uploads_account; ?> Account</h4>

<p>
You can now specify a directory (in the <?php echo $uploads_account; ?>
account) from which to add text+images into your project. This means
that you are now free to choose the name of the upload directory you
create, instead of having to use the project's ID. (E.g., you might
choose to give it the same name as the corresponding directory on your
local machine.) Of course, the project's ID will still work fine as the
name of the directory, and is in fact the default for the Add
Text+Images button.
</p>
<p>
Moreover, the string you type is actually interpreted as a 'path'
(relative to the root of the <?php echo $uploads_account; ?> account), so
it can be a directory within a directory. For instance, you may find it
convenient to create a personal directory in the <?php echo $uploads_account; ?>
account, and then create your project-specific directories within it.
(If you do this, it's recommended that you use your DP login name for
the name of the personal directory, as that may be an assumed default in
the future.)
</p>

<p>
When you click on the "Add" button, it copies all .png files into the
project's directory, adds the page-text files to the project's
page-table, and also copies any illustration files to the project's
directory.
</p>

<h4>Delete All Text</h4>

<p>
The "Delete All Text" link (if present) is now at the very bottom of
this page, to lessen the chance of hitting it by accident. This is a
temporary measure until a proper "Are You Sure?" prompt is implemented.
</p>

<h3><a name='pm_preferences'>Project Manager Preferences</a></h3>

<p>
Under the "My Preferences" page linked at the top of most pages on the
site, a tab will appear for Project Managing. From this tab, a PM can
change several user preferences specific to managing projects. Clicking
on the question mark next to each preference setting will pop up a new
window which describes each setting in further detail.
</p>

<?php
// vim: sw=4 ts=4 expandtab
