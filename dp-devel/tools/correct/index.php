<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablecorr.inc');
theme("Correct Editions", "header");

echo "
<br>
<p>
    This is the correct editions section.
    The books listed below have already been posted to
    Project Gutenberg, but a reader has found errors.
    Once you have checked out and downloaded a book
    it will remain checked out to you until you check it back in. 
    When you have finished your work on the book, send the
    book to Project Gutenberg to be posted.
</p>
<p>
    If no books are listed that means no work is currently available --
    please check back later!
</p>
<p>
    <hr width=75% align='center'>
</p>
";

echo "<center><b>Books I Have Checked Out to Verify Corrections:</b></center>";
show_available_correct(PROJ_CORRECT_VERIFYING);
echo "<br>";

echo "<center><b>Books Available with Corrections:</b></center>";
show_available_correct(PROJ_CORRECT_VERIFY);
echo "<br>";

theme("", "footer");
?>
