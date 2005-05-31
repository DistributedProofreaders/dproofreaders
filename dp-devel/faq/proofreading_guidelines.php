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

     There are now HTML comments interspersed in this document that are/will be
     used by a script that automagically slices out the Random Rule text for the
     database. It does this by copying:
	1) All text from one h_3 to the next h_3
		-OR-
	2) All text from h_3 to the END_RR comment line.

    This allows us to have "extra" information in the Guidelines, but leave it out
    in the Random Rule for purposes of clarity/brevity.

    If you are updating this document, the above should be kept in mind.
-->

<h1 align="center">Proofreading Guidelines</h1>

<h3 align="center">Version 1.8.c, generated May 30, 2005 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<h4>Proofreading Guidelines <a href="guidelines_francaises.html">in French</a> /
    Directives de Formatage <a href="guidelines_francaises.html">en fran&ccedil;ais</a></h4>

<h4>Check out the <a href="quiz/start.php">Proofreading Quiz and Tutorial</a></h4>

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
        <li><font size="+1">Proofreading of the...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#line_br">Line Breaks</a></li>
        <li><a href="#double_q">Double Quotes</a></li>
        <li><a href="#single_q">Single Quotes</a></li>
        <li><a href="#quote_ea">Quote Marks on each line</a></li>
        <li><a href="#period_s">End of Sentence Periods</a></li>
        <li><a href="#punctuat">Punctuation</a></li>
        <li><a href="#contract">Contractions</a></li>
        <li><a href="#extra_sp">Extra spaces or tabs between Words</a></li>
        <li><a href="#trail_s">Trailing Space at End-of-line</a></li>
        <li><a href="#line_no">Line Numbers</a></li>
        <li><a href="#italics">Italic, Bold and Small Capitals Text</a></li>
        <li><a href="#supers">Superscripts</a></li>
        <li><a href="#subscr">Subscripts</a></li>
        <li><a href="#font_sz">Font Size Changes</a></li>
        <li><a href="#drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></li>
        <li><a href="#a_chars">Accented/Non-ASCII Characters</a></li>
        <li><a href="#d_chars">Characters with Diacritical marks</a></li>
        <li><a href="#f_chars">Non-Latin Characters</a></li>
        <li><a href="#fract_s">Fractions</a></li>
        <li><a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a></li>
        <li><a href="#eol_hyphen">End-of-line Hyphenation</a></li>
        <li><a href="#initials">Initials</a></li>
        <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
        <li><a href="#mult_col">Multiple Columns</a></li>
        <li><a href="#blank_pg">Blank Page</a></li>
        <li><a href="#page_hf">Page Headers/Page Footers</a></li>
        <li><a href="#chap_head">Chapter Headers</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#footnotes">Footnotes/Endnotes</a></li>
        <li><a href="#poetry">Poetry/Epigrams</a></li>
        <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#title_pg">Front/Back Title Page</a></li>
        <li><a href="#toc">Table of Contents</a></li>
        <li><a href="#bk_index">Indexes</a></li>
        <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a></li>

        <li><a href="#quote_ea">Quote Marks on each line</a></li>

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
   the intent of the author. If the author spelled words oddly, we leave them spelled that way. If the author
   wrote outrageous racist or biased statements, we leave them that way. If the author puts italics, bold text
   or a footnote every third word, we mark them italicized, bolded or footnoted. We are proofreaders, <b>not</b> editors.
</p>
<p>We do change minor typographical conventions that don't affect the sense of what the author wrote.
   For example, we rejoin words that were broken at the end of a line (<a href="#eol_hyphen">End-of-line Hyphenation</a>).
   Changes such as these help us produce a <em>consistently formatted</em> version of the book.
   The proofreading rules we follow are designed to achieve this result. Please carefully read the rest of the
   Proofreading Guidelines with this concept in mind. There is a separate set of Formatting Guidelines. These guidelines are
intended for proofreading <i>only</i>. A second group of volunteers will be working on the formatting of the text.
</p>
<p>To assist the next proofreader, the formatter, and the post-processor, we also preserve <a href="#line_br">line breaks</a>.
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



<h3><a name="about">About This Document</a></h3>
<p>This document is written to explain the proofreading rules we user to maintain consistency when proofreading
   a single book that is distributed among many proofreaders, each of whom is working on different pages.
   This helps us all do proofreading <em>the same way</em>, which in turn makes it
   easier for the formatter and for the post-processor who will complete the work on this e-book.
