<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'show_projects_in_state.inc');
include_once($relPath.'user_is.inc');
theme("Post Processing", "header");

echo "
<br>
<p>
    This is the post processing section.
    The books listed below have already gone through
    two rounds of proofreading on this site
    and they now need to be massaged into a final e-text.
    Once you have checked out and downloaded a book
    it will remain checked out to you until you check it back in. 
    When you have finished your work on the book, choose Upload 
    for Verification from the drop-down list for that project. If
    you have several files to submit for a single project (say 
    a text and HTML version), zip them up together first.
</p>
<p>
    Here is a <a href='$code_url/faq/post_proof.php'><b>Post Proof FAQ</b></a>
    that details all of the steps that we normally take to post proof an etext.
    There is a <a href='$post_processing_forum_url'>forum</a>
    to post all your questions.
    If no books are listed that means no work is currently available --
    please check back later!
</p>
<p>
    <b>First Time here?</b>
    Juliet Sutherland (JulietS) is our Post Processing Coordinator. Julie Barkley
    (juliebarkley) maintains our PP FAQ. If this is your first time here...
    Please read the FAQ, read it again ;), select an easy work to get started on
    (usually fiction with a low page count is a good starter book; projects
    whose manager was BEGIN make excellent first projects for a new PPer)
    and write <a href='mailto:juliet.sutherland@verizon.net'>Juliet</a>
    with any questions/comments.
</p>
<p>
    If you are new to this page, make sure you only select projects
    from the Books Available for Post-Processing section (and NOT the
    Books Available for Post-Processing Verification & Posting section, 
    which are reserved for an invited list of experienced volunteers only).
</p>
<p>
    PPV is like a second round for PPed books; it allows another set of 
    eyes to scan the books and make sure no errors are missed.  PPVers 
    also give feedback to PPers, so that new PPers can learn the ropes 
    a little faster.  Because PPV involves both feedback to new PPers 
    and looking for easily-missed errors, only experienced PPers who 
    receive permission can check out PPV books. Please do not check out 
    these books if you have not yet received PPV clearance.
</p>
<p>
    <hr width=75% align='center'>
</p>
";

$isPPV = user_is_proj_facilitator();

echo "<center><b>Books I Have Checked Out for Post-Processing:</b></center>";
show_projects_in_state(PROJ_POST_FIRST_CHECKED_OUT);
echo "<br>";

if ($isPPV) {

echo "<center><b>Books I Have Checked Out for Verifying Post-Processing:</b></center>";
show_projects_in_state(PROJ_POST_SECOND_CHECKED_OUT);
echo "<br>";
}

echo "<center><b>Books Available for Post-Processing:</b></center>";
show_projects_in_state(PROJ_POST_FIRST_AVAILABLE);
echo "<br>";

if ($isPPV) {

echo "<center><b>Books Available for Post-Processing Verification & Posting:</b></center>";
show_projects_in_state(PROJ_POST_SECOND_AVAILABLE);
echo "<br>";
}

theme("", "footer");
?>
