<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofreading Guidelines','header');
?>

<!-- NOTE TO MAINTAINERS AND DEVELOPERS:

     There are now HTML comments interspersed in this document which are/will be
     used by a script which automagically slices out the Random Rule text for the
     database. It does this by copying:
	1) All text from one h_3 to the next h_3
		-OR-
	2) All text from h_3 to the END_RR comment line.

    This allows us to have "extra" information in the Guidelines, but leave it out
    in the Random Rule for purposes of clarity/brevity.

    If you are updating this document, the above should be kept in mind.
-->

<h1 align="center">Proofreading Guidelines</h1>

<h3 align="center">Version 1.7, generated August 31, 2004 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<h4>Proofreading Guidelines <a href="guidelines_francaises.html">in French</a> /
    Directives de Formatage <a href="guidelines_francaises.html">en fran&ccedil;ais</a></h4>

<h4>Check out the <a href="../quiz/start.php">Proofreading Quiz and Tutorial</a></h4>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Table of Contents</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">The Primary Rule</a></li>
      <li><a href="#summary">Summary Guidelines</a></li>
      <li><a href="#about">About This Document</a></li>
      <li><a href="#comments">Project Comments</a></li>
      <li><a href="#forums">Forum/Discuss this Project</a></li>
      <li><a href="#prev_pg">Fixing errors on Previous Pages</a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Formatting of the...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#title_pg">Front/Back Title Page</a></li>
        <li><a href="#toc">Table of Contents</a></li>
        <li><a href="#blank_pg">Blank Page</a></li>
        <li><a href="#page_hf">Page Headers/Page Footers</a></li>
        <li><a href="#chap_head">Chapter Headers</a></li>
        <li><a href="#sect_head">Section Headers</a></li>
        <li><a href="#maj_div">Other Major Divisions in Texts</a></li>
        <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
        <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
        <li><a href="#mult_col">Multiple Columns</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#footnotes">Footnotes/Endnotes</a></li>
        <li><a href="#italics">Italics</a></li>
        <li><a href="#bold">Bold Text</a></li>
        <li><a href="#supers">Superscripts</a></li>
        <li><a href="#subscr">Subscripts</a></li>
        <li><a href="#underl">Underlined Text</a></li>
        <li><a href="#spaced">S p a c e d &nbsp; O u t &nbsp; Text (gesperrt)</a></li>
        <li><a href="#font_sz">Font size changes</a></li>
        <li><a href="#word_caps">Word in all Caps or Small Caps</a></li>
        <li><a href="#drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></li>
        <li><a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a></li>
        <li><a href="#eol_hyphen">End-of-line Hyphenation</a></li>
        <li><a href="#eop_hyphen">End-of-page Hyphenation</a></li>
        <li><a href="#next_word">Single word at bottom of page</a></li>
        <li><a href="#initials">Initials</a></li>
        <li><a href="#contract">Contractions</a></li>
        <li><a href="#poetry">Poetry/Epigrams</a></li>
        <li><a href="#letter">Letters/Correspondence</a></li>
        <li><a href="#lists">Lists of Items</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#block_qt">Block Quotations</a></li>
        <li><a href="#double_q">Double Quotes</a></li>
        <li><a href="#single_q">Single Quotes</a></li>
        <li><a href="#quote_ea">Quote Marks on each line</a></li>
        <li><a href="#period_s">Periods Between Sentences</a></li>
        <li><a href="#punctuat">Punctuation</a></li>
        <li><a href="#line_br">Line Breaks</a></li>
        <li><a href="#extra_sp">Extra spaces or tabs between Words</a></li>
        <li><a href="#trail_s">Trailing Space at End-of-line</a></li>
        <li><a href="#line_no">Line Numbers</a></li>
        <li><a href="#extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></li>
        <li><a href="#period_p">Period Pause &quot;...&quot; (Ellipsis)</a></li>
        <li><a href="#a_chars">Accented/Non-ASCII Characters</a></li>
        <li><a href="#d_chars">Characters with Diacritical marks</a></li>
        <li><a href="#f_chars">Non-Latin Characters</a></li>
        <li><a href="#fract_s">Fractions</a></li>
        <li><a href="#page_ref">Page References "(See Pg. 123)"</a></li>
        <li><a href="#bk_index">Indexes</a></li>
        <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Specific Guidelines for Special Books</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#sp_ency">Encyclopedias</a></li>
        <li><a href="#sp_poet">Poetry Books</a></li>
        <li><a href="#sp_chem">Chemistry Books</a>   [to be completed.]</li>
        <li><a href="#sp_math">Mathematics Books</a> [to be completed.]</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Common Problems</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#OCR_1lI">OCR Problems: 1-l-I</a></li>
        <li><a href="#OCR_0O">OCR Problems: 0-O</a></li>
        <li><a href="#OCR_hyphen">OCR Problems: Hyphens and Dashes</a></li>
        <li><a href="#OCR_scanno">OCR Problems: Scannos</a></li>
        <li><a href="#hand_notes">Handwritten Notes in Book</a></li>
        <li><a href="#bad_image">Bad Image</a></li>
        <li><a href="#bad_text">Wrong Image for Text</a></li>
        <li><a href="#round1">Previous Proofreader Mistakes</a></li>
        <li><a href="#p_errors">Printer Errors/Misspellings</a></li>
        <li><a href="#f_errors">Factual Errors in Texts</a></li>
        <li><a href="#uncertain">Uncertain Items</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">The Primary Rule</a></h3>
<p><em>"Don't change what the author wrote!"</em>
</p>
<p>The final electronic book seen by a reader, possibly many years in the future, should accurately convey
   the intent of the author. If the author spelled words oddly, leave them spelled that way. If the author
   wrote outrageous racist or biased statements, leave them that way. If the author puts italics, bold text
   or a footnote every third word, mark them italicized, bolded or footnoted.
</p>
<p>We do change minor typographical conventions that don't affect the sense of what the author wrote.
   For example, we rejoin words that were broken at the end of a line (<a href="#eol_hyphen">End-of-line Hyphenation</a>).
   Changes such as these help us produce a <em>consistently formatted</em> version of the book.
   The proofreading rules we follow are designed to achieve this result. Please carefully read the rest of the
   Proofreading Guidelines with this concept in mind.
</p>
<p>To assist the next proofreader and the post-processor, we also preserve <a href="#line_br">line breaks</a>.
   This allows them to easily compare the lines in the text to the lines in the image.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h3><a name="summary">Summary Guidelines</a></h3>
<p>The <a href="summary.pdf">Summary of Guidelines</a> is a short, 2-page
   printer-friendly (.pdf) document that summarizes the main points of the Proofreading
   Guidelines, and gives examples of how to proofread. Beginning Proofreaders are
   encouraged to print out this document and keep it handy while proofreading.
</p>
<p>You may need to download and install a .pdf reader. You can get one free from Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">here</a>.
</p>

<h3><a name="about">About This Document</a></h3>
<p>This document is written in order to reduce formatting differences when proofreading
   of one book is distributed among many proofreaders, each working on different pages
   of the book. This helps us all do formatting <em>the same way</em>. That makes it
   easier for the post-processor to eventually combine all these proofread pages into
   one e-book.
</p>
<p><i>It is not intended as any kind of a general editorial or typesetting rulebook</i>.
</p>
<p>We've included in this document all the items that new users have asked about
   formatting while proofreading. If there are any items missing, or items that you
   consider should be done differently, or if something is vague, please let us know.
</p>
<p>This document is a work in progress. Help us to progress by posting your suggested changes in the
   Documentation Forum in <a href="<? echo $Guideline_discussion_URL; ?>">this thread</a>.
</p>

<h3><a name="comments">Project Comments</a></h3>

<p>On the proofreading interface page (Project Page) where you start proofreading pages, there is a section called
   "Project Comments" containing information specific to that project (book). <b>Read these
   before you start proofreading pages!</b> If the Project Manager wants you to format
   something in this book differently from the way specified in these Guidelines, that
   will be noted here. Instructions in the Project Comments <em>override</em> the rules
   in these Guidelines, so follow them. (This is also where the Project Manager may give
   you interesting tidbits of information about the author or the project.)
</p>
<p><em>Please also read the Project Thread</em>: The Project Manager may clarify project-specific
   guidelines here, and it is often used by proofreaders to alert other proofreaders to recurring
   issues within the project and how they can best be addressed.
</p>
<p>On the Project Page, the link 'Images, Pages Proofread, &amp; Differences' allows you to
   see how other proofreaders have made changes.
   <a href="<? echo $Using_project_details_URL ?>">This Forum thread</a>
   discusses different ways to use this information.
</p>

<h3><a name="forums">Forum/Discuss this Project</a></h3>
<p>On the proofreading interface page (Project Page) where you start proofreading pages, on the line "Forum", there is
   a link titled "Discuss this Project" (if the discussion has already started), or "Start
   a discussion on this Project" (if it hasn't). Clicking on that link will take you to a
   thread in the projects forum dedicated to this specific project. That is the place to ask
   questions about this book, inform the Project Manager about problems, etc. Using this project
   forum thread is the recommended way to communicate with the Project Manager and other
   proofreaders who are working on this book.
</p>

<h3><a name="prev_pg">Fixing errors on Previous Pages</a></h3>
<p>When you select a project for proofreading, the <a href="#comments">Project Comments</a>
   page is loaded. This page contains links to pages from this project that you have
   recently proofread. (If you haven't proofread any pages yet, there will be no links
   shown.)
</p>
<p>Pages listed under either "DONE" or "IN PROGRESS" are available to make proofreading
   corrections or to finish proofreading. Just click on the link to the page. So if you
   discover that you made a mistake on a page, or marked something incorrectly, you can
   click on that page here and re-open it to fix the error.
</p>
<p>For more detailed information, refer to either the <a href="prooffacehelp.php?i_type=0">Standard 
   Proofreading Interface Help</a> or the <a href="prooffacehelp.php?i_type=1">Enhanced Proofreading 
   Interface Help</a>, depending on which interface you are using.
</p>
<!-- END RR -->
<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Formatting of the...</font></td>
    </tr>
  </tbody>
</table>

<h3><a name="title_pg">Front/Back Title Page</a></h3>
<p>Proofread all the text, just as it was printed on the page, whether all capitals, upper and
   lower case, etc., including the years of publication or copyright.
</p>
<p>Older books often show the first letter as a large ornate graphic&mdash;proofread this as just the letter.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Sample Image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>GREEN FANCY</tt>
      </p>
      <p><tt>BY</tt></p>
      <p><tt>GEORGE BARR McCUTCHEON</tt></p>
      <p><tt>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
         "THE PRINCE OF GRAUSTARK," ETC.</tt></p>
      <p><tt>&lt;i&gt;WITH FRONTISPIECE BY&lt;/i&gt;<br>
         &lt;i&gt;C. ALLAN GILBERT&lt;/i&gt;</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY.</tt></p>
      <p><tt>1917</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="toc">Table of Contents</a></h3>
<p>Proofread the Table of Contents just as it is printed in the book, whether all capitals,
   upper and lower case, etc. and surround it with <tt>/*</tt> and <tt>*/</tt>. Leave a blank
   line between these markers and the rest of the text. Page number references should be
   retained and be placed at least six spaces past the end of the line.