</p>
<p><i>It is not intended as any kind of a general editorial or typesetting rulebook</i>.
</p>
<p>We've included in this document all the items that new users have asked about
    while proofreading. If there are any items missing, or items that you
   consider should be done differently, or if something is vague, please let us know.
</p>
<p>This document is a work in progress. Help us to progress by posting your suggested changes in the
   Documentation Forum in <a href="<? echo $Guideline_discussion_URL; ?>">this thread</a>.
</p>

<h3><a name="comments">Project Comments</a></h3>

<p>On the proofreading interface page (Project Page) where you start proofreading pages, there is a section called
   "Project Comments" containing information specific to that project (book). <b>Read these
   before you start proofreading pages!</b> If the Project Manager wants you to do
   something in this book differently from the way specified in these Guidelines, that
   will be noted here. Instructions in the Project Comments <em>override</em> the rules
   in these Guidelines, so follow them. There may also be instructions in the project comments that apply to the formatting phase, which do not apply during proofing. Finally, this is also where the Project Manager may give
   you interesting tidbits of information about the author or the project.
</p>
<p><em>Please also read the Project Thread(Forum)</em>: The Project Manager may clarify project-specific
   guidelines here, and it is often used by proofreaders to alert other proofreaders to recurring
   issues within the project and how they can best be addressed.  (See below).
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
      <td bgcolor="silver"><font size="+2">How to proof...</font></td>
    </tr>
  </tbody>
</table>



<h3><a name="line_br">Line Breaks</a></h3>
<p><b>Leave all line breaks in</b> so that later in the process other volunteers can easily compare
   the lines in the text to the lines in the image. If the previous proofreader removed the line breaks,
   please replace them so that they once again match the image.
</p>


<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="double_q">Double Quotes</a></h3>
<p>Proofread these as plain ASCII <tt>"</tt> double quotes. Do not change
   double quotes to single quotes. Leave them as the Author wrote them.
</p>
<p>For quotes from non-English languages, use the quotation marks appropriate
   to that language if they are available in the Latin-1 character set. The
   French equivalent, guillemets, <tt>&laquo;like this&raquo;</tt>, are available
   from the pulldown menus in the proofreading interface, since they are part of
   Latin-1. The quotation marks used in some German texts, <tt>&bdquo;like this&rdquo;</tt>
   are not available in the pulldown menus, as they are not in Latin-1.
   The Project Manager may instruct you in the <a href="#comments">Project Comments</a>
   to proofread non-English language quotation marks differently for a particular book.
</p>

<h3><a name="single_q">Single Quotes</a></h3>
<p>Proofread these as the plain ASCII <tt>'</tt> single quote (apostrophe). Do not
   change single quotes to double quotes. Leave them as the Author wrote them.
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

<h3><a name="period_s">End of Sentence Periods</a></h3>
<p>Proofread periods that end sentences with a single space after them.
</p>
<p>You do not need to remove extra spaces after periods if they're already in the scanned
   text&mdash;we can do that automatically during post-processing. See the <a href="#para_side">Sidenotes</a>
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


<h3><a name="contract">Contractions</a></h3>
<p>Remove any extra space in contractions: for example, would&nbsp;n't should
   be proofread as <tt>wouldn't</tt>.
</p>
<p>This was often an early printers' convention, where the space was retained
   to indicate that 'would' and 'not' were originally separate words. It is
   also sometimes an artifact of the OCR. Remove the extra space in either case.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   not to remove extra spaces in contractions, particularly in the case of texts that
   contain slang, dialect, or are written in languages other than English.
</p>


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
<p>Keep line numbers. Use a few spaces to separate them from the other text on the line so that
the formatters can easily find them.
</p>
<p>Line numbers are numbers in the margin for each line, or sometimes every fifth or tenth
   line, and are common in books of poetry. Since poetry will not be reformatted in the e-book
   version, the line numbers will be useful to readers.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->


