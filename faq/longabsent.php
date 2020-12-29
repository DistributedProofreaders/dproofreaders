<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Welcome Back, Alumni', NO_STATSBAR);

$Workflow_Change_Announcement_URL = make_forum_url('t',0,15170);
?>

<h1>Welcome Back to Distributed Proofreaders, Alumni</h1>

<p><small>Version 1.2.1, updated August 2, 2008</small></p>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Table of Contents</h2></td>
  </tr>
 </tbody>
</table>
     <ul style="margin-left: 3em;">
        <li><a href="#welcome">Welcome</a></li>
        <li><a href="#p1">Proofreading Round One Duties</a></li>
        <li><a href="#p2">Proofreading Round Two Duties</a></li>
        <li><a href="#p3">Proofreading Round Three Duties</a></li>
        <li><a href="#f1">The Formatting Rounds</a></li>
        <li><a href="#counts">Page Counts</a></li>
        <li><a href="#track">Your Projects</a></li>
        <li><a href="#rprojects">{R} Projects</a></li>
        <li><a href="#wordcheck">Wordcheck Word Lists and Interface</a></li>
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
   have been made to the site and the Proofreading &amp; Formatting structure as of June 2005.
   It also contains updates for changes as of June 2006 and March 2007.
</p>

<p>After a user has logged in, they will be directed to the Activity Hub, which
   has replaced the personal page.
</p>

<p>As part of the changes to the site, the proofreading structure has been revised. 
   Formerly in Round 1 and Round 2 proofreading (correcting the text) and formatting 
   (amending the text to DP formatting) were done simultaneously in each round.
   A project passed through two rounds before going onto to post-processing.
</p>

<p>In the new structure, R1 and R2 have been replaced by two (or possibly three) proofreading and two formatting 
   rounds: P1, P2, P3, F1 and F2. Thus a book will now progress through four (or five) rounds before 
   going to post-processing.
</p>

<p>Most proofreaders on the new site have started in the P1 round. After volunteers 
   have completed the requirements listed on each round's page,
   they can move into P2, P3, F1, and F2 rounds should they 
   wish to do so. Each round's page can be visited from the Activity Hub, and the 
   requirements for proofreading in each round (for example, the number of pages 
   completed in other rounds, time spent on site, and passes on the quizzes) 
   are shown on that round's page.
</p>

<p>The <a href="<?php echo $Workflow_Change_Announcement_URL; ?>">Workflow Change
   Announcement Thread</a> from 2005 contains more details of the changes.
</p>

<p>The duties for each round are as follows:</p>

<h2><a name="p1">P1 Proofreading Duties</a></h2>

<p>The aim of the P rounds is character proofreading: to match letter for letter, 
   punctuation mark for punctuation mark, the original text as if the original 
   text had no italics, font changes, etc.
</p>

<p>Thus, proofreaders are responsible, among other things, for the following:</p>

<ul style="margin-left: 2em;">
    <li>checking each letter of the scan, reading the text closely and correcting 
            scannos and stealth scannos;</li>
    <li>inserting Latin-1, non-ASCII-7 characters;</li>
    <li>adding markup for hyphens;</li>
    <li>removing additional spaces around punctuation and contractions;</li>
    <li>removing any other junk that has crept in (errant punctuation, etc); and</li>
    <li>marking footnote and endnote markers by putting square brackets [ ]
            around the numbers in the main text and using * for all symbolic footnotes.</li>
</ul>

<p>All these items require careful reading of the text and thus are the responsibility of proofreaders.</p>

<p>Proofreaders are no longer responsible for such things as: </p>
<ul style="margin-left: 2em;">
    <li>formatting the text: for example, adding markup for text style (italics, small caps etc.);</li>
    <li>adding markup for sidenotes and footnotes;</li>
    <li>adding markup for poetry or block-quotes; or</li>
    <li>unwrapping lines in poetry or indexes, adding blank lines for chapter headings, etc.</li>
</ul>

<p>If some formatting has already been included, for example, indented lines in 
   poetry, the formatting can be left in place or removed. Even if 
   the formatting is wrong as in <kbd>&lt;i&gt;</kbd>i.e<kbd>&lt;/i&gt;</kbd>., 
   please do not move it.  Leave it to the formatters to repair and concentrate on the text.
</p>

<p>Please note that the above is by no means a comprehensive list. The P rounds 
   have a revised set of <a href="<?php echo $code_url; ?>/faq/proofreading_guidelines.php">Proofreading
   Guidelines</a> to reflect the new responsibilities for this round and everyone is
   enthusiastically encouraged to check these Guidelines. There is also a new
   <a href="<?php echo $code_url; ?>/quiz/start.php?show_only=PQ">Proofreading Quiz</a>.
</p>

<p>P1 proofreaders should run WordCheck on their pages.</p>

<h2><a name="p2">P2 Proofreading Duties</a></h2>

<p>This is the close reading of pages already read in P1 for any small errors 
   that might have been missed in the P1 round. Otherwise, the responsibilities 
   for this round are as per the P1 round. P2 proofreaders are required to run
   WordCheck on their pages.
</p>

<h2><a name="p3">P3 Proofreading Duties</a></h2>