</p>
<p>Remove any periods or asterisks (leaders) used to align the page numbers.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Sample Image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>CONTENTS</tt></p>
      <p><tt>/*<br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>
          I. THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
          MEET AND PART ON THE HIGHWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1<br>
          <br>
          II. THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>
          FALLS IN WITH FRIENDS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15<br>
          <br>
          III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>
          AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>
          <br>
          IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>
          TRAGEDY, AND A MAN WHO SAID "THANK YOU"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50<br>
          <br>
          V. THE FARM-BOY TELLS A GHASTLY STORY, AND AN<br>
          IRISHMAN ENTERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;67<br>
          <br>
          VI. CHARITY BEGINS FAR FROM HOME, AND A STROLL IN<br>
          THE WILDWOOD FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
          <br>
          VII. SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS ENCOUNTERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. A NOTE, SOME FANCIES, AND AN EXPEDITION IN<br>
          QUEST OF FACTS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;120<br>
          <br>
          IX. THE FIRST WAYFARER, THE SECOND WAYFARER, AND<br>
          THE SPIRIT OF CHIVALRY ASCENDANT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;134<br>
          <br>
          X. THE PRISONER OF GREEN FANCY, AND THE LAMENT OF<br>
          PETER THE CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;148<br>
          <br>
          XI. MR. SPROUSE ABANDONS LITERATURE AT AN EARLY<br>
          HOUR IN THE MORNING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;167<br>
          <br>
          XII. THE FIRST WAYFARER ACCEPTS AN INVITATION, AND<br>
          MR. DILLINGFORD BELABORS A PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. THE SECOND WAYFARER RECEIVES TWO VISITORS AT<br>
          MIDNIGHT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;199<br>
          <br>
          XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>
          OUTSIDE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;221<br>
          */<br>
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="blank_pg">Blank Page</a></h3>
<p>Proofread as <tt>[Blank Page]</tt> if both the text and the image are blank.
</p>
<p>If there is text in the proofreading text area and a blank image, or if there is an image
   but no text, follow the directions for a <a href="#bad_image">Bad Image</a>
   or <a href="#bad_text">Bad Text</a>.
</p>

<h3><a name="page_hf">Page Headers/Page Footers</a></h3>
<p>Remove page headers and page footers, but <em>not</em> <a href="#footnotes">footnotes</a>,
   from the text.