<h3><a name="italics">Italic, Bold and Small Capitals Text</a></h3>
<p><i>Italicized</i> text may occasionally appear with <tt>&lt;i&gt;</tt> inserted at the start and
   <tt>&lt;/i&gt;</tt> inserted at the end of the italics. <b>bold text</b> (text printed in a heavier
   typeface) may occasionally appear with <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt>
   after it. <span style="font-variant: small-caps">Small caps</span> (text printed in all capital letters
   with initial capitals in a larger size) may occasionally appear with <tt>&lt;sc&gt;</tt> inserted
   before the small caps and <tt>&lt;/sc&gt;</tt> after the small caps. Do not remove this formatting information,
   unless it surrounds junk that does not appear on the page. Do not add it where it
   does not appear. The formatters will do that later in the process.
</p>
<!-- END RR -->


<h3><a name="supers">Superscripts</a></h3>
<p>Older books often abbreviated words as contractions, and printed them as
   superscripts: for example,<br>
   &nbsp;&nbsp;&nbsp;&nbsp; Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Proofread these by inserting an up-arrow followed by the superscripted text, like this:<br>
   &nbsp;&nbsp;&nbsp;&nbsp; <tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>


<h3><a name="subscr">Subscripts</a></h3>
<p>Subscripted text is often found in scientific works, but is not common in other
   material. Proofread subscripted text by inserting an underline character <tt>_</tt>.
   <br>For example:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; H<sub>2</sub>O.
   <br>would be proofread as
   <br>&nbsp; &nbsp; &nbsp; &nbsp; <tt>H_2O.<br></tt>
</p>


<h3><a name="font_sz">Font size changes</a></h3>
<p>Do not mark changes in font size. The formatters will take care of this later in the process.
</p>


<h3><a name="drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></h3>
<p>Proofread large and ornate graphic first letters of a chapter, section, or paragraph
   as just the letter.
</p>


<h3><a name="a_chars">Accented/Non-ASCII Characters</a></h3>
<p>Please proofread these using the proper accented Latin-1 characters, where possible. See
   <a href="#d_chars">Diacritical marks</a> for ways to proof some non-Latin-1 characters.
</p>
<p>There are several ways of inputting accented characters:</p>
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
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Capital O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>Use [OE]</td>
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
      menu (the drop-down menu attached to your locale's flag icon in the menu bar). It's labeled
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
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Opt-~, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Opt-O   </td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>Use [OE]</td>
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


<h3><a name="d_chars">Characters with Diacritical marks</a></h3>
<p>In some projects, you will find characters with special marks either above or below
   the normal Latin A..Z character. These are called <i>diacritical marks</i> and
   indicate a special pronunciation for this character.
   For proofreading, we indicate them in our normal ASCII text by using a
   specific coding, such as: ă becomes <tt>[)a]</tt> for a breve (the u-shaped accent)
   above an a, or <tt>[a)]</tt> for a breve below.
</p>
<p>Be sure to include the square brackets (<tt>[&nbsp;]</tt>) around these, so the post-processor
   knows to which letter it applies. He or she will eventually replace these with
   whatever symbol works in each version of the text they produce, like 7-bit ASCII,
   8-bit, Unicode, html, etc.
