<?
$relPath='../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');

$no_stats=1;
theme('Welcome Back, Alumni','header');

$Workflow_Change_Announcement_URL = make_forum_url('t',0,15170);
?>

<h1 align="center">Welcome Back to Distributed Proofreaders, Alumni</h1>

<h3 align="center">Version 1.1, generated April 14, 2006</h3>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Table of Contents</b></font></td>
  </tr>
 </tbody>
</table>
     <ul style="margin-left: 3em;">
        <li><a href="#welcome">Welcome</a></li>

        <li><a href="#p1">Proofing Round One Duties</a></li>
        <li><a href="#p2">Proofing Round Two Responsibilities</a></li>
        <li><a href="#f1">The Formatting Rounds</a></li>
        <li><a href="#counts">Page Counts</a></li>
        <li><a href="#track">Your Projects</a></li>
        <li><a href="#rprojects">(R) Projects</a></li>
        <li><a href="#links">Useful Links</a></li>
      </ul>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2">&nbsp;</font></td>
  </tr>
 </tbody>
</table>

<h2><a name="welcome">Welcome</a></h2>

<p>Welcome to the new DP website! This Guideline describes some of the changes that
   have been made to the site and the Proofing & Formatting structure as of June 2005.
</p>

<p>After a user has logged in, they will be directed to the Activity Hub, which
   has replaced the personal page.
</p>

<p>As part of the changes to the site, the proofing structure has been revised. 
   Formerly in Round 1 and Round 2 proofing (correcting the text) and formatting 
   (amending the text to DP formatting) were done simultaneously in each round.
   A project passed through two rounds before going onto to post-processing.
</p>

<p>In the new structure, R1 and R2 have been replaced by two proofing and two formatting 
   rounds: P1, P2, F1 and F2. Thus a book will now progress through four rounds before 
   going to post-processing.
</p>

<p>Most proofers on the new site have started in the P1 round. After volunteers 
   have completed the requirements listed on the Activity Hub and/or in the
   <a href="<?=$Workflow_Change_Announcement_URL?>">Workflow Change
   Announcement Thread</a>, they can move into P1, P2, and F2 rounds should they 
   wish to do so. Each round's page can be visited from the Activity Hub, and the 
   requirements for proofing in each round (for example, the number of pages 
   completed well in other rounds, time spent on site and passes on the quizzes) 
   are shown on that round's page.
</p>

<p>The duties for each round are as follows:</p>

<h2><a name="p1">P1 Proofing Duties</a></h2>

<p>The aim of the P rounds is character proofing: to match letter for letter, 
   punctuation mark for punctuation mark, the original text as if the original 
   text had no italics, superscript numbers (footnotes), etc.
</p>

<p>Thus, proofers are responsible, among other things, for the following:</p>

<ul style="margin-left: 2em;">
    <li>	checking each letter of the scan, reading the text closely and correcting 
            scannos and stealth scannos;</li>
    <li>	inserting Latin-1, non-ASCII-7 characters;</li>
    <li>	adding markup for hyphens;</li>
    <li>	removing additional spaces around punctuation and contractions;</li>
    <li>	removing any other junk that has crept in (errant punctuation, etc); and</li>
    <li>	marking footnote and endnote markers by putting square brackets [ ]
            around the numbers in the main text and using * for all symbolic footnotes.</li>
</ul>

<p>All these things require careful reading of the text and thus are the responsibility of proofers.</p>

<p>Proofers are no longer responsible for such things as: </p>
<ul style="margin-left: 2em;">
    <li>	formatting the text: for example, adding markup for text style (italics, small caps etc.);</li>
    <li>	adding markup for sidenotes and footnotes;</li>
    <li>	adding markup for poetry or block-quotes; or</li>
    <li>	unwrapping lines in poetry or indexes, adding lines for chapter headings, etc.</li>
</ul>

<p>If some formatting has already been included, for example, indented lines in 
   poetry, the formatting is <b>not</b> removed unless it is it surrounds OCR 
   garbage such as <tt>&lt;i&gt;</tt> *#4@P,,./<tt>&lt;/i&gt;</tt> . Even if 
   the formatting is wrong as in, <tt>&lt;i&gt;</tt>i.e<tt>&lt;/i&gt;</tt>., 
   please leave it to the formatters to repair and concentrate on the text.
</p>

<p>Please note that the above is by no means a comprehensive list. The P rounds 
   have a revised set of <a href="<?=$code_url?>/faq/proofreading_guidelines.php">Proofing
   Guidelines</a> to reflect the new responsibilities for this round and everyone is
   enthusiastically encouraged to check these Guidelines. There is also a new
   <a href="<?=$code_url?>/quiz/start.php?show_only=PQ">Proofing Quiz</a>.