</p>
<p>The page headers are normally at the top of the image and have a page
   number opposite them. Page headers may be the same all through the book (often the
   title of the book and the author's name), they may be the same for each chapter
   (often the chapter number), or they may be different on each page (describing the
   action on that page). Remove them all, regardless, including the page number.
</p>
<!-- END RR -->

<p>A <a href="#chap_head">chapter header</a> will start further down the page and won't
   have a page number on the same line. See the next section for a specific example.
</p>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/#<br>In the United States?[A] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads &lt;i&gt;public&lt;/i&gt; and &lt;i&gt;private&lt;/i&gt;.<br>
    <br>
    How could a pastor collect his salary if the church should<br>
    refuse to pay it?<br>
    <br>
    Could a bank buy a piece of ground "on speculation?" To<br>
    build its banking-house on? Could a county lend money if it<br>
    had a surplus? State the general powers of a corporation.<br>
    Some of the special powers of a bank. Of a city.<br>
    <br>
    A portion of a man's farm is taken for a highway, and he is<br>
    paid damages; to whom does said land belong? The road intersects<br>
    the farm, and crossing the road is a brook containing<br>
    trout, which have been put there and cared for by the farmer;<br>
    may a boy sit on the public bridge and catch trout from that<br>
    brook? If the road should be abandoned or lifted, to whom<br>
    would the use of the land go?<br>#/<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER XXXV.<br>
    <br>
    COMMERCIAL PAPER.<br>
    <br>
    <br>
    &lt;b&gt;Kinds and Uses.&lt;/b&gt;--If a man wishes to buy some commodity<br>
    from another but has not the money to pay for<br>
    it, he may secure what he wants by giving his written<br>
    promise to pay at some future time. This written<br>
    promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers to an oral promise<br>
    for several reasons, only two of which need be mentioned<br>
    here: first, because it is &lt;i&gt;prima facie&lt;/i&gt; evidence of<br>
    the debt; and, second, because it may be more easily<br>
    transferred or handed over to some one else.<br>
    <br>
    If J.M. Johnson, of Saint Paul, owes C.M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J.M. Johnson a hundred dollars, it is<br>
    plain that the risk, expense, time and trouble of sending<br>
    the money to and from Chicago may be avoided,<br>
    <br>
    [Footnote A: The United States: "Its charter, the constitution. * * * Its flag the<br>
    symbol of its power; its seal, of its authority."--Dole.]
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="chap_head">Chapter Headers</a></h3>
<p>Proofread chapter headers as they appear in the text.
</p>
<p>A chapter header may start a bit farther down the page than the <a href="#page_hf">page header</a>
   and won't have a page number on the same line. Chapter Headers are often printed all caps; if so,
   keep them as all caps.
</p>
<p>Put 4 blank lines before the "CHAPTER XXX". Include these blank
   lines even if the chapter starts on a new page; there are no 'pages' in an e-book,
   so the blank lines are needed. Then leave 1 (one) blank line between each additional part
   of the chapter header, such as a chapter description, opening quote, etc., and
   finally leave 2 (two) blank lines before the start of the text of the chapter.
</p>
<p>Old books often printed the first word or two of every chapter in all caps;
   change these to upper and lower case (first letter only capitalized).
</p>
<p>Watch out for a missing double quote at the start of the first paragraph, which some
   publishers did not include or which the OCR missed due to a large capital in the
   original. If the author started the paragraph with dialog, insert the double quote.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Chapters">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="chap1.png" alt=""
          width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    GREEN FANCY<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER I<br>
    <br>
    THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
    MEET AND PART ON THE HIGHWAY<br>
    <br>
    <br>
    A solitary figure trudged along the narrow<br>
    road that wound its serpentinous way<br>
    through the dismal, forbidding depths of<br>
    the forest: a man who, though weary and footsore,<br>
    lagged not in his swift, resolute advance. Night<br>
    was coming on, and with it the no uncertain prospects<br>
    of storm. Through the foliage that overhung<br>
    the wretched road, his ever-lifting and apprehensive<br>
    eye caught sight of the thunder-black, low-lying<br>
    clouds that swept over the mountain and bore<br>
    down upon the green, whistling tops of the trees.<br>
    <br>
    At a cross-road below he had encountered a small<br>
    girl driving homeward the cows. She was afraid<br>
    of the big, strange man with the bundle on his back<br>
    and the stout walking stick in his hand: to her a<br>
    remarkable creature who wore "knee pants" and<br>
    stockings like a boy on Sunday, and hob-nail shoes,<br>
    and a funny coat with "pleats" and a belt, and a<br>
    green hat with a feather sticking up from the band.
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="sect_head">Section Headers</a></h3>
<p>Some texts have sections within chapters. Proof these headers as they appear in the text.
   Leave 2 blanks lines before the header and one after, unless the Project Manager has requested
   otherwise. If you are not sure if a header indicates a chapter or a section, post a question in
   the Project Thread, noting the page number.
</p>

<h3><a name="maj_div">Other Major Divisions in Texts</a></h3>
<p>Major Divisions in the text such as Preface, Foreword, Introduction, Prologue, Epilogue,
   Appendix, References, Conclusion, Glossary, Summary, Acknowledgements, Bibliography, etc., should
   be proofread in the same way as Chapter Headers, <i>i.e.</i> 4 blank lines before the heading and 2 blank lines
   before the start of the text.
</p>

<h3><a name="para_side">Paragraph Side-Descriptions (Sidenotes)</a></h3>
<p>Some books will have short descriptions of the paragraph along the side of the text.
   These are called sidenotes. Move sidenotes to just above the paragraph that they belong to.
   A sidenote should be surrounded by a sidenote tag <tt>[Sidenote:&nbsp;</tt> and <tt>]</tt>,
   with the text of the sidenote placed in between. Proofread the sidenote text as it is printed,
   preserving the line breaks, italics, etc.. Leave a blank line after the sidenote, so that it
   does not get merged into the paragraph when the text is rewrapped during post-processing.
</p>
<p>If there are multiple sidenotes for a single paragraph, put them one after another
   at the start of the paragraph. Leave a blank line separating each of them.
</p>
<p>If the paragraph began on a previous page, put the Sidenote at the top of the page
   and mark it with <tt>*</tt> so that the post-processor can see that it belongs on
   the previous page. Like this: <tt>*[Sidenote: <font color="red">(text of sidenote)</font>]</tt>. The
   post-processor will move them to the appropriate place.
</p>
<p>Sometimes a Project Manager will request that you put Sidenotes next to the sentence
   they apply to, rather than at the top or bottom of the paragraph. In this case,
   don't separate them out with blank lines.
</p>
<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Sidenotes"> <col width="128*">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Sample Image:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    *[Sidenote: Burning<br>
    discs<br>
    thrown into<br>
    the air.]<br>
    <br>
    that such as looked at the fire holding a bit of larkspur<br>
    before their face would be troubled by no malady of the<br>
    eyes throughout the year.[1] Further, it was customary at<br>
    Würzburg, in the sixteenth century, for the bishop's followers<br>
    to throw burning discs of wood into the air from a mountain<br>
    which overhangs the town. The discs were discharged by<br>
    means of flexible rods, and in their flight through the darkness<br>
    presented the appearance of fiery dragons.[2]<br>
    <br>
    [Sidenote: The Midsummer<br>
    fires in<br>
    Swabia.]<br>
    <br>
    [Sidenote: Omens<br>
    drawn from<br>
    the leaps<br>
    over the<br>
    fires.]<br>
    <br>
    [Sidenote: Burning<br>
    wheels<br>
    rolled<br>
    down hill.]<br>
    <br>
    In the valley of the Lech, which divides Upper Bavaria<br>
    from Swabia, the midsummer customs and beliefs are, or<br>
    used to be, very similar. Bonfires are kindled on the<br>
    mountains on Midsummer Day; and besides the bonfire<br>
    a tall beam, thickly wrapt in straw and surmounted by a<br>
    cross-piece, is burned in many places. Round this cross as<br>
    it burns the lads dance with loud shouts; and when the<br>
    flames have subsided, the young people leap over the fire in<br>
    pairs, a young man and a young woman together. If they<br>
    escape unsmirched, the man will not suffer from fever, and<br>
    the girl will not become a mother within the year. Further,<br>
    it is believed that the flax will grow that year as high as<br>
    they leap over the fire; and that if a charred billet be taken<br>
    from the fire and stuck in a flax-field it will promote the<br>
    growth of the flax.[3] Similarly in Swabia, lads and lasses,<br>
    hand in hand, leap over the midsummer bonfire, praying<br>
    that the hemp may grow three ells high, and they set fire<br>
    to wheels of straw and send them rolling down the hill.<br>
    Among the places where burning wheels were thus bowled<br>
    down hill at Midsummer were the Hohenstaufen mountains<br>
    in Wurtemberg and the Frauenberg near Gerhausen.[4]<br>
    At Deffingen, in Swabia, as the people sprang over the mid-*<br>
    <br>
    [Footnote 1: &lt;i&gt;Op. cit.&lt;/i&gt; iv. i. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>
    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J.W. Wolf, &lt;i&gt;Beiträge zur deutschen<br>
    Mythologie&lt;/i&gt;, i. p. 217, § 185).]<br>
    <br>
    [Footnote 2: J. Boemus, &lt;i&gt;Mores, leges et ritus<br>
    omnium gentium&lt;/i&gt; (Lyons, 1541), p.<br>
    226.]<br>
    <br>
    [Footnote 3: Karl Freiherr von Leoprechting,<br>
    &lt;i&gt;Aus dem Lechrain&lt;/i&gt; (Munich, 1855),<br>
    pp. 181 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der<br>
    Baumkultus&lt;i&gt;, p. 510.]<br>
    <br>
    [Footnote 4: A. Birlinger, &lt;i&gt;Volksthümliches aus<br>
    Schwaben&lt;/i&gt; (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 &lt;i&gt;sqq.&lt;/i&gt;, § 128, pp. 103<br>
    &lt;i&gt;sq.&lt;/i&gt;, § 129; &lt;i&gt;id., Aus Schwaben&lt;/i&gt; (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    &lt;i&gt;Deutsche Sagen, Sitten und Gebräuche<br>
    aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<br>
    423 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der Baumkultus&lt;/i&gt;,<br>
    p. 510.]<br>
    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="para_space">Paragraph Spacing/Indenting</a></h3>
<p>Put a blank line before the start of paragraphs, even if a paragraph starts at the top of a page.
   You should not indent the start of paragraphs, but if all paragraphs are already indented, don't
   bother removing those spaces&mdash;that can be done automatically during post-processing.
</p>
<p>See the <a href="#chap_head">Chapter Headers</a> image/text for an example.
</p>

<h3><a name="mult_col">Multiple Columns</a></h3>
<p>Proofread ordinary text which has been printed in two columns as a single column.
</p>
<p>Spans of multiple-column text within single column sections should be proofread as a single column
   by placing the text from the left-most column first, the text from the next one after it, and so on.
   You do not need to mark where the columns were split, just join them together.
</p>
<p>If the columns are lists of items, mark the start of the list with <tt>/*</tt>
   and the end with <tt>*/</tt> so that the lines do not get re-wrapped during post-processing.
   Leave a blank line between these markers and the rest of the text.
</p>
<p>See also the <a href="#bk_index">Indexes</a>, <a href="#lists">Lists of Items</a> and
   <a href="#tables">Tables</a> sections of the Proofreading Guidelines.
</p>

<h3><a name="illust">Illustrations</a></h3>
<p>Text for an illustration should be surrounded by an illustration tag <tt>[Illustration:&nbsp;</tt> and <tt>]</tt>,
   with the caption text placed in between. Proofread the caption text as it is printed, preserving
   the line breaks, italics, etc.
</p>
<p>If an illustration has no caption, add a tag <tt>[Illustration]</tt>.
</p>
<p>If the illustration is in the middle of or at the side of a paragraph, move the illustration tag
   to before or after the paragraph and leave a blank line to separate them. Rejoin the paragraph
   by removing any blank lines left by doing so.
</p>
<p>If there is no paragraph break on the page, mark the illustration tag with an
   <tt>*</tt> like so <tt>*[Illustration: <font color="red">(text of caption)</font>]</tt>, move it to the top of the page, and leave 1 (one)
   blank line after it.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Sample Image:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
   </tr>
    <tr>
    <td width="100%" valign="top">
      <table summary="" border="0" align="left"><tr>
      <td>
      <p><tt>[Illustration: Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>
      &lt;i&gt;Frontispiece&lt;/i&gt;<br>
      <br>
      &lt;i&gt;Her Weight in Gold&lt;/i&gt;]
      </tt></p>
      </td></tr></table>
    </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
 cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
   <tr>
     <th align="left" bgcolor="cornsilk">Sample Image: (Illustration in middle of paragraph)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
   </tr>
    <tr valign="top">
     <td>
<table summary="" border="0" align="left"><tr><td>
     <p><tt>
     such study are due to Italians. Several of these instruments<br>
     have already been described in this journal, and on the present<br>
     occasion we shall make known a few others that will<br>
     serve to give an idea of the methods employed.<br>
     </tt></p>
     <p><tt>[Illustration: FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
     SEISMIC MOVEMENTS.]</tt></p>
     <p><tt>
     For the observation of the vertical and horizontal motions<br>
     of the ground, different apparatus are required. The</tt>
     </p>
</td></tr></table>
    </td>
    </tr>
  </tbody>
</table>

<h3><a name="footnotes">Footnotes/Endnotes</a></h3>
<p><b>Footnotes are placed out-of-line</b>; that is, the text of the footnote is left
   at the bottom of the page and a tag placed where it is referenced in the text.
</p>
<p>During proofreading, this means:
</p>
<p>1. The number, letter, or other character that marks a footnote location should
   be surrounded with brackets (<tt>[</tt> and <tt>]</tt>). Remove any spaces
   before the <tt>[</tt>&mdash;keep it right next to the word being footnoted<tt>[1]</tt> or its
   punctuation mark,<tt>[2]</tt> as shown in the text, and the two examples in this sentence.
</p>
<p>When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;,
   etc.) we replace these with Capital letters in order (A, B, C, etc.).
</p>
<p>2. A footnote should be surrounded by a footnote tag <tt>[Footnote #:&nbsp;</tt> and <tt>]</tt>,
   with the footnote text placed in between, and the footnote number or letter placed where the # is
   shown in the tag. Proofread the footnote text as it is printed, preserving the line breaks, italics, etc.
   Leave the footnote text at the bottom of the page.  Be sure to use the same tag in the footnote as
   you used in the text where the footnote was referenced.
</p>
<!-- END RR -->

<p>In some books, the Project Manager may ask that you move the footnotes
   in-line; read the <a href="#comments">Project Comments</a> for instructions in this case.
</p>
<p>See the <a href="#page_hf">Page Headers/Page Footers</a> image/text for an example footnote.
</p>
<p>If there's a footnote at the bottom of the page with no footnote marker in the text,
   especially if it starts mid-sentence or mid-word, it's probably a continuation of a
   footnote from a previous page. Leave it at the bottom of the page near the other footnotes,
   and surround it with
   <tt>*[Footnote: <font color="red">(text of footnote)</font>]</tt>
   (without any footnote number or marker). The <tt>*</tt> indicates that the footnote was continued,
   and brings it to the attention of the post-processor.
</p>
<p>If a footnote continues on the next page (the page ends before the footnote does),
   leave the footnote at the bottom of the page, and just put an asterisk <tt>*</tt>
   where the footnote ends, like this: <tt>[Footnote 1: <font color="red">(text of footnote)</font>]*</tt>. (The
   <tt>*</tt> indicates that the footnote ended prematurely, and brings it to the
   attention of the post-processor, who will eventually join it up with the rest of the
   footnote text.
</p>
<p>If a continued footnote ends or starts on a hyphenated word, mark <b>both</b> the footnote
   and the word with <tt>*</tt>, thus:<br>
   <tt>[Footnote 1: This footnote is continued and the last word in it is also con-*]*</tt><br>
   for the leading fragment, and<br>
   <tt>*[Footnote: *tinued onto the next page.]</tt>.
</p>
<p>If a footnote or endnote is referenced in the text but does not appear on that page,
   keep the footnote/endnote number or marker and surround it with square brackets
   <tt>[</tt> and <tt>]</tt>. This is common in scientific and technical books, where
   footnotes are often grouped at the end of chapters. See "Endnotes" below.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Original Text:</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    The principal persons involved in this argument were Caesar<sup>1</sup>, former military<br>
    leader and Imperator, and the orator Cicero<sup>2</sup>. Both were of the aristocratic<br>
    (Patrician) class, and were quite wealthy.<br>
    <hr align="left" width="50%" noshade size="2">
    <font size=-1><sup>1</sup> Gaius Julius Caesar.</font><br>
    <font size=-1><sup>2</sup> Marcus Tullius Cicero.</font>
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Proofread with Out-of-Line Footnotes:</th>
    </tr>
      <tr valign="top">
      <td>
<table summary="" border="0" align="left"><tr><td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>[Footnote 1: Gaius Julius Caesar.]</tt><br>
    <br>
    <tt>[Footnote 2: Marcus Tullius Cicero.]</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<p>In some books, footnotes are separated from the main text by a horizontal line.
   We don't keep this so please just leave a blank line between the main text and the footnotes.
   (See example above.)
</p>
<p><b>Endnotes</b> are just footnotes that have been located together at the end of a
   chapter or at the end of the book, instead of on the bottom of each page. These
   are proofread in the same manner as out-of-line footnotes. Where you find an
   endnote reference in the text, just surround it with <tt>[</tt> and <tt>]</tt>.
   If you are proofreading one of the ending pages with the endnotes text on it,
   surround the text of each note with <tt>[Footnote #: <font color="red">(text of endnote)</font>]</tt>, with
   the endnote text placed in between, and the endnote number or letter placed where the # is.
   Put a blank line after each endnote so that they remain separate
   paragraphs when the text is rewrapped during post-processing.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Footnotes in <a href="#poetry">Poetry</a> or <a href="#tables">Tables</a></b>
   should be treated the same as other footnotes. Proofreaders should tag them and leave them
   at the bottom of the page; the post-processor will decide on the final placement.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Footnoted Poetry:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Mary had a little lamb<sup>1</sup><br>
    &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
    <hr align="left" width="50%" noshade size=2>
    <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.</font>
</td></tr></table>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/*<br>
    Mary had a little lamb[1]<br>
    &nbsp;&nbsp;Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    &nbsp;&nbsp;The lamb was sure to go!<br>
    */<br>
    <br>
    [Footnote 1: This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.]<br>
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="italics">Italics</a></h3>
<p>Proofread <i>italicized</i> text with <tt>&lt;i&gt;</tt> inserted at the start and
   <tt>&lt;/i&gt;</tt> inserted at the end of the italics. (Note the "/" in the closing
   tag.)
</p>
<p>Punctuation goes <b>outside</b> the italics, unless it is an entire sentence or section
   that is italicized, or the punctuation is itself part of a phrase, title
   or abbreviation that is italicized.
</p>
<p>The periods that mark an abbreviated word in the title of a journal such as <i>Phil. Trans.</i>
   are part of the title for italicization purposes, and are included within the italic tags, thus:
   <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>.
</p>
<p>For dates and similar phrases, proofread the entire phrase as
   italics, rather than marking the words as italics and the numbers as non-italics.
   The reason is that many typefaces found in older texts used the same design for numbers
   in both regular and italics. 
</p>
<p>If the italicized text consists of a series/list of words or names, mark these up with
   italics tags individually.
</p>
<!-- END RR -->

<p><b>Examples</b>&mdash;Italics:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Italics">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Text:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><tt>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</tt> </td>
    </tr>
    <tr>
      <td valign="top"><i>God knows what she saw in me!</i> I spoke<br> in such an affected manner.</td>
      <td valign="top"><tt>&lt;i&gt;God knows what she saw in me!&lt;/i&gt; I spoke<br> in such an affected manner.</tt></td>
    </tr>
    <tr>
      <td valign="top">As in many other of these <i>Studies</i>, and</td>
      <td valign="top"><tt>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, and</tt></td>
    </tr>
    <tr>
      <td valign="top">(<i>Psychological Review</i>, 1898, p. 160)</td>
      <td valign="top"><tt>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 160)</tt></td>
    </tr>
    <tr>
      <td valign="top">L. Robinson, art. "<i>Ticklishness</i>,"</td>
      <td valign="top"><tt>L. Robinson, art. "&lt;i&gt;Ticklishness&lt;/i&gt;,"</tt></td>
    </tr>
    <tr>
      <td valign="top">
      Proofreaders may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <tt>Proofreaders may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bold">Bold Text</a></h3>
<p>Proofread <b>bold text</b> (text printed in a heavier typeface) with
   <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt> after it. (Note the "/" in the closing
   tag.)
</p>
<p>Punctuation goes <b>outside</b> the bold tags, unless it is an entire sentence or section
   that is in bold, or the punctuation is itself part of a phrase, title
   or abbreviation that is in bold type.
</p>
<p>See the <a href="#page_hf">Page Headers/Page Footers</a> image/text for an example.
</p>
<p>Some Project Managers may specify in the
   <a href="#comments">Project Comments</a> that bold text be rendered as all caps.
</p>

<h3><a name="supers">Superscripts</a></h3>
<p>Older books often abbreviated words as contractions, and printed them as
   superscripts, for example:<br>
   &nbsp;&nbsp;&nbsp;&nbsp; Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Proofread these by inserting a single caret to identify this as a superscripted
   abbreviation/contraction, like this:<br>
   &nbsp;&nbsp;&nbsp;&nbsp; <tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>
<p>In scientific &amp; technical works, proofread superscripted characters with curly braces
   <tt>{</tt> and <tt>}</tt>, surrounding them, even if there is only one character superscripted.
   <br>For example:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; ... up to x<sup>n-1</sup> elements in the array.
   <br>would be proofread as
   <br>&nbsp; &nbsp; &nbsp; &nbsp; <tt>... up to x^{n-1} elements in the array.<br></tt>
</p>
<p>The Project Manager may specify in the <a href="#comments">Project Comments</a>
   that superscripted text be marked up differently.
</p>

<h3><a name="subscr">Subscripts</a></h3>
<p>Subscripted text is often found in scientific works, but is not common in other
   material. Proofread subscripted text by inserting an underline character <tt>_</tt> and
   surrounding the text with curly braces <tt>{</tt> and <tt>}</tt>.
   <br>For example:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; H<sub>2</sub>O.
   <br>would be proofread as
   <br>&nbsp; &nbsp; &nbsp; &nbsp; <tt>H_{2}O.<br></tt>
</p>

<h3><a name="underl">Underlined Text</a></h3>
<p>Proofread <u>underlined text</u> as <a href="#italics">Italics</a>, with <tt>&lt;i&gt;</tt> and
   <tt>&lt;/i&gt;</tt>. (Note the "/" in the closing tag.)
</p>
<p>Underlining was often used to indicate emphasis when the typesetter was unable to actually
   italicize the text, for example in a typewritten document.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   that underlined text be marked up with the <tt>&lt;u&gt;</tt> and <tt>&lt;/u&gt;</tt> tags.
</p>

<h3><a name="spaced">S p a c e d &nbsp; O u t &nbsp; Text (gesperrt)</a></h3>
<p>Proofread &nbsp; s p a c e d &nbsp; o u t &nbsp; text as <a href="#italics">Italics</a>, with <tt>&lt;i&gt;</tt> and
   <tt>&lt;/i&gt;</tt>, and remove the extra spaces between letters in each word. (Note the "/" in the closing tag.)
</p>
<p>This was a typesetting technique used to emphasize a piece of text in older German (and some
   Italian) books. Italics serve that purpose for modern readers, and extra spacing may not be
   clear on all the different screen sizes &amp; fonts where people may read the final e-book.
</p>

<h3><a name="font_sz">Font size changes</a></h3>
<p>Normally we do not do anything to mark changes in font size.
</p>
<p>The exception to this is when the font size changes to indicate a
   <a href="#block_qt">block quotation</a>.
</p>

<h3><a name="word_caps">Word in all Caps or Small Caps</a></h3>
<p>Proofread words that are printed in all capital letters (including small
   caps) as all capital letters.
</p>
<p>The exception to this is the <a href="#chap_head">first word of a chapter</a>:
   many old books typeset the first word of these in all caps; this should be changed to upper and
   lower case, so "ONCE upon a time," becomes "<tt>Once upon a time,</tt>"
</p>

<h3><a name="drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></h3>
<p>Proofread large and ornate graphic first letters of a chapter, section, or paragraph
   as just the letter.
</p>

<h3><a name="em_dashes">Dashes, Hyphens, and Minus Signs</a></h3>
<p> There are generally four such marks you will see in books:
  <ol compact>
    <li><i>Hyphens</i>. These are used to <b>join</b> words together, or sometimes to
        join prefixes or suffixes to a word.
    <br>Leave these as a single hyphen, with no spaces on either side.
    <li><i>En-dashes</i>. These are just a little longer, and are used for a
        <b>range</b> of numbers, or for a mathematical <b>minus</b> sign.
    <br>Proofread these as a single hyphen, too. Spaces before or after are determined by the
        way it was done in the book; usually no spaces in number ranges, usually spaces
        around mathematical minus signs, sometimes both sides, sometimes just before.
    <li><i>Em-dashes &amp; long dashes</i>. These serve as <b>separators</b> between
        words&mdash;sometimes for emphasis like this&mdash;or when a speaker gets a word caught in
        his throat&mdash;!
    <br>Proofread these as two hyphens. Don't leave a space before or after,
        even if it looks like there was a space in the original book image.
    <li><i>Still longer dashes</i>. These represent <b>omitted</b> or <b>censored</b>
        words or names.
    <br>Proofread these as 4 hyphens. When it represents a word, we leave appropriate space
        around it like it's really a word. If it's only part of a word, then no
        spaces&mdash;join it with the rest of the word.
  </ol>
<p>Note: If an em-dash appears at the start or end of a line of your OCR'd text, join it with the
   other line so that there are no spaces or line breaks around it. Only if the
   author used an em-dash to start or end the paragraph or line of poetry or dialog
   should you leave it at the start or end of a line. See the examples below.
</p>
<!-- END RR -->

<p><b>Examples</b>&mdash;Dashes, Hyphens, and Minus Signs:
</p>

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Hyphens and Dashes">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Image:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Proofread Text:</th>
      <th valign="top" bgcolor="cornsilk">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">four-part harmony</td>
      <td valign="top"><tt>four-part harmony</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><tt>2-1/2</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague<br> on both your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>I am hurt;--A plague<br> on both your houses!--I am dead.</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes.</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>long dash</td>
    </tr>
  </tbody>
</table>

<h3><a name="eol_hyphen">End-of-line Hyphenation</a></h3>
<p>Where a hyphen appears at the end of a line, join the two halves of the hyphenated
   word back together. If it is really a hyphenated word like well-meaning, join the
   two halves leaving the hyphen in-between. But if it was just hyphenated because it
   wouldn't fit on the line, and is not a word that is usually hyphenated, then join
   the two halves and remove the hyphen. Keep the joined word on the top line, and put
   a line break after it to preserve the line formatting&mdash;this makes it easier for
   the 2nd Round Proofreader. See the <a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a> section
   of the Proofreading Guidelines for examples of each kind (nar-row turns into narrow,
   but low-lying keeps the hyphen). If the word is followed
   by punctuation, then carry that punctuation onto the top line, too.
</p>
<p>Words like to-day and to-morrow that we don't commonly hyphenate now were often
   hyphenated in the old books we are working on. Leave them hyphenated the way the
   author did. If you're not sure if the author hyphenated it or not, leave the hyphen,
   put an <tt>*</tt> after it, and join the word together. Like this:
   <tt>to-*day</tt>. The asterisk will bring it to the attention of the post
   processor, who has access to all the pages, and can determine how the author
   typically wrote this word.
</p>

<h3><a name="eop_hyphen">End-of-page Hyphenation</a></h3>
<p>Proofread end-of-page hyphens by leaving the hyphen at the end of the last line, and mark it with a <tt>*</tt> after
   the hyphen.<br>
   For example, proofread:<br>
   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;something Pat had already become accus-<br>
   as:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>something Pat had already become accus-*</tt>
</p>
<p>On pages that start with part of a word from the previous page, place a <tt>*</tt> before the partial word.<br>
   To continue the above example, proofread:<br>
   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;tomed to from having to do his own family<br>
   as:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>*tomed to from having to do his own family</tt>
</p>
<p>These markings indicate to the post-processor that the word must be rejoined when the pages are
   combined to produce the final e-book.
</p>

<h3><a name="next_word">Single word at bottom of page</a></h3>
<p>Proofread these by deleting the word, even if it's the second half of a hyphenated word.
</p>
<p>In some older books, the single word at the bottom of the page (called a "catchword", usually
   printed near the right margin) indicates the first word on the next page of the book (called
   an "incipit"). It was used to alert the printer to print the correct reverse (called "verso");
   to make it easier for printers' helpers to make up the pages prior to binding; also to help
   the reader avoid turning over more than one page." 
</p>
<!-- END RR -->
<!-- We need an example here! -->

<h3><a name="initials">Initials</a></h3>
<p>Remove all spaces in names printed as initials, even if it appears that the typesetter included
   spaces (or partial spaces) in the printed version. For example, proofread H.&nbsp;M.&nbsp;S.&nbsp;Pinafore as <tt>H.M.S.
   Pinafore</tt>, Proofread G.&nbsp;B.&nbsp;Shaw as <tt>G.B. Shaw</tt>. This avoids the potential problem
   of the letters being broken across lines when text is rewrapped.
</p>
<!-- END RR -->

<h3><a name="contract">Contractions</a></h3>
<p>Remove any extra space in contractions, for example: would&nbsp;n't should
   be proofread as <tt>wouldn't</tt>.
</p>
<p>This was often an early printers convention, where the space was retained
   to indicate that 'would' and 'not' were originally separate words. It is
   also sometimes an artifact of the OCR. Remove the extra space in either case.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   not to remove extra spaces in contractions, particularly in the case of texts which
   contain slang, dialect, or are written in languages other than English.
</p>


<h3><a name="poetry">Poetry/Epigrams</a></h3>
<p>This section applies to an occasional Poem or Epigram in a mainly non-poetry book.
   For an entire book of poetry, see the
   <a href="doc-poet.php">special guidelines for Poetry Books</a>.
</p>
<p>Mark poetry or epigrams so the post-processor can find it more quickly. Insert
   a separate line with <tt>/*</tt> at the start of the poetry or epigram and a separate
   line with <tt>*/</tt> at the end. Leave a blank line between these markers and the rest
   of the text.
</p>
<p>Preserve the relative indentation of the individual lines of the poem or epigram by adding
   2, 4, 6 (or more) spaces in front of the indented lines to make them resemble the original.
</p>
<p>When a line of verse is too long for the printed page, many texts wrap the continuation
   onto the next printed line and place a wide indentation in front of it. These continuation lines
   should be rejoined with the line above. Continuation lines usually start with a lower case
   letter. They will appear randomly unlike normal indentation, which occurs at regular intervals
   in the metre of the poem.
</p>
<p>If the poetry is centered on the printed page, don't try to center the lines of
   poetry during proofreading. Move the lines to the left margin, and preserve the relative
   indentation of the lines.
</p>
<p><b>Footnotes</b> in poetry should be treated the same as usual footnotes during proofreading.
   See <a href="#footnotes">footnotes</a> for details.
</p>
<p><b>Line Numbers</b> in poetry should be kept. Put them at the end of the
   line, leaving at least 6 spaces between them and the end of the text.
   See <a href="#line_no">Line Numbers</a> for details.
</p>
<p>Check the <a href="#comments">Project Comments</a> for the specific text you are proofreading.
   Books of poetry often have special instructions from the Project Manager. Many times, you won't
   have to follow all these formatting guidelines for a book that is mostly or entirely poetry.
</p>
<!-- END RR -->

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="poetry.png" alt=""
          width="500" height="508"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
<tt>
to the scenery of his own country:<br></tt>
<p><tt>
/*<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, to be in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that April's there,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And whoever wakes in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree hole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In England--now!</tt>
</p><p><tt>
And after April, when May follows,<br>
And the whitethroat builds, and all the swallows!<br>
Hark! where my blossomed pear-tree in the hedge<br>
Leans to the field and scatters on the clover<br>
Blossoms and dewdrops--at the bent spray's edge--<br>
That's the wise thrush; he sings each song twice over,<br>
Lest you should think he never could recapture<br>
The first fine careless rapture!<br>
And though the fields look rough with hoary dew,<br>
All will be gay, when noontide wakes anew<br>
The buttercups, the little children's dower;<br>
--Far brighter than this gaudy melon-flower!<br>
*/<br></tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>
</td></tr></table>

      </td>
    </tr>
  </tbody>
</table>

<h3><a name="letter">Letters/Correspondence</a></h3>
<p>Proofread letters and correspondence as you would <a href="#para_space">paragraphs</a>.
   Put a blank line before the start of the letter, you do not need to duplicate any indenting.
</p>
<p>Surround consecutive heading or footer lines (such as addresses, date blocks, salutations or signatures) with
   <tt>/*</tt> and <tt>*/</tt> markers. Leave a blank line between the markers and the rest of the
   text. The markers will ensure the  individual lines are kept in post-processing and not rewrapped.
</p>
<p>Don't indent the heading or footer lines, even if they are indented or right justified in the original&mdash;just
   put them at the left margin. The post-processor will format them as needed.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="letter.png" alt=""
          width="500" height="217"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>&lt;i&gt;John James Audubon to Claude François Rozier&lt;/i&gt;</tt></p>
<p><tt>[Letter No. 1, addressed]</tt></p>
<p><tt>/*<br>
M. FR. ROZIER,<br>
Merchant-Nantes.<br>
NEW YORK, &lt;i&gt;10 January, 1807.&lt;/i&gt;</tt></p>
<p><tt>
DEAR SIR:<br>
*/</tt></p>
<p><tt>
We have had the pleasure of receiving by the &lt;i&gt;Penelope&lt;/i&gt; your<br>
consignment of 20 pieces of linen cloth, for which we send our<br>
thanks. As soon as we have sold them, we shall take great<br>
pleasure in making our return.</tt>
</p>
</td></tr></table>

      </td>
    </tr>
  </tbody>
</table>


<h3><a name="lists">Lists of Items</a></h3>
<p>Surround lists with <tt>/*</tt> and <tt>*/</tt> markers. Leave a blank line between these markers
   and the rest of the text. The markers will ensure the  individual lines are not rewrapped during
   post-processing. Use this markup for any such list that should not be reformatted, including
   lists of questions &amp; answers, items in a recipe, etc.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Text:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
Andersen, Hans Christian   Daguerre, Louis J. M.    Melville, Herman
Bach, Johann Sebastian     Darwin, Charles          Newton, Isaac
Balboa, Vasco Nunez de     Descartes, René          Pasteur, Louis
Bierce, Ambrose            Earhart, Amelia          Poe, Edgar Allan
Carroll, Lewis             Einstein, Albert         Ponce de Leon, Juan
Churchill, Winston         Freud, Sigmund           Pulitzer, Joseph
Columbus, Christopher      Lewis, Sinclair          Shakespeare, William
Curie, Marie               Magellan, Ferdinand      Tesla, Nikola
</pre>
</td></tr></table>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    /*<br>
    Andersen, Hans Christian<br>
    Bach, Johann Sebastian<br>
    Balboa, Vasco Nunez de<br>
    Bierce, Ambrose<br>
    Carroll, Lewis<br>
    Churchill, Winston<br>
    Columbus, Christopher<br>
    Curie, Marie<br>
    Daguerre, Louis J.M.<br>
    Darwin, Charles<br>
    Descartes, René<br>
    Earhart, Amelia<br>
    Einstein, Albert<br>
    Freud, Sigmund<br>
    Lewis, Sinclair<br>
    Magellan, Ferdinand<br>
    Melville, Herman<br>
    Newton, Isaac<br>
    Pasteur, Louis<br>
    Poe, Edgar Allan<br>
    Ponce de Leon, Juan<br>
    Pulitzer, Joseph<br>
    Shakespeare, William<br>
    Tesla, Nikola<br>
    */
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="tables">Tables</a></h3>
<p>Surround tables with <tt>/*</tt> and <tt>*/</tt> markers. Leave a blank line between these markers
   and the rest of the text. The markers will ensure the  individual lines are not rewrapped during
   post-processing. Format the table with spaces to look approximately like the original table.
   Don't make the table wider than 75 characters. Project Gutenberg's guidelines go on to say
   "...except where it can't be helped. Never, ever longer than 80...".
</p>
<p>Do not use tabs for formatting&mdash;use space
   characters only. Tab characters will line up differently between computers, and your careful
   formatting will not always display the same way.
</p>
<p>It's often hard to format tables in plain ASCII text; just do your best.
   This is much easier if you use a mono-spaced font such as <a href="font_sample.php">DPCustomMono</a> or Courier.
   Remember that the goal is to preserve the Author's meaning, while producing a
   readable table in an e-book. Sometimes this requires sacrificing the original
   format of the table on the printed page. Check the <a href="#comments">Project Comments</a> and discussion
   thread because other proofreaders may have settled on a specific format. If there is nothing there, you might
   find something useful in the <a href="<? echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a> forum thread.
</p>
<p><b>Footnotes</b> in tables should go at the end of the table. See <a href="#footnotes">footnotes</a> for details.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>/*
Deg. C.   Millimeters of Mercury.    Gasolene.
               Pure Benzene.

 -10&deg;               13.4                 43.5
   0&deg;               26.6                 81.0
 +10&deg;               46.6                132.0
  20&deg;               76.3                203.0
  40&deg;              182.0                301.8
*/</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 2">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>/*
TABLE II.

-----------------------+----+-----++-------------------------+----+------
                       | C  |     ||                         |  C |
Flat strips compared   | o  |     ||                         |  o |
with round wire 30 cm. | p  |Iron.|| Parallel wires 30 cm.   |  p | Iron.
in length.             | p  |     || in length.              |  p |
                       | e  |     ||                         |  e |
                       | r  |     ||                         |  r |
                       | .  |     ||                         |  . |
-----------------------+----+-----++-------------------------+----+------
Wire 1 mm. diameter    | 20 | 100 || Wire 1 mm. diameter     | 20 |  100
-----------------------+----+-----++-------------------------+----+------
        STRIPS.        |    |     ||       SINGLE WIRE.      |    |
0.25 mm. thick, 2 mm.  |    |     ||                         |    |
  wide                 | 15 |  35 || 0.25 mm. diameter       | 16 |   48
Same, 5 mm. wide       | 13 |  20 || Two  similar wires      | 12 |   30
 "   10  "    "        | 11 |  15 || Four    "      "        |  9 |   18
 "   20  "    "        | 10 |  14 || Eight   "      "        |  8 |   10
 "   40  "    "        |  9 |  13 || Sixteen "      "        |  7 |    6
Same strip rolled up in|    |     || Same 16 wires bound     |    |
  the form of a wire   | 17 |  15 ||   close together        | 18 |   12
-----------------------+----+-----++-------------------------+----+------
*/</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>



<h3><a name="block_qt">Block Quotations</a></h3>
<p>Surround block quotations with <tt>/#</tt> and <tt>#/</tt> markers. Leave a blank line between these markers
   and the rest of the text. The markers will ensure the block quotation is formatted properly during
   post-processing.
</p>
<p>Apart from adding the markers, block quotations should be proofed as any other text.
</p>
<p>Block quotations are long quotations (typically several lines and sometimes several pages) and are often
   (but not always) printed with wider margins or in a smaller font size&mdash;sometimes both.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Block Quotation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
	<p><tt>later day was welcomed in their home on the Hudson.<br>
	Dr. Bakewell's contribution was as follows:[24]</tt></p>
	<p><tt>/#<br>
	The uncertainty as to the place of Audubon's birth has been<br>
	put to rest by the testimony of an eye witness in the person<br>
	of old Mandeville Marigny now dead some years. His repeated<br>
	statement to me was, that on his plantation at Mandeville,<br>
	Louisiana, on Lake Ponchartrain, Audubon's mother was<br>
	his guest; and while there gave birth to John James Audubon.<br>
	Marigny was present at the time, and from his own lips, I have,<br>
	as already said, repeatedly heard him assert the above fact.<br>
	He was ever proud to bear this testimony of his protection<br>
	given to Audubon's mother, and his ability to bear witness as<br>
	to the place of Audubon's birth, thus establishing the fact that<br>
	he was a Louisianian by birth.<br>
	#/<br></tt>
	</p>
	<p><tt>We do not doubt the candor and sincerity of the<br>
	excellent Dr. Bakewell, but are bound to say that the<br>
	incidents as related above betray a striking lapse of<br>
	</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="double_q">Double Quotes</a></h3>
<p>Proofread these as plain ASCII <tt>"</tt> double quotes.
</p>
<p>Do not change double quotes to single quotes. Leave them as the Author wrote them.
</p>
<p>For quotes from non-English languages, use the quotation marks appropriate
   to that language if they are available in the Latin-1 character set.
</p>
<p>The French equivalent, guillemets, <tt>&laquo;like this&raquo;</tt>, are available
   from the pulldown menus in the proofreading interface, since they are part of Latin-1.
</p>
<p>The quotation marks used in some German texts, <tt>&bdquo;like this&rdquo;</tt>
   are not available in the pulldown menus, as they are not in Latin-1.
</p>
<p>The Project Manager may instruct you in the <a href="#comments">Project Comments</a>
   to proofread non-English language quotation marks differently for a particular book.
</p>

<h3><a name="single_q">Single Quotes</a></h3>
<p>Proofread these as the plain ASCII <tt>'</tt> single quote (apostrophe).
</p>
<p>Do not change single quotes to double quotes. Leave them as the Author wrote them.
</p>

<h3><a name="quote_ea">Quote Marks on each line</a></h3>
<p>Proofread quotation marks at the beginning of each line of a quotation by removing
   all of them <b>except for</b> the one at the start of the first line of the quotation.
</p>
<p>If the quotation goes on for multiple paragraphs, each paragraph should have an opening
   quote mark on the first line of the paragraph.
</p>
<p>Often there is no closing quotation mark until the very end of the quoted section of text,
   which may not be on the same page you are proofreading. Leave it that way&mdash;do not
   add closing quotation marks that are not in the page image.
</p>

<h3><a name="period_s">Periods Between Sentences</a></h3>
<p>Proofread periods between sentences with a single space after them.
</p>
<p>You do not need to remove extra spaces after periods if they're already in the scanned
   text&mdash;we can do that automatically during post-processing. See the <a href="#chap_head">Chapter Headers</a>
   image and text for an example.
</p>

<h3><a name="punctuat">Punctuation</a></h3>
<p>In general, there should be no space before punctuation characters except opening quotation
   marks. If scanned text has a space before punctuation, remove it.
</p>
<p>Spaces before punctuation sometimes appear because books typeset in the 1700's &amp; 1800's
   often used partial spaces before punctuation such as a semicolon or comma. 
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Punctuation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Scanned Text:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>

<h3><a name="line_br">Line Breaks</a></h3>
<p><b>Leave all line breaks in</b> so that the next proofreader and the post-processor can compare
   the lines in the text to the lines in the image easily. Be especially careful about this
   when rejoining <a href="#eol_hyphen">hyphenated words</a> or moving words around
   <a href="#em_dashes">em-dashes</a>. If the previous proofreader removed the line breaks,
   please replace them so that they once again match the image.
</p>
<p>Extra blank lines that are not in the image should be removed except where we intentionally
   add them for formatting. But blank lines at the bottom of the page are fine&mdash;these are removed
   during post-processing.
</p>

<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="extra_sp">Extra Spaces or Tabs Between Words</a></h3>
<p>Extra spaces and tab characters between words are common in OCR output. You don't need to bother
   removing these&mdash;that can be done automatically during post-processing.
</p>
<p>However, extra spaces around punctuation, em-dashes, quote marks, etc. <b>do</b> need to be
   removed when they separate the symbol from the word.
</p>
<p>For example, in <b>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse.</b> the space between
   the word "horse" and the semicolon should be removed. But the 2 spaces after the semicolon are
   fine&mdash;you don't have to delete one of them.
</p>

<h3><a name="trail_s">Trailing Space at End-of-line</a></h3>
<p>Do not bother inserting spaces at the ends of lines of text. It is a waste of your time for
   something that we can take care of automatically later. Similarly do not waste your time
   removing extra spaces at the ends of lines.
</p>

<h3><a name="line_no">Line Numbers</a></h3>
<p>Keep line numbers. Place them at least six spaces past the right hand end of
   the line, even if they are on the left side of the poetry/text in the original image.
</p>
<p>Line numbers are numbers in the margin for each line, or sometimes every fifth or tenth
   line, and are common in books of poetry. Since poetry will not be reformatted in the e-book
   version, the line numbers will be useful to readers.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></h3>
<p>Most paragraphs start on the line immediately after the end of the previous one. Sometimes
   two paragraphs are separated to indicate a "thought break." A "thought break" may take the
   form of a line of stars, hyphens or some other character, a plain or floridly decorated
   horizontal line, a simple decoration, or even just an extra blank line or two.
</p>
<p>A "thought break" may represent a change of scene or subject, a lapse in time or a bit
   of suspense. This is intended by the author, so we preserve them by putting a blank line,
   5 <tt>*</tt>'s indented 7 spaces and then 7 spaces apart, as shown in the example.
</p>
<p>Sometimes printers used decorative lines to mark the ends of chapters. As we already mark
   <a href="#chap_head">Chapter Headers</a>, there is no need to add a "thought break" marker.
</p>
<p>The proofreading interface has the "thought break" marker available to cut and paste.
</p>
<!-- END RR -->
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Thought Break">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk"> Sample Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="tbreak.png" alt=""
          width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    like the gentleman with the spiritual hydrophobia<br>
    in the latter end of Uncle Tom's Cabin.<br>
    Unconsciously Mr. Dixon has done his best to<br>
    prove that Legree was not a fictitious character.</tt>
    </p>
    <p><tt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</tt></p>
    <p><tt>
    Joel Chandler Harris, Harry Stillwell Edwards,<br>
    George W. Cable, Thomas Nelson Page,<br>
    James Lane Allen, and Mark Twain are Southern<br>
    men in Mr. Griffith's class. I recommend</tt>
    </p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="period_p">Period Pause &quot;...&quot; (Ellipsis)</a></h3>
<p>The guidelines are different for English and Languages Other Than English (LOTE).
</p>

<p><b>ENGLISH</b>: Leave a space before the three dots, and a space after. The exception is at the end
   of a sentence, when there would be no space, four dots, and a space after. This is
   also the case for any other ending punctuation mark: the 3 dots follow immediately,
   without any space.
</p>
<p>For example:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
   </tt>
</p>
<p>Sometimes you will see it with the punctuation at the end; so proofread it that way:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?
   </tt>
</p>
<p>Remove extra dots, if any, or add new ones, if necessary, to bring the number to three (or four) as appropriate.
</p>

<p><b>LOTE:</b> (Languages Other Than English) Use the general rule "Follow closely the style used in the printed page."
Sometimes the printed page is unclear: in that case, insert a <tt>*</tt> to draw the attention of the post-processor.
If spaces appear to exist between the dots, or between the word and the dots, replace the spaces with underscores:
<tt>like this_...</tt> or <tt>like this_._._.</tt> depending on the style.
This will avoid problems in rewrapping, and will be replaced by spaces during post-processing.
</p>

<h3><a name="a_chars">Accented/Non-ASCII Characters</a></h3>
<p>Please proofread these using the proper accented Latin-1 characters. Only use characters which are not
   in Latin-1 when the Project Manager instructs otherwise in the <a href="#comments">Project Comments</a>.
</p>
<p>There are several ways of inputting these characters:</p>
<ul compact>
  <li> The pull-down menus in the proofreading interface.</li>
  <li> Applets included with your operating system.
      <ul compact>
      <li>Windows: "Character Map"<br> Access it through:<br>
          Start: Run: charmap, or<br>
          Start: Accessories: System Tools: Character Map.</li>
      <li>Macintosh: Key Caps or "Keyboard Viewer"<br>
          For OS 9 and lower this is on the Apple Menu,<br>
          For OS X through 10.2, this is located the in Applications, Utilities folder<br>
          For OS X 10.3 and higher, this is in the Input Menu as "Keyboard Viewer."</li>
      <li>Linux: Various, depending on your desktop environment.<br>
          For KDE, try KCharSelect (in the Utilities submenu of the start menu).</li>
      </ul>
  </li>
  <li>An on-line program, such as <a
   href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li> Keyboard shortcuts.<br>
       Tables for <a href="#a_chars_win">Windows</a> and <a href="#a_chars_mac">Macintosh</a> which list these shortcuts are in the Proofreading Guidelines.</li>
  <li> Switching to a keyboard layout or locale which supports "deadkey" accents.
       <ul compact>
       <li>Windows: Control Panel (Keyboard, Input Locales)</li>
       <li>Macintosh: Input Menu (on Menu Bar)</li>
       <li>Linux: Change the keyboard in your X configuration.</li>
      </ul>
</ul>
<p>
   Project Gutenberg will post as a minimum, 7-bit ASCII versions of texts, but versions
   using other character encodings which can preserve more of the information from the
   original text are accepted. Currently for Distributed Proofreaders this means
   using Latin-1 or ISO 8859-1 and -15, and in the future will include Unicode.
</p>
<!-- END RR -->
<a name="a_chars_win"></a>
<p><b>For Windows</b>:
</p>
<ul compact>
  <li>You can use the Character Map program
     (Start: Run: charmap) to select an individual letter, and then cut &amp; paste.
  </li>
  <li>If you are using the enhanced proofreading interface, the <i>more</i> tag opens a pop-up
      window containing these characters, which you can then cut &amp; paste.
  </li>
  <li>Or you can type the Alt+NumberPad shortcut codes for these characters.
      <br>This is faster than using cut &amp; paste, once you get used to the codes.
      <br>Hold the Alt key and type the four digits on the
          <i>Number Pad</i>&mdash;the number row over the letters won't work.
      <br>You must type all 4 digits, including the leading 0 (zero).
          Note that the capital version of a letter is 32 less than the lower case.
      <br>These instructions are for the US-English keyboard layout. It may not work for other keyboard layouts.
      <br>The table below shows the codes we use.
          (<a href="charwin.pdf">Print-friendly version of this table)</a>
      <br>Do not use other special characters unless the Project Manager tells you to in the <a href="#comments">Project Comments</a>.
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows shortcuts">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Windows Shortcuts for Latin-1 symbols</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acute (aigu)</th>
      <th colspan=2>^ circumflex</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; ring</th>
      <th colspan=2>&AElig; ligature</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small a grave"         >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="Small a acute"         >&aacute; </td><td>Alt-0225</td>
      <td align="center" bgcolor="mistyrose" title="Small a circumflex"    >&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="Small a tilde"         >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="Small a umlaut"        >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="Small a ring"          >&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="Small ae ligature"     >&aelig;  </td><td>Alt-0230</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital A grave"       >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="Capital A acute"       >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="Capital A circumflex"  >&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="Capital A tilde"       >&Atilde; </td><td>Alt-0195</td>
      <td align="center" bgcolor="mistyrose" title="Capital A umlaut"      >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="Capital A ring"        >&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="Capital AE ligature"   >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small e grave"         >&egrave; </td><td>Alt-0232</td>
      <td align="center" bgcolor="mistyrose" title="Small e acute"         >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="Small e circumflex"    >&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small e umlaut"        >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital E grave"       >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="Capital E acute"       >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="Capital E circumflex"  >&Ecirc;  </td><td>Alt-0202</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital E umlaut"      >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small i grave"         >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="Small i acute"         >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="Small i circumflex"    >&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small i umlaut"        >&iuml;   </td><td>Alt-0239</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital I grave"       >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="Capital I acute"       >&Iacute; </td><td>Alt-0205</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital I umlaut"      >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ slash</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligature</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Alt-0248</td>
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>Use "oe"&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Capital O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>Use "Oe"&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small u grave"         >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="Small u acute"         >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="Small u circumflex"    >&ucirc;  </td><td>Alt-0251</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small u umlaut"        >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital U grave"       >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="Capital U acute"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="Capital U circumflex"  >&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital U umlaut"      >&Uuml;   </td><td>Alt-0220</td>
      <th colspan=2 bgcolor="cornsilk">currency     </th>
      <th colspan=2 bgcolor="cornsilk">mathematics  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td align="center" bgcolor="mistyrose" title="Capital Y umlaut"      >&Yuml;   </td><td>Alt-0159</td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;edilla </th>
      <th colspan=2 bgcolor="cornsilk">Icelandic    </th>
      <th colspan=2 bgcolor="cornsilk">marks        </th>
      <th colspan=2 bgcolor="cornsilk">accents      </th>
      <th colspan=2 bgcolor="cornsilk">punctuation  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Alt-0231</td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>Alt-0036</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Alt-0153</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>Alt-0185</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178</td>
      <th colspan=2 bgcolor="cornsilk">sz ligature        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>Alt-0189</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>Alt-0179</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Alt-0223</td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>Alt-0042</td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>Alt-0190</td>
  </tr>
  </tbody>
</table>

<p> &dagger;&nbsp;Note: In most cases, the two individual
    letters have replaced the oe ligature. (Thus &OElig;dipus becomes Oedipus.)
    Check the <a href="#comments">Project Comments</a> to see if the Project Manager
    wants you to use the ligature, otherwise use the two individual letters.
</p>
<p> <b>For Apple Macintosh</b>:
</p>
<ul compact>
  <li>You can use the "Key Caps" program as a reference.<br>
      In OS 9 &amp; earlier, this is located in the Apple Menu; in OS X through 10.2, it is located in Applications, Utilities folder.<br>
      This brings up a picture of the keyboard, and pressing shift, opt, command, or
      combinations of those keys shows how to produce each character. Use this
      reference to see how to type that character, or you can cut &amp; paste it from
      here into the text in the proofreading interface.</li>
  <li>In OS X 10.3 and higher, the same function is now a palette available from the Input
      menu (the drop-down menu attached to your locale's flag icon in the menu bar). It's labelled
      "Show Keyboard Viewer." If this isn't in your Input menu, or if you don't have that menu, you can
      activate it by opening System Preferences, the "International" panel, and selecting the "Input Menu"
      pane. Ensure that "Show input menu in menu bar" is checked. In the spreadsheet view, check the box
      for "Keyboard Viewer" in addition to any input locales you use.
  </li>
  <li>If you are using the enhanced proofreading interface, the <i>more</i> tag creates a pop-up
      window containing these characters, which you can then cut &amp; paste.
  <li>Or you can type the Apple Opt- shortcut codes for these characters.
      <br>This is a lot faster than using cut &amp; paste, once you get used to the codes.
      <br>Hold the Opt key and type the accent symbol, then type the letter to be accented
          (or, for some codes, only hold the Opt key and type the symbol).
      <br>These instructions are for the US-English keyboard layout. It may not work for other keyboard layouts.
      <br>The table below shows the codes we use.
          (<a href="charapp.pdf">Print-friendly version of this table)</a>
      <br>Do not use other special characters unless the Project Manager tells you to in the <a href="#comments">Project Comments</a>.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk"  >
      <th colspan=14>Apple Mac Shortcuts for Latin-1 symbols</th>
  <tr bgcolor="cornsilk"  >
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acute (aigu)</th>
      <th colspan=2>^ circumflex</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; ring</th>
      <th colspan=2>&AElig; ligature</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small a grave"         >&agrave; </td><td>Opt-~, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a acute"         >&aacute; </td><td>Opt-e, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a circumflex"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a tilde"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a umlaut"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a ring"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Small ae ligature"     >&aelig;  </td><td>Opt-'   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital A grave"       >&Agrave; </td><td>Opt-~, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A acute"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A circumflex"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A tilde"       >&Atilde; </td><td>Opt-n, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A umlaut"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A ring"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="Capital AE ligature"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small e grave"         >&egrave; </td><td>Opt-~, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e acute"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e circumflex"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small e umlaut"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital E grave"       >&Egrave; </td><td>Opt-~, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E acute"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E circumflex"  >&Ecirc;  </td><td>Opt-i, E</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital E umlaut"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small i grave"         >&igrave; </td><td>Opt-~, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i acute"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i circumflex"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small i umlaut"        >&iuml;   </td><td>Opt-u, i</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital I grave"       >&Igrave; </td><td>Opt-~, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I acute"       >&Iacute; </td><td>Opt-e, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital I umlaut"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ slash</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligature</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Opt-~, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Opt-o   </td>
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>Use "oe"&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Opt-~, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Opt-O   </td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>Use "Oe"&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small u grave"         >&ugrave; </td><td>Opt-~, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u acute"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u circumflex"    >&ucirc;  </td><td>Opt-i, u</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small u umlaut"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital U grave"       >&Ugrave; </td><td>Opt-~, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U acute"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U circumflex"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital U umlaut"      >&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor="cornsilk">currency     </th>
      <th colspan=2 bgcolor="cornsilk">mathematics  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Opt-+   </td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td align="center" bgcolor="mistyrose" title="Capital Y umlaut"      >&Yuml;   </td><td>Opt-u, Y</td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>Opt-V   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;edilla </th>
      <th colspan=2 bgcolor="cornsilk">Icelandic    </th>
      <th colspan=2 bgcolor="cornsilk">marks        </th>
      <th colspan=2 bgcolor="cornsilk">accents      </th>
      <th colspan=2 bgcolor="cornsilk">punctuation  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>Shift-Opt-5</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>Shift-Opt-6</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Shift-Opt-2</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Opt-*   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Opt-2   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(none)&nbsp;&Dagger;  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(none)&nbsp;&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz ligature        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Opt-8  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0   </td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(none)&nbsp;&Dagger;  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(none)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9   </td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(none)&nbsp;&Dagger;  </td>
  </tr>
  </tbody>
</table>
<p>&Dagger;&nbsp;Note: No equivalent shortcut, use drop-down menus.
</p>
<p>&dagger; Note that in most cases, the two individual
   letters have replaced the oe ligature. (Thus &OElig;dipus becomes Oedipus.)
   Check the <a href="#comments">Project Comments</a> to see if the Project Manager
   wants you to use the ligature, otherwise use the two individual letters.
</p>

<h3><a name="d_chars">Characters with Diacritical marks</a></h3>
<p>In some projects, you will find characters with special marks either above or below
   the normal latin A..Z character. These are called <i>diacritical marks</i>, and
   indicate a special pronunciation for this character.
   For proofreading, we indicate them in our normal ASCII text by using a
   specific coding: such as <tt>[)x]</tt> for a breve (u-shaped accent)
   above an x, or <tt>[x)]</tt> for a breve below.
</p>
<p>Be sure to include the square brackets (<tt>[&nbsp;]</tt>) around these, so the post-processor
   knows which letter it applies to. They will eventually replace these with
   whatever symbol works in each version of the text they produce, like 7-bit ASCII,
   8-bit, Unicode, html, etc.
</p>
<p>Note that when some of these marks appear on some characters (mainly vowels) our standard
   Latin-1 character set already includes that character with the diacritical mark. <b>In those
   cases, use the Latin-1 character (see <a href="#a_chars">here</a>), available from the
   drop down lists in the proofreading interface.</b>
</p>
<!-- END RR -->

<p>The table below lists the special codings currently used:<br>
   The "x" represents a character with a diacritical mark.<br>
   When proofreading, use the actual character from the text, not the <tt>x</tt> shown in the examples.
</p>

<!--
  diacritical mark           above  below
macron (straight line)       [=x]   [x=]
2 dots (diaresis or umlaut)  [:x]   [x:]
1 dot                        [.x]   [x.]
grave accent                 ['x]   [x'] or [/x] [x/]
acute (aigu) accent          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-shaped symbol)      [vx]   [xv]
breve (u-shaped symbol)      [)x]   [x)]
tilde                        [~x]   [x~]
cedilla                      [,x]   [x,]
-->

