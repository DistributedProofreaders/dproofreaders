<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'show_projects_in_state.inc');
include_once($relPath.'user_is.inc');

theme("Post Processing", "header");

$isPPV = user_is_proj_facilitator();

if (!$isPPV) {
echo "
<br>
<p>
    The books listed below have already gone through
    two rounds of proofreading on this site
    and they now need to be massaged into a final e-text.
    Once you have checked out and downloaded a book
    it will remain checked out to you until you check it back in. 
    When you have finished your work on the book, select <i>Upload 
    for Verification</i> from the drop-down list for that project. If
    you have several files to submit for a single project (say 
    a text and HTML version), zip them up together first.
</p>
<p>
    <b>First Time here?</b>
    Juliet Sutherland (<a href='http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1396'>JulietS</a>) 
    is our Post Processing Coordinator. Julie Barkley
    (<a href='http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674'>juliebarkley</a>)
    maintains our <a href='$code_url/faq/post_proof.php'>Post Processing FAQ</a>.
    Please read the FAQ as it covers all the steps needed to post process an e-text.
    Select an easy work to get started on (usually fiction with a low page count is a 
    good starter book; projects whose manager is BEGIN make excellent first projects
    for a new post processor) and check out the <a href='$post_processing_forum_url'>
    Post Processing Forum</a> to post all your questions. If nothing interests you
    rigt now, check back later and there will be more!
</p>";
} else {
echo "
<br>
<p>
    As an experienced volunteer, you have access to do verification of texts
    that have been Post Processed already. The <a href='$code_url/faq/post_proof.php'>
    Post Processing FAQ</a> is available for more details on the role and the
    <a href='$post_processing_forum_url'>Post Processing Forum</a> is available
    for any of your questions.
</p>
";
}

echo "<hr width=75% align='center'><center><b>Books I Have Checked Out for Post Processing:</b></center>";
show_projects_in_state(PROJ_POST_FIRST_CHECKED_OUT);
echo "<br>";

if ($isPPV) {

echo "<center><b>Books I Have Checked Out for Verifying Post Processing:</b></center>";
show_projects_in_state(PROJ_POST_SECOND_CHECKED_OUT);
echo "<br>";
}

echo "<center><b>Books Available for Post Processing:</b></center>";
show_projects_in_state(PROJ_POST_FIRST_AVAILABLE);
echo "<br>";

if ($isPPV) {

echo "<center><b>Books Available for Post Processing Verification & Posting:</b></center>";
show_projects_in_state(PROJ_POST_SECOND_AVAILABLE);
echo "<br>";
}

theme("", "footer");
?>