</p>

<p>P1 proofers should run a spell check on their pages.</p>

<h2><a name="p2">P2 Proofing Duties</a></h2>

<p>This is the close reading of pages already read in P1 for any small errors 
   that might have been missed in the P1 round. Otherwise, the responsibilities 
   for this round are as per the P1 round. P2 proofers are required to run a spell 
   check on their pages.
</p>

<h2><a name="f1">F1 & F2 Formatting Duties</a></h2>

<p>The aim of the F rounds is to insert all markup and formatting: for example, 
   footnotes and sidenotes markup, text style markup (italics, small caps, etc.), 
   returns before and after chapter headings, and so on. Formatters arrange the 
   text on the page as required: indenting poetry, inserting the correct blank 
   lines for chapter headings, and new paragraph pages, etc. Formatters are not 
   responsible for finding and fixing scannos or doing a letter-by-letter comparison 
   of the text, although they are encouraged to fix anything that catches their eye. 
   The F rounds are currently continuing to use the previous 
   <a href="<?=$code_url?>/faq/document.php">Guidelines</a> with a few
   modifications such as the addition of small caps notation. There is a new 
   <a href="<?=$code_url?>/quiz/start.php?show_only=FQ">Formatting Quiz</a>.
</p>

<h2><a name="counts">Page Counts</a></h2>

<p>When the site was re-launched all users' page counts were saved. Everybody has 
   started with a zero page count on the new site. User page counts are now kept separately
   in each round.
</p>

<h2><a name="track">Keeping Track of Your Projects and Difference Watching</a></h2>

<p>At the top of the Activity Hub page, under the DP header and to the left of 
   &#8220;My Preferences&#8221; there is a tab called &#8220;My Projects&#8221;.
   Clicking on &#8220;My Projects&#8221; will bring up a list of the books you 
   have worked on. Clicking on a book title will bring up the Project Page window. If 
   you are looking for your diffs pages, there is a new feature in the Page Details 
   row of the Project Page called &gt;&gt;Just my pages&lt;&lt;. Clicking on 
   &gt;&gt;Just my pages&lt;&lt; will display the pages that you have proofed in a 
   book. Checking diffs has never been easier! An important additional feature on 
   the Diffs Page is that there is an &#8220;edit&#8221; link beside each page you 
   have proofed. Clicking on &#8220;edit&#8221; will bring up the page should you 
   want to make any amendments to previously proofed pages.
</p>

<h2><a name="rprojects">(R) Projects</a></h2>


<p>At the time of writing (June 2005) there are a number of projects in P1 with 
   (R) after the title. These are projects that have been returned to the proofing 
   rounds from the PP pool or from PPer's personal queues. As many of these projects 
   mention on the Project Page, these projects have been proofed under older Guidelines. 
   As per above, proofers should <b>not</b> remove or adjust any of the formatting. 
   Formatting checks will be completed by the formatters. Proofers should proof 
   according to <a href="<?=$code_url?>/faq/proofreading_guidelines.php">current
   Guidelines</a>, for example, insert page numbers into tables of contents
   and indexes.
</p>

<h2><a name="links">Useful Links</a></h2>

<ul style="margin-left: 3em;">
    <li><a href="<?=$Workflow_Change_Announcement_URL?>">The Workflow Change Announcement Thread</a></li>
    <li><a href="<?=$code_url?>/faq/proofreading_guidelines.php">The Proofreading Guidelines</a></li>
    <li><a href="<?=$code_url?>/faq/proofing_summary.pdf">The Proofreading Summary (PDF)</a></li>
    <li><a href="<?=$code_url?>/quiz/start.php?show_only=PQ">The Proofreading Quiz</a></li>
    <li><a href="<?=$code_url?>/faq/document.php">The Formatting Guidelines</a></li>
    <li><a href="<?=$code_url?>/faq/formatting_summary.pdf">The Formatting Summary (PDF)</a></li>
    <li><a href="<?=$code_url?>/quiz/start.php?show_only=FQ">The Formatting Quiz</a></li>
    <li><a href="<?=$code_url?>/stats/stats_central.php">Statistics Central</a></li>
 </ul>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Return to:
     <a href="..">Distributed Proofreaders home page</a>,
     &nbsp;&nbsp;&nbsp;

     <a href="faq_central.php">DP FAQ Central page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="http://www.gutenberg.org/">Project Gutenberg home page</a>.
     </font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>