</p>
<p>Note that when some of these marks appear on some characters (mainly vowels) our standard
   Latin-1 character set already includes that character with the diacritical mark. <b>In those
   cases, use the Latin-1 character (see <a href="#a_chars">here</a>), available from the
   drop-down lists in the proofreading interface.</b>
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
<p>Some projects contain text printed in non-Latin characters--that is, characters other
than the Latin A...Z--for example, Greek, Cyrillic, Hebrew, or Arabic.
</p><? if(strcasecmp($charset,"UTF-8")) { ?>
<p>For Greek, you should attempt a transliteration. Transliteration involves converting
   each character of the foreign text into the equivalent ASCII Latin letter(s). A Greek
   transliteration tool is provided in the proofing interface to make this task much easier.
</p>
<p>Press the "Greek" button near the bottom of the proofreading interface to pop up the tool.
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
<p>For other languages that cannot be so easily transliterated, such as Cyrillic,
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
      you attempt a transliteration if you are fluent in the languages that use it. Otherwise, just mark
      it as indicated above. You may find this
      <a href="http://learningrussian.com/transliteration.htm">Transliteration Table</a> useful.
  </li>
  <li>Hebrew and Arabic:
      Not recommended unless you are fluent. There are significant
      difficulties transliterating these languages and neither <a href="..">Distributed
      Proofreaders</a> nor <a href="<? echo $PG_home_url; ?>">Project Gutenberg</a>
      has yet chosen a standard method.
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
<p>If the script appears only occasionally, you may use a separate program to enter it.
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
        &mdash;if even all of these are simple tastes. What</td>
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
   two halves leaving the hyphen in between. But if it was just hyphenated because it
   wouldn't fit on the line, and is not a word that is usually hyphenated, then join
   the two halves and remove the hyphen. Keep the joined word on the top line, and put
   a line break after it to preserve the line formatting&mdash;this makes it easier for
   volunteers in later rounds. See the <a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a> section
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


<h3><a name="initials">Initials</a></h3>
<p>Remove all spaces in names printed as initials, even if it appears that the typesetter included
   spaces (or partial spaces) in the printed version. For example, proofread H.&nbsp;M.&nbsp;S.&nbsp;Pinafore as <tt>H.M.S.
   Pinafore</tt>, proofread G.&nbsp;B.&nbsp;Shaw as <tt>G.B. Shaw</tt>. This avoids the potential problem
   of the letters being broken across lines when text is rewrapped.
</p>
<!-- END RR -->


<h3><a name="para_space">Paragraph Spacing/Indenting</a></h3>
<p>Put a blank line to separate paragraphs.
   You should not indent the start of paragraphs, but if all paragraphs are already indented, don't
   bother removing those spaces&mdash;that can be done automatically during post-processing.
</p>
<p>See the <a href="#para_side">Sidenotes</a> image/text for an example.
</p>

<h3><a name="mult_col">Multiple Columns</a></h3>
<p>Proofread ordinary text that has been printed in two columns as a single column.
</p>
<p>Spans of multiple-column text within single column sections should be proofread as a single column
   by placing the text from the left-most column first, the text from the next one after it, and so on.
   You do not need to mark where the columns were split, just re-join them.
</p>
<p>See also the <a href="#bk_index">Index</a> and
   <a href="#tables">Table</a> sections of the Proofreading Guidelines.
</p>


<h3><a name="blank_pg">Blank Page</a></h3>
<p>Most blank pages, or pages with an illustration but no text, will already be
   marked with <tt>[Blank Page]</tt>. Leave this marking as is. If the page is
   blank, and [Blank Page] does not appear, there is no need to add it.
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
    <tt>In the United States?[*] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads public and &lt;i&gt;private&lt;/i&gt;.<br>
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
    would the use of the land go?<br>
    <br>
    <br>
    CHAPTER XXXV.<br>
    <br>
    &lt;sc&gt;Commercial Paper&lt;/sc&gt;.<br>
    <br>
    Kinds and Uses.&mdash;If a man wishes to buy some commodity<br>
    from another but has not the money to pay for<br>
    it, he may secure what he wants by giving his written<br>
    promise to pay at some future time. This written<br>
    promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers to an oral promise<br>
    for several reasons, only two of which need be mentioned<br>
    here: first, because it is prima facie evidence of<br>
    the debt; and, second, because it may be more easily<br>
    transferred or handed over to some one else.<br>
    <br>
    If J.M. Johnson, of Saint Paul, owes C.M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J.M. Johnson a hundred dollars, it is<br>
    plain that the risk, expense, time and trouble of sending<br>
    the money to and from Chicago may be avoided,<br>
    <br>
    * The United States: "Its charter, the constitution. * * * Its flag the<br>
    symbol of its power; its seal, of its authority."--Dole.
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
<p>Watch out for a missing double quote at the start of the first paragraph, which some
   publishers did not include or which the OCR missed due to a large capital in the
   original. If the author started the paragraph with dialog, insert the double quote.
</p>
<!-- END RR -->



<h3><a name="illust">Illustrations</a></h3>
<p>Proofread any caption text as it is printed, preserving
   the line breaks. If the caption falls in the middle of a paragraph, use blank lines to set it apart from
   the rest of the text. If there is <b>no</b> caption in the original text, then the mark-up of the illustration is left to the formatters.
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
      <p><tt>Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>
      &lt;i&gt;Frontispiece<br>
      Her Weight in Gold&lt;/i&gt;
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
     <p><tt>FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
     SEISMIC MOVEMENTS.</tt></p>
     <p><tt>
      occasion we shall make known a few others that will<br>
     serve to give an idea of the methods employed.<br>
     </tt></p>
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
<p>

The number, letter, or other character that marks a footnote location should be surrounded with square brackets ([ and ]) and
   placed right next to the word being footnoted<tt>[1]</tt> or its
   punctuation mark,<tt>[2]</tt> as shown in the text and the two examples in this sentence.
</p>
<p>When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;,
   etc.) we replace them all with *.