<p>This is the very close examination of pages already read in P1 and/or P2 for any
   remaining difficult-to-find errors which may have been missed in the previous 
   proofreading round(s). Otherwise, the responsibilities for this round are identical
   to the earlier proofreading rounds. P3 proofreaders are required to run WordCheck
   on their pages.
</p>


<h2><a name="f1">F1 &amp; F2 Formatting Duties</a></h2>

<p>The aim of the F rounds is to insert all markup and formatting: for example, 
   footnotes and sidenotes markup, text style markup (italics, small caps, etc.), 
   blank lines before and after chapter headings, and so on. Formatters arrange the 
   text on the page as required: indenting poetry, aligning tables, etc. Formatters are not 
   responsible for finding and fixing scannos or doing a letter-by-letter comparison 
   of the text, although they are encouraged to fix anything that catches their eye. 
   The F rounds use the <a href="<?php echo $code_url; ?>/faq/formatting_guidelines.php">Formatting Guidelines</a>,
   which are similar to the previous guidelines, with a few
   modifications such as the addition of small caps notation. There is a new 
   <a href="<?php echo $code_url; ?>/quiz/start.php?show_only=FQ">Formatting Quiz</a>.
</p>

<h2><a name="counts">Page Counts</a></h2>

<p>When the site was re-launched in June 2005, all users' page counts were saved
   as an "R*" page count (R* representing the old R1 and R2 rounds). Everybody has 
   started with a zero page count on the new site. User page counts are now kept separately
   in each round.
</p>

<h2><a name="track">Keeping Track of Your Projects and Difference Watching</a></h2>

<p>At the top of the Activity Hub page, under the DP header there is a link called &#8220;My Projects&#8221;.
   Clicking on &#8220;My Projects&#8221; will bring up a list of the books you 
   have worked on. Clicking on a book title will bring up the Project Page window. If 
   you are looking for your diffs pages, there is a new feature in the Page Details 
   row of the Project Page called &gt;&gt;Just my pages&lt;&lt;. Clicking on 
   &gt;&gt;Just my pages&lt;&lt; will display the pages that you have proofread in a 
   book. Checking diffs has never been easier! An important additional feature on 
   the Diffs Page is that there is an &#8220;edit&#8221; link beside each page you 
   have proofread. Clicking on &#8220;edit&#8221; will bring up the page should you 
   want to make any amendments to previously proofread pages.
</p>

<h2><a name="rprojects">{R} Projects</a></h2>

<p>(June 2005) At the time of writing there are a number of projects in the rounds with 
   {R} after the title. These are projects that have been returned to the proofreading 
   rounds from the PP pool or from PPer's personal queues. As many of these projects 
   mention on the Project Page, these projects have been proofread under older versions of the Guidelines. 
   As per above, proofreaders should <b>not</b> adjust any of the formatting. 
   Formatting checks will be completed by the formatters. Proofreaders should proofread 
   according to <a href="<?php echo $code_url; ?>/faq/proofreading_guidelines.php">current
   Guidelines</a>, for example, insert page numbers into tables of contents
   and indexes if they were removed previously.
</p>

<h2><a name="wordcheck">Wordcheck Word Lists and Interface</a></h2>

<p>(March 2007) The former spell check function in the Proofreading Interface has been superceded by
WordCheck. In this enhanced approach, projects can each have a project-specific set of
"good" and "bad" word lists which affect how these words are presented to the proofreader
when checking for spelling problems or steath scannos. Returning Project Managers should
review the <a href="<?php echo $code_url; ?>/faq/wordcheck-faq.php">Wordcheck FAQ</a> which covers the details of the process.
</p>


<h2><a name="links">Useful Links</a></h2>

<ul style="margin-left: 3em;">
    <li><a href="<?php echo $Workflow_Change_Announcement_URL; ?>">The Workflow Change Announcement Thread</a></li>
    <li><a href="<?php echo $code_url; ?>/faq/proofreading_guidelines.php">The Proofreading Guidelines</a></li>
    <li><a href="<?php echo $code_url; ?>/faq/proofing_summary.pdf">The Proofreading Summary (PDF)</a></li>
    <li><a href="<?php echo $code_url; ?>/quiz/start.php?show_only=PQ">The Proofreading Quiz</a></li>
    <li><a href="<?php echo $code_url; ?>/faq/formatting_guidelines.php">The Formatting Guidelines</a></li>
    <li><a href="<?php echo $code_url; ?>/faq/formatting_summary.pdf">The Formatting Summary (PDF)</a></li>
    <li><a href="<?php echo $code_url; ?>/quiz/start.php?show_only=FQ">The Formatting Quiz</a></li>
    <li><a href="<?php echo $code_url; ?>/stats/stats_central.php">Statistics Central</a></li>
    <li><a href="<?php echo $code_url; ?>/faq/wordcheck-faq.php">Wordcheck FAQ</a></li>
 </ul>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Return to:
     <a href=".."><?php echo "$site_name"; ?> home page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="faq_central.php"><?php echo "$site_abbreviation"; ?> FAQ Central page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="<?php echo $PG_home_url; ?>">Project Gutenberg home page</a>.
     </font>
  </td>
</tr>
</table>

<?php
// vim: sw=4 ts=4 expandtab