<table align="center" border="6" rules="all" summary="Diacriticals">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=4>Proofreading Symbols for Diacritical Marks</th>
  <tr bgcolor="cornsilk">
      <th>diacritical mark</th>
      <th>sample</th>
      <th>above</th>
      <th>below</th>
   </tr>
  <tr><td>macron (straight line)</td>
      <td align="center">&macr;</td>
      <td align="center"><tt>[=x]</tt></td>
      <td align="center"><tt>[x=]</tt></td>
      </tr>
  <tr><td>2 dots (diaresis, umlaut)</td>
      <td align="center">&uml;</td>
      <td align="center"><tt>[:x]</tt></td>
      <td align="center"><tt>[x:]</tt></td>
      </tr>
  <tr><td>1 dot</td>
      <td align="center">&middot;</td>
      <td align="center"><tt>[.x]</tt></td>
      <td align="center"><tt>[x.]</tt></td>
      </tr>
  <tr><td>grave accent</td>
      <td align="center">`</td>
      <td align="center"><tt>[`x]</tt> or <tt>[\x]</tt></td>
      <td align="center"><tt>[x`]</tt> or <tt>[x\]</tt></td>
      </tr>
  <tr><td>acute accent (aigu)</td>
      <td align="center">&acute;</td>
      <td align="center"><tt>['x]</tt> or <tt>[/x]</tt></td>
      <td align="center"><tt>[x']</tt> or <tt>[x/]</tt></td>
      </tr>
  <tr><td>circumflex</td>
      <td align="center">&circ;</td>
      <td align="center"><tt>[^x]</tt></td>
      <td align="center"><tt>[x^]</tt></td>
      </tr>
  <tr><td>caron (v-shaped symbol)</td>
      <td align="center"><font size="-2">&or;</font></td>
      <td align="center"><tt>[vx]</tt></td>
      <td align="center"><tt>[xv]</tt></td>
      </tr>
  <tr><td>breve (u-shaped symbol)</td>
      <td align="center"><font size="-2">&cup;</font></td>
      <td align="center"><tt>[)x]</tt></td>
      <td align="center"><tt>[x)]</tt></td>
      </tr>
  <tr><td>tilde</td>
      <td align="center">&tilde;</td>
      <td align="center"><tt>[~x]</tt></td>
      <td align="center"><tt>[x~]</tt></td>
      </tr>
  <tr><td>cedilla</td>
      <td align="center">&cedil;</td>
      <td align="center"><tt>[,x]</tt></td>
      <td align="center"><tt>[x,]</tt></td>
      </tr>
  </tbody>
</table>

<h3><a name="f_chars">Non-Latin Characters</a></h3>
<p>There are projects which contain text printed in non-Latin characters; that is, characters
   other than the Latin A...Z characters, for example Greek, Cyrillic (used in 
   Russian, Slavic and other languages), Hebrew, or Arabic characters.
</p><? if(strcasecmp($charset,"UTF-8")) { ?>
<p>For Greek, you should attempt a transliteration. Transliteration involves converting
   each character of the foreign text into the equivalent ASCII Latin letter(s). A Greek
   transliteration tool is provided in the proofing interface to make this task much easier.
</p>
<p>Press the "Greek" button near the bottom of the proofreading interface to pop-up the tool.
   In the tool, click on the Greek characters that match the word or phrase you are transliterating,
   and the appropriate ASCII characters will appear in the text box. When you are done,
   simply cut and paste this transliterated text into the page you are proofreading.
   Surround the transliterated text with the Greek markers <tt>[Greek:&nbsp;</tt> and <tt>]</tt>.
   For example, <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b>
   would become <tt>[Greek: Biblos]</tt>. ("Book"&mdash;so appropriate for DP!)
</p>
<p>If you are uncertain about your transliteration, mark it with an <tt>*</tt> to bring it to
   the attention of the second round proofreader or the post-processor.
</p>
<p>For other languages which cannot be so easily transliterated, such as Cyrillic,
   Hebrew or Arabic, surround the text with appropriate markers; <tt>[Cyrillic:&nbsp;**]</tt>,
   <tt>[Hebrew:&nbsp;**]</tt>, or <tt>[Arabic:&nbsp;**]</tt> and leave it as scanned.
   Include the <tt>**</tt> so the post-processor can address it later.
</p>
<!-- END RR -->

<ul compact>
  <li>Greek: <a href="<? echo $PG_greek_howto_url; ?>">Greek HOWTO</a> (from
      Project Gutenberg) Or see the "Greek" pop-up tool in the proofreading interface.
  </li>
  <li>Cyrillic: While a standard transliteration scheme exists for Cyrillic, we only recommend
      you attempt a transliteration if you are fluent in the languages which use it. Otherwise, just mark
      it as indicated above. You may find this
      <a href="http://learningrussian.com/transliteration.htm">Transliteration Table</a> useful.
  </li>
  <li>Hebrew and Arabic:
      Not recommended unless you are fluent. There are significant
      difficulties transliterating these languages and neither <a href="..">Distributed
      Proofreaders</a> nor <a href="<? echo $PG_home_url; ?>">Project Gutenberg</a>
      have yet chosen a standard method.
  </li>
</ul>
<? } else { ?>
<p>These characters should be entered in the text just as Latin characters are.
   (<b>WITHOUT transliteration!</b>)
</p>
<p>If a document is written entirely in a non-Latin script, it is the best to install a
   keyboard driver which supports the language. Consult your operating system manual for
   instructions on how to do that.
</p>
<p>If the script appears only occasionaly, you may use a separate program to enter it.
   See <a href="#a_chars">above</a> for some of the programs.
</p>
<p>If you are uncertain about a character or an accent, mark it with an <tt>*</tt> to
   bring it to the attention of the second round proofreader or the post-processor.
</p>
<p>For scripts which cannot be so easily entered, such as Arabic, surround the text
   with appropriate markers: <tt>[Arabic:&nbsp;**]</tt> and leave it as scanned.
   Include the <tt>**</tt> so the post-processor can address it later.
</p>
<? } ?>

<h3><a name="fract_s">Fractions</a></h3>
<p>Proofread <b>fractions</b> as follows: <tt>2&frac12;</tt> becomes <tt>2-1/2</tt>.
   The hyphen prevents the whole and fractional part from becoming
   separated when the lines are rewrapped during post-processing.
</p>

<h3><a name="page_ref">Page References &quot;See Pg. 123&quot;</a></h3>
<p>Proofread page number references within the text such as <tt>(see p. 123)</tt> as
   they appear in the image.</p>
<p>Check the <a href="#comments">Project Comments</a> to see if the Project Manager
   has special requirements for page references.
</p>

<h3><a name="bk_index">Indexes</a></h3>
<p>Please retain page numbers in index pages. Surround the index with <tt>/*</tt> and <tt>*/</tt> tags,
   leaving a blank line before <tt>/*</tt> and after <tt>*/</tt>. You don't need to align the numbers
   as they appear in the scan; just put a comma or semicolon, followed by the page numbers.
</p>
<p>Indexes are often printed in 2 columns; this narrower space can cause entries to
   split onto the next line. Rejoin these back onto a single line.
</p>
<p>Indexes are a case where long lines created by following this rule are acceptable, since the lines
   will be re-wrapped to the proper width and indentation during post-processing.
</p>
<p>Place one blank line between each entry in the index.
</p>
<p>For sub-topic listings in an index, start each one on a new line, indented 2 spaces.
</p>
<p>Treat each new section in an index (A, B, C...) the same as a <a href="#sect_head">section header</a> by placing 2
   blank lines before it.
</p>
<p>Old books sometimes printed the first word of each letter in the index in all caps;
   change this to match the style used for the rest of the index entries.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Scanned Text:</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Elizabeth I, her royal Majesty the<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text: (with rejoined lines)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/*<br>
    Elizabeth I, her royal Majesty the Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.<br>
    */</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Aligning Index Subtopics">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Scanned Text:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Hooker, Jos., maj. gen. U. S. V., 345; assigned<br>
    &nbsp;&nbsp;to command Porter's corps, 350; afterwards,<br>
    &nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382; unacceptable to Halleck,<br>
    &nbsp;&nbsp;retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209; notorious secessionist in<br>
    &nbsp;&nbsp;Kanawha valley, 217; controversy with Gen.<br>
    &nbsp;&nbsp;Cox over escaped slave, 233.<br>
    <br>
    Hosea, Lewis M., 187; capt. on Gen. Wilson's staff, 194.<br>
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text: (with index subtopics aligned)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/*<br>
    Hooker, Jos., maj. gen. U.S.V., 345;<br>
    &nbsp;&nbsp;assigned to command Porter's corps, 350;<br>
    &nbsp;&nbsp;afterwards, McDowell's, 367;<br>
    &nbsp;&nbsp;in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382;<br>
    &nbsp;&nbsp;unacceptable to Halleck, retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209;<br>
    &nbsp;&nbsp;notorious secessionist in Kanawha valley, 217;<br>
    &nbsp;&nbsp;controversy with Gen. Cox over escaped slave, 233;<br>
    <br>
    Hosea, Lewis M., 187;<br>
    &nbsp;&nbsp;capt. on Gen. Wilson's staff, 194.<br>
    */</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="play_n">Plays: Actor Names/Stage Directions</a></h3>
<p>For all plays:</p>
<ul compact>
 <li>Proofread cast listings (Dramatis Person&aelig;) as <a href="#lists">lists</a>.</li>
 <li>Put four blank lines before the beginning of an Act.</li>
 <li>Put two blank lines before the beginning of each Scene.</li>
 <li>In dialogue, treat a change in speaker as a new paragraph, with one blank line between.</li>
 <li>Proofread actor names as they are in the original text, whether they are <a href="#italics">italics</a>,
   <a href="#bold">bold</a> or <a href="#word_caps">all capital</a> letters.</li>
<li>Stage directions are formatted as they are in the original text.<br>
    If the stage direction on a line by itself, proofread it that way; if it is at the end of a line of dialogue, leave it there.<br>
   Stage directions often begin with an opening bracket and omit the closing bracket.<br>
   This convention is retained; do not close the brackets. Italics are generally placed inside the brackets.</li>
</ul>
<p>For metrical plays: (Plays written as rhymed poetry)</p>
<ul compact>
 <li>Many plays are metrical, and like poetry should not be rewrapped.
 Surround metred text with <tt>/*</tt> and <tt>*/</tt> as for poetry.</li>
 <li>Preserve relative indenting of dialog when a single metrical line is
 shared by more than one speaker.</li>
 <li>Rejoin metrical lines that were split due to width restrictions of the paper, just as in poetry.<br>
   If the continuation is only a word or so, it is often shown on the line
   above or below following a (, rather than having a line of its own.<br>
   See the <a href="#play3">example</a>.</li>
</ul>
<p>Please check the <a href="#comments">Project Comments</a>, as the Project Manager may
   specify different formatting.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Sample Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; But why a Peel-crow here?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
&lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Sample Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play2.png" width="500"
          height="680" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
CLIN. And do I hold thee, my Antiphila,<br>
Thou only wish and comfort of my soul!<br>
<br>
SYRUS. In, in, for you have made our good man wait. (&lt;i&gt;Exeunt.&lt;/i&gt;<br>
*/<br>
<br>
<br>
<br>
<br>
ACT THE THIRD.<br>
<br>
<br>
SCENE I.<br>
<br>
<br>
/*<br>
CHREM. 'Tis now just daybreak.--Why delay I then<br>
To call my neighbor forth, and be the first<br>
To tell him of his son's return?--The youth,<br>
I understand, would fain not have it so.<br>
But shall I, when I see this poor old man<br>
Afflict himself so grievously, by silence<br>
Rob him of such an unexpected joy,<br>
When the discov'ry can not hurt the son?<br>
No, I'll not do't; but far as in my pow'r<br>
Assist the father. As my son, I see,<br>
Ministers to th' occasions of his friend,<br>
Associated in counsels, rank, and age,<br>
So we old men should serve each other too.<br>
*/<br>
<br>
<br>
SCENE II.<br>
<br>
&lt;i&gt;Enter&lt;/i&gt; MENEDEMUS.<br>
<br>
<br>
/*<br>
MENE. (&lt;i&gt;to himself&lt;/i&gt;). Sure I'm by nature form'd for misery<br>
Beyond the rest of humankind, or else<br>
'Tis a false saying, though a common one,<br>
"That time assuages grief." For ev'ry day<br>
My sorrow for the absence of my son<br>
Grows on my mind: the longer he's away,<br>
The more impatiently I wish to see him,<br>
The more pine after him.<br>
<br>
CHREM. But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; MENEDEMUS.)<br>
Yonder he stands. I'll go and speak with him.<br>
Good-morrow, neighbor! I have news for you;<br>
Such news as you'll be overjoy'd to hear.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play3"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Sample Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play3.png" width="502"
          height="98" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
Or not slept well to night; some dream (&lt;i&gt;Ismena&lt;/i&gt;?)<br>
<br>
&lt;i&gt;Ism.&lt;/i&gt; My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="anything">Anything else that needs special handling or that you're unsure of</a></h3>
<p>While proofreading, if you encounter something that isn't covered in these guidelines that you
   think needs special handling or that you are not sure how to handle, post your question, noting
   the png (page) number, in the Project Discussion thread (a link to the project-specific forum is
   in the <a href="#comments">Project Comments</a>), and put a note in the proofread text explaining
   the problem. Your note will explain to the next proofreader or post-processor what the problem or
   question is.
</p>
<p>Start your note with a square bracket and two asterisks <tt>[**</tt> and end it with another square bracket <tt>]</tt>.
   This clearly separates it from from the Author's text and signals the next proofreader to stop and carefully examine
   this part of the text &amp; the matching image to address any issues.
</p>
<p>If you are proofreading in a later round and come across a note from a proofreader in a previous round,
   once you have resolved the issue, please take a moment and provide Feedback to them by clicking on their
   name in the proofreading interface and posting a private message to them explaining how to handle the
   situation in the future.
</p>
<!-- END RR -->

<br>
<table width="100%" border="0" cellspacing="0" summary="Other Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<br>

<h2><a name="sp_ency"></a>
    <a name="sp_chem"></a>
    <a name="sp_math"></a>
    <a name="sp_poet"></a>
    Specific Guidelines for Special Books</h2>
<p>These particular types of books have specific guidelines that add to or
   modify the normal guidelines given in this document. Projects for these
   books are often difficult, and are not recommended for beginning proofreaders.
   They are more appropriate to experienced proofreaders or people who have
   expertise in the particular field.
</p>
<p>Click on the link below when you need to see the guidelines for one of these
   types of books.
</p>
<ul compact>
  <li><b><a href="doc-ency.php">Encyclopedias</a></b></li>
  <li><b><a href="doc-poet.php">Poetry Books</a></b></li>
  <li><b>                       Chemistry Books    [to be completed.]</b></li>
  <li><b>                       Mathematics Books  [to be completed.]</b></li>
</ul>

<table width="100%" border="0" cellspacing="0" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Common Problems</h2>

<h3><a name="OCR_1lI">OCR Problems: 1-l-I</a></h3>
<p>OCR commonly has trouble distinguishing between the digit '1' (one), the
   lowercase letter 'l' (ell), and the uppercase letter 'I'. This is especially true for
   books where the pages may be in poor condition.
</p>
<p>Watch out for these. Read the context of the sentence to determine which is the correct
   character, but be careful&mdash;often your mind will automatically 'correct' these as you are reading.
</p>
<p>Noticing these is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.
</p>

<h3><a name="OCR_0O">OCR Problems: 0-O</a></h3>
<p>OCR commonly has trouble distinguishing between the digit '0' (zero), and the uppercase letter 'O'.
   This is especially true for books where the pages may be in poor condition.
</p>
<p>Watch out for these. Normally the context of the sentence is sufficient to determine which is the
   correct character, but be careful&mdash;often your mind will automatically 'correct' these as
   you are reading.
</p>
<p>Noticing these is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.
</p>

<h3><a name="OCR_hyphen">OCR Problems: Hyphens and Dashes</a></h3>
<p>OCR commonly has trouble distinguishing between dashes &amp; hyphens. Proofread these
   carefully&mdash;OCR'd text often has only one hyphen for an em-dash that should have two.
   See the rules for a <a href="#eol_hyphen">hyphenated words</a> and <a href="#em-dashes">em-dashes</a>
   for more detailed information.
</p>
<p>Noticing these is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.
</p>

<h3><a name="OCR_scanno">OCR Problems: Scannos</a></h3>
<p>Another common OCR issue is misrecognition of characters. We call these errors "scannos" (like "typos").
   This misrecognition can result in a word which:</p>
<ul compact>
   <li>appears to be correct at first glance, but is actually misspelled.<br>
       These can usually be caught by running the spellcheck from the proofreading interface.</li>
   <li>is changed to a different but otherwise valid word that does not match what is in the page image.<br>
       These are subtle because they can only be caught by someone actually reading the text.</li>
</ul>
<p>Possibly the most common example of the second type is "and" being OCR'ed as "arid." Other examples: "eve" for "eye",
   "Torn" for "Tom", "train" for "tram". This type is harder to spot and we have a special term for them: "Stealth Scannos."
   We collect examples of Stealth Scannos in <a href="<? echo $Stealth_Scannos_URL; ?>">this thread</a>.
</p>
<p>Spotting scannos is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Handwritten Notes in Book</a></h3>
<p>Do not include handwritten notes in a book (unless it is overwriting faded, printed text to make it more visible).
   Do not include handwritten marginal notes made by readers, etc.
</p>
<p>Some Project Managers may ask that handwritten notes be marked with <tt>[HW: (text of the note)]</tt>.
</p>

<h3><a name="bad_image">Bad Images</a></h3>
<p>If an image is bad (not loading, chopped off, unable to be read), please put a post
   about this bad image in the Project Comments <a href="#forums">forum</a>. Do not
   click on "Return Page to Round"; if you do, the page will be reissued to the next
   proofreader. Instead, click on the "Report Bad Page" button so this page is
   'quarantined'.
</p>
<p>Note that some page images are quite large, and it is common for your browser to
   have difficulty displaying them, especially if you have several windows open or are
   using an older computer. Before reporting this as a bad page, try clicking on the
   "Image" line on the bottom of the page to bring up just the image in a new window.
   If that brings up a good image, then the problem is probably in your browser or
   system.
</p>
<p>It's fairly common for the image to be good, but the OCR scan is missing the first
   line or two of the text. Please just type in the missing line(s). If nearly all of
   the lines are missing in the scan, then either type in the whole page (if you are
   willing to do that), or just click on the "Return Page to Round" button and the page
   will be reissued to someone else. If there are several pages like this, you might
   post a note in the Project Comments <a href="#forums">forum</a> to notify the
   Project Manager.
</p>

<h3><a name="bad_text">Wrong Image for Text</a></h3>
<p>If there is a wrong image for the text given, please put a post about this bad image
   in the Project Comments <a href="#forums">forum</a>. Do not click on "Return Page
   to Round"; if you do, the page will be reissued to the next proofreader. Instead, click
   on the "Report Bad Page" button so this page is 'quarantined'.
</p>

<h3><a name="round1">Previous Proofreader Mistakes</a></h3>
<p>If the previous proofreader made a lot of mistakes or missed a lot of things,
   please take a moment and provide Feedback to them by clicking on their name
   in the proofreading interface and posting a private message to them explaining
   how to handle the situation so that they will know how in the future.
</p>
<p><em>Please be nice!</em> Everyone here is a volunteer and presumably trying their best.
   The point of your feedback message should be to inform them of the correct way to
   proofread, rather than to criticize them. Give a specific example from their work
   showing what they did, and what they should have done.
</p>
<p>If the previous proofreader did an outstanding job, you can also send them a message
   about that&mdash;especially if they were working on a particularly difficult page.
</p>

<h3><a name="p_errors">Printer Errors/Misspellings</a></h3>
<p>Correct all of the words which the OCR has misread (scannos), but do not correct
   what may appear to you to be misspellings or printer errors that occur on the scanned
   image. Many of the older texts have words spelled differently from modern usage and
   we retain these older spellings, including any accented characters.
</p>
<p>If you are unsure, place a note in the txet <tt>[**typo for text?]</tt> and ask in the
   Project Discussion thread. If you do make a change, include a note describing what you changed:
   <tt>[*Transcriber's Note: typo fixed, changed from "txet" to "text"]</tt>.
   Include an <tt>*</tt> so the post-processor will notice it.
</p>

<h3><a name="f_errors">Factual Errors in Texts</a></h3>
<p>In general, don't correct factual errors in the author's book. Many of the books we
   are proofreading have statements of fact in them that we no longer accept as accurate.
   Leave them as the author wrote them.
</p>
<p>A possible exception is in technical or scientific books, where a known formula or
   equation may be given incorrectly, especially if it is shown correctly on other
   pages of the book. Notify the Project Manager about these, either by sending them a
   message via the <a href="#forums">Forum</a>, or by inserting <tt>[**note sic
   explain-your-concern]</tt> at that point in the text.
</p>

<h3><a name="uncertain">Uncertain Items</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...to be completed...]
</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Return to:
     <a href="..">Distributed Proofreaders home page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="faq_central.php">DP FAQ Central page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="<? echo $PG_home_url; ?>">Project Gutenberg home page</a>.
     </font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>