</p>
<p>Proofread the footnote text as it is printed, preserving the line breaks, italics, etc.
   Leave the footnote text at the bottom of the page.  Be sure to use the same tag in the footnote as
   you used in the text where the footnote was referenced.
</p>
<!-- END RR -->

<p>See the <a href="#page_hf">Page Headers/Page Footers</a> image/text for an sample footnote.
</p>
<p>If a footnote or endnote is referenced in the text but does not appear on that page,
   keep the footnote/endnote number or marker and don't be concerned. This is common in scientific and technical books,
   where footnotes are often grouped at the end of chapters. See "Endnotes" below.
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
      <th valign="top" align="left" bgcolor="cornsilk">Correctly Proofed Text:</th>
    </tr>
      <tr valign="top">
      <td>
<table summary="" border="0" align="left"><tr><td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>1 Gaius Julius Caesar.</tt><br>
    <br>
    <tt>2 Marcus Tullius Cicero.</tt>
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
   are proofread in the same manner as footnotes. Where you find an
   endnote reference in the text, retain the number or letter.
   If you are proofreading one of the ending pages with the endnotes text on it,
   put a blank line after each endnote so that it is clear where each begins and ends.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Footnotes in <a href="#poetry">Poetry</a></b>
   should be treated the same as other footnotes and left at the bottom of the page.<br /> <br />

<b>Footnotes in <a href="#tables">Tables</a></b> should remain where they are in the original text.
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
    <tt>
    Mary had a little lamb[1]<br>
    Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    The lamb was sure to go!<br>
    <br>
    1 This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.<br>
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="poetry">Poetry/Epigrams</a></h3>
<p>Insert a blank line at the start of the poetry or epigram and another blank line at the end, so that the formatters can clearly see the beginning and end.
</p>
<p>Leave each line left-justified and maintain the line breaks. Do not try to center or indent the poetry. The formatters will do that part. Do insert a blank line between stanzas.
</p>
<p><b>Footnotes</b> in poetry should be treated the same as regular footnotes during proofreading.
  See <a href="#footnotes">footnotes</a> for details.
</p>
<p><b>Line Numbers</b> in poetry should be kept. Separate them from the main text with a few spaces. See instructions on <a href="#line_no">Line Numbers</a>.

</p>
<p>Check the <a href="#comments">Project Comments</a> for the specific text you are proofreading.
</p>
<!-- END RR -->

<br>
<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

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
Oh, to be in England<br>
Now that April's there,<br>
And whoever wakes in England<br>
Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree hole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
In England--now!</tt>
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
</tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>
</td></tr></table>

     </td>
   </tr>
 </tbody>
</table>

