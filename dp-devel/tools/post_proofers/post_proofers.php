<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablepost.inc');
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
    <b>The Completed Post-Processing feature is currently disabled
    until it gets fully developed.
    E-mail the project manager with the completed project for now.</b>
</p>
<p>
    Here is a <a href='$siteurl/faq/post_proof.php'><b>Post Proof FAQ</b></a>
    that details all of the steps that we normally take to post proof an etext.
    There is a <a href='$post_processing_forum_url'>forum</a>
    to post all your questions.
    If no books are listed that means no work is currently available --
    please check back later!
</p>
<p>
    <b>First Time here?</b>
    Juliet Sutherland is our Post Processing Coordinator.
    Please read the FAQ, select an easy work to get started on
    (usually fiction with a low page count is a good starter book)
    and write <a href='mailto:juliet.sutherland@verizon.net'>Juliet</a>
    with any questions/comments.
</p>
<p>
    <hr width=75% align='center'>
</p>
";

//List the "My Checked Out Post-Processing Books"
echo "<center><b>My Checked Out Post-Processing Books:</b></center>";
show_available_post(PROJ_POST_CHECKED_OUT);
echo "<br>";

//List the "My Checked Out Verifying Post-Processing Books"
echo "<center><b>My Checked Out Verifying Post-Processing Books:</b></center>";
show_available_post(PROJ_POST_VERIFYING);
echo "<br>";

//List the "Available for Post Processing Books"
echo "<center><b>Available for Post-Processing Books:</b></center>";
show_available_post(PROJ_POST_AVAILABLE);
echo "<br>";

//List the "Available for Post-Processing Verification & Posting Books"
echo "<center><b>Available for Post-Processing Verification & Posting Books:</b></center>";
show_available_post(PROJ_POST_VERIFY);
echo "<br>";

theme("", "footer");
?>