<h3><a name="para_side">Paragraph Side-Descriptions (Sidenotes)</a></h3>
<p>Some books will have short descriptions of the paragraph along the side of the text.
   These are called sidenotes.
    Proofread the sidenote text as it is printed,
   preserving the line breaks. Leave a blank line before and after the sidenote, so that it
   can be distinguished from the text around it. The OCR may place the sidenotes anywhere on the page, and may even intermingle the sidenote text with the rest of the text.. Separate them so that the sidenote text is all together, but don't worry about the position of the sidenotes on the page. The formatters will move them to the correct locations.

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
    Burning<br>
    discs<br>
    thrown into<br>
    the air.<br>
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
    The Midsummer<br>
    fires in<br>
    Swabia.<br>
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
    Omens<br>
    drawn from<br>
    the leaps<br>
    over the<br>
    fires.<br>
    <br>
    Burning<br>
    wheels<br>
    rolled<br>
    down hill.<br>
    <br>
    1 Op. cit. iv. 1. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>
    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J.W. Wolf, Beiträge zur deutschen<br>
    Mythologie, i. p. 217, § 185).<br>
    <br>
    2 J. Boemus, &lt;i&gt;Mores, leges et ritus<br>
    omnium gentium&lt;/i&gt; (Lyons, 1541), p.<br>
    226.<br>
    <br>
    3 Karl Freiherr von Leoprechting,<br>
    Aus dem Lechrain (Munich, 1855),<br>
    pp. 181 &lt;i&gt;sqq.;&lt;/i&gt; W. Mannhardt, &lt;i&gt;Der<br>
    Baumkultus&lt;/i&gt;, p. 510.<br>
    <br>
    4 A. Birlinger, &lt;i&gt;Volksthümliches aus<br>
    Schwaben&lt;/i&gt; (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 sqq., § 128, pp. 103<br>
    sq., § 129; &lt;i&gt;id., Aus Schwaben&lt;/i&gt; (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    &lt;i&gt;Deutsche Sagen, Sitten und Gebräuche<br>
    aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<br>
    423 sqq.; W. Mannhardt, Der Baumkultus,<br>
    p. 510.<br>
    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="tables">Tables</a></h3>
<p>
   A proofreader's job is to be sure that all the information in a table is correctly proofed. Details of formatting
   will be handled later in the process. Provide enough space between entries on a line to clearly indicate where
   each item ends and begins. Retain line breaks.
</p>
<p><b>Footnotes</b> in tables should remain where it is in the original. See <a href="#footnotes">footnotes</a> for details.
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
<pre>
Deg. C.  Millimeters of Mercury. Gasolene.
Pure Benzene.

-10&deg;  13.4  43.5
 0&deg;  26.6  81.0
+10&deg;  46.6  132.0
20&deg;  76.3  203.0
40&deg;  182.0  301.8
</pre>
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
<pre>
TABLE II.

Flat strips compared   Copper   Copper
with round wire 30 cm.  Iron. Parallel wires 30 cm. in  Iron.
in length.             length.

Wire 1 mm. diameter   20  100  Wire 1 mm. diameter   20 100

        STRIPS.      SINGLE WIRE.
0.25 mm. thick, 2 mm.
  wide  15  35  0.25 mm. diameter   16   48
Same, 5 mm. wide       13  20  Two similar wires    12  30
 "   10  "    "   11   15  Four    "    "     9   18
 "   20  "    "    10  14  Eight  "    "   8   10
 "   40  "    "    9   13  Sixteen "    "     7    6
Same strip rolled up in  Same 16 wires bound
  the form of a wire  17   15    close together   18    12
</pre>
</td></tr></table>
      </td>
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
   upper and lower case, etc. Page numbers should be
   retained.
</p>
<p>Ignore any periods or asterisks (leaders) used to align the page numbers. These will be removed later in the process.
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
      <p><tt><br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>
          I. THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
          MEET AND PART ON THE HIGHWAY&nbsp;&nbsp;.....&nbsp;1<br>
          <br>
          II. THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>
          FALLS IN WITH FRIENDS&nbsp;&nbsp;....&nbsp;...&nbsp;15<br>
          <br>
          III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>
          AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>
          <br>
          IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>
          TRAGEDY, AND A MAN WHO SAID "THANK YOU"&nbsp;&nbsp;&nbsp;50<br>
          <br>
          V. THE FARM-BOY TELLS A GHASTLY STORY, AND AN<br>
          IRISHMAN ENTERS&nbsp;&nbsp;..&nbsp;&nbsp;..&nbsp;67<br>
          <br>
          VI. CHARITY BEGINS FAR FROM HOME, AND A STROLL IN<br>
          THE WILDWOOD FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
          <br>
          VII. SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS ENCOUNTERS&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. A NOTE, SOME FANCIES, AND AN EXPEDITION IN<br>
          QUEST OF FACTS&nbsp;&nbsp;..&nbsp;,,&nbsp;120<br>
          <br>
          IX. THE FIRST WAYFARER, THE SECOND WAYFARER, AND<br>
          THE SPIRIT OF CHIVALRY ASCENDANT&nbsp;&nbsp;&nbsp;,&nbsp;&nbsp;134<br>
          <br>
          X. THE PRISONER OF GREEN FANCY, AND THE LAMENT OF<br>
          PETER THE CHAUFFEUR&nbsp;...&nbsp;&nbsp;&nbsp;....148<br>
          <br>
          XI. MR. SPROUSE ABANDONS LITERATURE AT AN EARLY<br>
          HOUR IN THE MORNING&nbsp;..&nbsp;&nbsp;...&nbsp;&nbsp;,&nbsp;167<br>
          <br>
          XII. THE FIRST WAYFARER ACCEPTS AN INVITATION, AND<br>
          MR. DILLINGFORD BELABORS A PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. THE SECOND WAYFARER RECEIVES TWO VISITORS AT<br>
          MIDNIGHT&nbsp;,,,..&nbsp;&nbsp;....&nbsp;199<br>
          <br>
          XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>
          OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221<br>
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>



<h3><a name="bk_index">Indexes</a></h3>
<p>Please retain page numbers in index pages. You don't need to align the numbers
   as they appear in the scan; just make sure that the numbers and punctuation match the scan and retain the line breaks.
</p>
<p>
   Specific formatting of indexes will occur later in the process. The proofreader's job is to be sure that all
   the text and numbers are correct.
</p>
<!-- END RR -->


<h3><a name="play_n">Plays: Actor Names/Stage Directions</a></h3>
<p>For all plays:</p>
<ul compact>
 <li>In dialogue, treat a change in speaker as a new paragraph, with one blank line between.</li>
<li>Stage directions are formatted as they are in the original text.<br>
    If the stage direction is on a line by itself, proofread it that way; if it is at the end of a line
   of dialogue, leave it there.<br>
   Stage directions often begin with an opening bracket and omit the closing bracket.
   This convention is retained; do not close the brackets.</li>
 <li>Sometimes, especially in metrical plays, a word is split due to page-size constraints and placed
   above or below following a (, rather than having a line of its own. Please treat this as a normal end-of-line reattachment.<br>
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
<p><tt>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
Lap. These marks are ugly.
</tt></p><p><tt>
Clow. He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
Lap. But why a Peel-crow here?
</tt></p><p><tt>
Clow. I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
Lap. How slave? look you, Sir,<br>
Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this Bob,<br>
Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
</tt></p><p><tt>
Clow. So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
And he has made 'em Welch Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
Lap. Hay-day; a &lt;i&gt;Souse, Italica&lt;/i&gt;?
</tt></p><p><tt>
Clow. Yes, that may hold, Sir,<br>
Souse is a &lt;i&gt;bona roba&lt;/i&gt;, so is Flops too.</tt></p>
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
<p><tt>
Am. Sure you are fasting;<br>
Or not slept well to night; some dream (Ismena?)<br>
<br>
Ism. My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.</tt></p>
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
   the problem. Your note will explain to the next proofreader, formatter or post-processor what the problem or
   question is.
</p>
<p>Start your note with a square bracket and two asterisks <tt>[**</tt> and end it with another square bracket <tt>]</tt>.
   This clearly separates it from the Author's text and signals the next volunteer to stop and carefully examine
   this part of the text &amp; the matching image to address any issues.
</p>
<p>If you are proofreading in a later round and come across a note from a proofreader in a previous round,
   once you have resolved the issue, please take a moment and provide Feedback to them by clicking on their
   name in the proofreading interface and posting a private message to them explaining how to handle the
   situation in the future.
</p>
<!-- END RR -->


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


<h3><a name="OCR_scanno">OCR Problems: Scannos</a></h3>
<p>Another common OCR issue is misrecognition of characters. We call these errors "scannos" (like "typos").
   This misrecognition can create errors in the text:</p>
<ul compact>
   <li>A word that appears to be correct at first glance, but is actually misspelled. <br />
This can usually be caught by running the spellcheck from the proofreading interface.</li>
   <li>A word that is changed to a different but otherwise valid word that does not match what is in the page image.<br />
These are subtle because they can only be caught by someone actually reading the text."</li>
</ul>
<p>Possibly the most common example of the second type is "and" being OCR'd as "arid." Other examples: "eve" for "eye",
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
<p>Correct all of the words that the OCR has misread (scannos), but do not correct
   what may appear to you to be misspellings or printer errors that occur on the scanned
   image. Many of the older texts have words spelled differently from modern usage and
   we retain these older spellings, including any accented characters.
</p>
<p>If you are unsure, place a note in the txet <tt>[**typo for text?]</tt> and ask in the
   Project Discussion thread. If you do make a change, include a note describing what you changed:
   <tt>[**Transcriber's Note: typo fixed, changed from "txet" to "text"]</tt>.
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



