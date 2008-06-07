<?
$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Formatting Guidelines','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
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

<h1 align="center">Formatting Guidelines</h1>

<h3 align="center">Version 1.9.e, revised July 19, 2007 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<h4>Formatting Guidelines <a href="formatting_guidelines_francaises.php">in French</a> /
      Directives de Formatage <a href="formatting_guidelines_francaises.php">en fran&ccedil;ais</a><br />
    Formatting Guidelines <a href="formatting_guidelines_portuguese.php">in Portuguese</a> /
      Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_portuguese.php">em Portugu&ecirc;s</a><br />
    Formatting Guidelines <a href="formatting_guidelines_dutch.php">in Dutch</a> /
      Formatteer-Richtlijnen <a href="formatting_guidelines_dutch.php">in het Nederlands</a><br />
    Formatting Guidelines <a href="formatting_guidelines_german.php">in German</a> /
      Formatierungsrichtlinien <a href="formatting_guidelines_german.php">auf Deutsch</a><br />
</h4>

<h4>Check out the <a href="../quiz/start.php?show_only=FQ">Formatting Quiz</a>!</h4>

<table border="0" cellspacing="0" width="100%" summary="Formatting Guidelines">
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
        <li><a href="#italics">Italics</a></li>
        <li><a href="#bold">Bold Text</a></li>
        <li><a href="#underl">Underlined Text</a></li>
        <li><a href="#spaced">S p a c e d &nbsp; O u t &nbsp; Text (gesperrt)</a></li>
        <li><a href="#font_ch">Font Changes</a></li>
        <li><a href="#small_caps">Words in <span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#word_caps">Words in all Capitals</a></li>
        <li><a href="#font_sz">Font size changes</a></li>
        <li><a href="#extra_sp">Extra spaces or tabs between Words</a></li>
        <li><a href="#supers">Superscripts</a></li>
        <li><a href="#subscr">Subscripts</a></li>
        <li><a href="#page_ref">Page References "(See Pg. 123)"</a></li>
        <li><a href="#line_br">Line Breaks</a></li>
        <li><a href="#chap_head">Chapter Headers</a></li>
        <li><a href="#sect_head">Section Headers</a></li>
        <li><a href="#maj_div">Other Major Divisions in Texts</a></li>
        <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
        <li><a href="#extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#footnotes">Footnotes/Endnotes</a></li>
        <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
        <li><a href="#block_qt">Block Quotations</a></li>
        <li><a href="#mult_col">Multiple Columns</a></li>
        <li><a href="#lists">Lists of Items</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#poetry">Poetry/Epigrams</a></li>
        <li><a href="#line_no">Line Numbers</a></li>
        <li><a href="#letter">Letters/Correspondence</a></li>
        <li><a href="#blank_pg">Blank Page</a></li>
        <li><a href="#title_pg">Front/Back Title Page</a></li>
        <li><a href="#toc">Table of Contents</a></li>
        <li><a href="#bk_index">Indexes</a></li>
        <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a></li>
        <li><a href="#prev_notes">Previous Proofreaders' Notes</a></li>
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
        <li><a href="#bad_image">Bad Image</a></li>
        <li><a href="#bad_text">Wrong Image for Text</a></li>
        <li><a href="#round1">Previous Proofreading or Formatting Mistakes</a></li>
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
   wrote outrageous racist or biased statements, we leave them that way. If the author put italics, bold text,
   or a footnote every third word, we mark them italicized, bolded, or footnoted. (See <a href="#p_errors">Printer's
   Errors</a> for proper handling of obvious misprints.)
</p>
<p>We do change minor typographical conventions that don't affect the sense of what the author wrote.
   For example, we rejoin words that were broken at the end of a line (<a href="#eol_hyphen">End-of-line Hyphenation</a>).
   Changes such as these help us produce a <em>consistently formatted</em> version of the book.
   The rules we follow are designed to achieve this result. Please carefully read the rest of
   these Guidelines with this concept in mind.
</p>
<p>To assist the next formatter and the post-processor, we also preserve <a href="#line_br">line breaks</a>.
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
<p>The <a href="formatting_summary.pdf">Formatting Summary</a> is a short, 2-page
   printer-friendly (.pdf) document that summarizes the main points of these
   Guidelines, and gives examples of how to format. Beginning formatters are
   encouraged to print out this document and keep it handy while formatting.
</p>
<p>You may need to download and install a .pdf reader. You can get one free from Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">here</a>.
</p>

<h3><a name="about">About This Document</a></h3>
<p>This document is written to explain the formatting rules we use to maintain consistency when formatting
   a single book that is distributed among many formatters, each of whom is working on different pages.
   This helps us all do formatting <em>the same way</em>, which in turn makes it
   easier for the post-processor to eventually combine all these pages into one e-book.
</p>
<p><i>It is not intended as any kind of a general editorial or typesetting rulebook</i>.
</p>
<p>We've included in this document all the items that new users have asked about
   formatting and proofreading. If there are any items missing, or items that you
   consider should be done differently, or if something is vague, please let us know.
</p>
<p>This document is a work in progress. Help us to progress by posting your suggested changes in the
   Documentation Forum in <a href="<? echo $Guideline_discussion_URL; ?>">this thread</a>.
</p>

<h3><a name="comments">Project Comments</a></h3>

<p>On the Project Page where you start formatting pages, there is a section called
   "Project Comments" containing information specific to that project (book). <b>Read these
   before you start formatting pages!</b> If the Project Manager wants you to format
   something in this book differently from the way specified in these Guidelines, that
   will be noted here. Instructions in the Project Comments <em>override</em> the rules
   in these Guidelines, so follow them. (This is also where the Project Manager may give
   you interesting tidbits of information about the author or the project.)
</p>
<p><em>Please also read the Project Thread (discussion)</em>: The Project Manager may clarify project-specific
   guidelines here, and it is often used by volunteers to alert other volunteers to recurring
   issues within the project and how they can best be addressed. (See below).
</p>
<p>On the Project Page, the link 'Images, Pages Proofread, &amp; Differences' allows you to
   see how other volunteers have made changes.
   <a href="<? echo $Using_project_details_URL ?>">This Forum thread</a>
   discusses different ways to use this information.
</p>

<h3><a name="forums">Forum/Discuss this Project</a></h3>
<p>On the Project Page where you start formatting pages, on the line "Forum", there is
   a link titled "Discuss this Project" (if the discussion has already started), or "Start
   a discussion on this Project" (if it hasn't). Clicking on that link will take you to a
   thread in the projects forum dedicated to this specific project. That is the place to ask
   questions about this book, inform the Project Manager about problems, etc. Using this project
   forum thread is the recommended way to communicate with the Project Manager and other
   volunteers who are working on this book.
</p>

<h3><a name="prev_pg">Fixing errors on Previous Pages</a></h3>
<p>When you select a project for formatting, the <a href="#comments">Project Page</a>
   page is loaded. This page contains links to pages from this project that you have
   recently worked on. (If you haven't formatted any pages yet, there will be no links
   shown.)
</p>
<p>Pages listed under either "DONE" or "IN PROGRESS" are available to make
   corrections or to finish formatting. Just click on the link to the page. So if you
   discover that you made a mistake on a page, or marked something incorrectly, you can
   click on that page here and reopen it to fix the error.
</p>
<p>You may also use the "Images, Pages Proofread, &amp; Differences" or "Just My Pages" links
   on the <a href="#comments">Project Page</a>. These pages will display an "Edit"
   link next to the pages you have worked on in the current round that can still be corrected.
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


<h3><a name="italics">Italics</a></h3>
<p>Format <i>italicized</i> text with <tt>&lt;i&gt;</tt> inserted at the start and
   <tt>&lt;/i&gt;</tt> inserted at the end of the italics. (Note the "/" in the closing
   tag.)
</p>
<p>Punctuation goes <b>outside</b> the italics, unless it is an entire sentence or section
   that is italicized, or the punctuation is itself part of a phrase, title,
   or abbreviation that is italicized.
</p>
<p>The periods that mark an abbreviated word in the title of a journal such as <i>Phil. Trans.</i>
   are part of the title for italicization purposes, and are included within the italic tags, thus:
   <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>.
</p>
<p>For dates and similar phrases, format the entire phrase as
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
      <th valign="top" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br />
                     1323 Picadilly Circus</td>
      <td valign="top"><tt>/*<br />
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br />
         1323 Picadilly Circus<br />
         */</tt></td>
    </tr>
    <tr>
    <tr>
      <td valign="top">
      Volunteers may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <tt>Volunteers may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bold">Bold Text</a></h3>
<p>Format <b>bold text</b> (text printed in a heavier typeface) with
   <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt> after it. (Note the "/" in the closing
   tag.)
</p>
<p>Punctuation goes <b>outside</b> the bold tags, unless it is an entire sentence or section
   that is in bold, or the punctuation is itself part of a phrase, title,
   or abbreviation that is in bold type.
</p>
<p>See the <a href="#page_hf">Page Headers/Page Footers</a> image/text for an example.
</p>
<p>Some Project Managers may specify in the
   <a href="#comments">Project Comments</a> that bold text be rendered as all caps.
</p>

<h3><a name="underl">Underlined Text</a></h3>
<p>Format <u>underlined text</u> as <a href="#italics">Italics</a>, with <tt>&lt;i&gt;</tt> and
   <tt>&lt;/i&gt;</tt>. (Note the "/" in the closing tag.)
</p>
<p>Underlining was often used to indicate emphasis when the typesetter was unable to actually
   italicize the text, for example in a typewritten document.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   that underlined text be marked up with the <tt>&lt;u&gt;</tt> and <tt>&lt;/u&gt;</tt> tags.
</p>

<h3><a name="spaced">S p a c e d &nbsp; O u t &nbsp; Text (gesperrt)</a></h3>
<p>Format &nbsp; s p a c e d &nbsp; o u t &nbsp; text with <tt>&lt;g&gt;</tt> inserted
   before the text and <tt>&lt;/g&gt;</tt> after it. (Note the "/" in the closing
   tag.) Remove the extra spaces between letters in each word.
</p>
<p>Punctuation goes <b>outside</b> the tags, unless it is an entire sentence or section
   that is spaced out, or the punctuation is itself part of a phrase that is spaced out.
</p>
<p>This was a typesetting technique used to emphasize a piece of text in some older books,
   especially in German.
</p>

<h3><a name="font_ch">Font Changes</a></h3>
<p>Format a change of font within a paragraph or line of normal text by inserting <tt>&lt;f&gt;</tt> before
   the change in font and <tt>&lt;/f&gt;</tt> after it. (Note the "/" in the closing tag.)
   Use this markup to identify any special font or other formatting, <b>except</b> bold,
   italic, small capped, and spaced out text, which have their own tags.
</p>
<p>Possible uses of this markup include:</p>
<ul compact>
  <li>antiqua (a variant of roman font) inside fraktur</li>
  <li>blackletter within a section of regular font</li>
  <li>smaller or larger font only if it is <b>within</b> a paragraph in regular font
    (for a whole paragraph in a different font or size, see the
    <a href="#block_qt">block quotation</a> section)</li>
  <li>upright font inside of a paragraph of italicized text</li>
</ul>
<p>The particular use or uses of this markup in a project will usually be spelled
   out in the <a href="#comments">Project Comments</a>. Formatters should post in the
   <a href="#forums">Project Discussion</a> if the markup appears to be needed
   and has not yet been discussed.
</p>
<p>Punctuation goes <b>outside</b> the tags, unless it is an entire sentence
   that is in a different font, or the punctuation is itself part of a phrase, title,
   or abbreviation in the different font.
</p>

<h3><a name="small_caps">Words in Small Capitals</a></h3>
<p>The markup is different for <span style="font-variant:small-caps;">Mixed Case Small Caps</span>
   and <span style="font-variant: small-caps;">all small caps</span>:
</p>
<p>Format words that are printed in <span style="font-variant: small-caps;">Mixed Small Caps</span>
   as mixed upper and lowercase, and surround the text with <tt>&lt;sc&gt;</tt> and <tt>&lt;/sc&gt;</tt>
   markup. <br>
&nbsp;&nbsp;&nbsp;&nbsp;Example:
   <span style="font-variant: small-caps;">This is Small Caps</span> <br>
&nbsp;&nbsp;&nbsp;&nbsp;would correctly be:
   <tt>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</tt>.
</p>

<p>Format words that are printed in <span style="font-variant: small-caps;">all small caps</span>
   as ALL-CAPS, and surround the text with <tt>&lt;sc&gt;</tt> and <tt>&lt;/sc&gt;</tt> markup.
   <br>
&nbsp;&nbsp;&nbsp;&nbsp;Example:
   You cannot be serious about
   <span style="font-variant: small-caps;">aardvarks</span>!<br>
&nbsp;&nbsp;&nbsp;&nbsp;would correctly be:
   <tt>You cannot be serious about
   &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</tt> <br>
</p>

<p>Words in headings (Chapter Headings, Section Headings, Captions, etc.) that are entirely all-capped
   should be formatted as all-caps without any &lt;sc&gt; &lt;/sc&gt;. The first word of a chapter
   that is in Small Caps should be changed to mixed case without the tags.
</p>

<h3><a name="word_caps">Words in all Capitals</a></h3>
<p>Format words that are printed in all capital letters as all capital letters.
</p>
<p>The exception to this is the <a href="#chap_head">first word of a chapter</a>:
   many old books typeset the first word of these in all caps; this should be changed to upper and
   lower case, so "ONCE upon a time," becomes "<tt>Once upon a time,</tt>"
</p>

<h3><a name="font_sz">Font size changes</a></h3>
<p>Normally we do not do anything to mark changes in font size.
</p>
<p>The exceptions to this are when the font size changes to indicate a
   <a href="#block_qt">block quotation</a>, or when the font size changes within a single
   paragraph or line of text (see <a href="#font_ch">Font Changes</a>).
</p>

<h3><a name="extra_sp">Extra Spaces or Tabs Between Words</a></h3>
<p>Extra spaces and tab characters between words are common in OCR output. You don't need to bother
   removing these&mdash;that can be done automatically during post-processing.
</p>
<p>However, extra spaces around punctuation, em-dashes, quote marks, etc. <b>do</b> need to be
   removed when they separate the symbol from the word.
</p>
<p>For example, in <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</tt> the space between
   the word "horse" and the semicolon should be removed. But the 2 spaces after the semicolon are
   fine&mdash;you don't have to delete one of them.
</p>

<h3><a name="supers">Superscripts</a></h3>
<p>Older books often abbreviated words as contractions, and printed them as
   superscripts. For example:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Format these by inserting a single caret followed by the superscripted text, like this:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>
<p>In scientific &amp; technical works, format superscripted characters with curly braces
   <tt>{</tt> and <tt>}</tt> surrounding them, even if there is only one character superscripted.
   <br>For example:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;... up to x<sup>n-1</sup> elements in the array.
   <br>would be formatted as
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>... up to x^{n-1} elements in the array.<br></tt>
</p>
<p>The Project Manager may specify in the <a href="#comments">Project Comments</a>
   that superscripted text be marked up differently.
</p>

<h3><a name="subscr">Subscripts</a></h3>
<p>Subscripted text is often found in scientific works, but is not common in other
   material. Format subscripted text by inserting an underline character <tt>_</tt> and
   surrounding the text with curly braces <tt>{</tt> and <tt>}</tt>.
   <br>For example:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.
   <br>would be formatted as
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_{2}O.<br></tt>
</p>

<h3><a name="page_ref">Page References &quot;See Pg. 123&quot;</a></h3>
<p>Format page number references within the text such as <tt>(see p. 123)</tt> as
   they appear in the image.</p>
<p>Check the <a href="#comments">Project Comments</a> to see if the Project Manager
   has special requirements for page references.
</p>

<h3><a name="line_br">Line Breaks</a></h3>
<p><b>Leave all line breaks in</b> so that the next formatter and the post-processor can easily compare
   the lines in the text to the lines in the image. Be especially careful about this
   when rejoining <a href="#eol_hyphen">hyphenated words</a> or moving words around
   <a href="#em_dashes">em-dashes</a>. If the previous volunteer removed the line breaks,
   please replace them so that they once again match the image.
</p>
<p>Extra blank lines that are not in the image should be removed except where we intentionally
   add them for formatting. But blank lines at the bottom of the page are fine&mdash;these are removed
   when you save the page.
</p>

<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="chap_head">Chapter Headers</a></h3>
<p>Format chapter headers as they appear in the text.
</p>
<p>A chapter header may start a bit farther down the page than the <a href="#page_hf">page header</a>
   and won't have a page number on the same line. Chapter Headers are often printed all caps; if so,
   keep them as all caps. Chapter Headers are usually printed in a different or larger font which
   may appear to be bold or spaced out, but we do not mark them as a different font or
   as bold or spaced text; however you should include italics or small-caps markup
   if it appears in the header.
</p>
<p>Put 4 blank lines before the "CHAPTER XXX". Include these blank
   lines even if the chapter starts on a new page; there are no 'pages' in an e-book,
   so the blank lines are needed. Then leave one blank line between each additional part
   of the chapter header, such as a chapter description, opening quote, etc., and
   finally leave two blank lines before the start of the text of the chapter.
</p>
<p>Old books often printed the first word or two of every chapter in all caps or small caps;
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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
    &lt;sc&gt;Commercial Paper.&lt;/sc&gt;<br>
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
    If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J. M. Johnson a hundred dollars, it is<br>
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

<h3><a name="sect_head">Section Headers</a></h3>
<p>Some texts have sections within chapters. Format these headers as they appear in the text.
   Leave 2 blanks lines before the header and one after, unless the Project Manager has requested
   otherwise. If you are not sure if a header indicates a chapter or a section, post a question in
   the Project Thread, noting the page number. Section Headers are often printed in a different
   or larger font which may appear to be bold or spaced out, but we do not mark them as a
   different font or as bold or spaced text; however you should include italics or small-caps
   markup if it appears in the header.
</p>

<h3><a name="maj_div">Other Major Divisions in Texts</a></h3>
<p>Major Divisions in the text such as Preface, Foreword, Table of Contents, Introduction, Prologue, Epilogue,
   Appendix, References, Conclusion, Glossary, Summary, Acknowledgements, Bibliography, etc., should
   be formatted in the same way as Chapter Headers, <i>i.e.</i> 4 blank lines before the heading and 2 blank lines
   before the start of the text.
</p>

<h3><a name="para_space">Paragraph Spacing/Indenting</a></h3>
<p>Put a blank line before the start of paragraphs, even if a paragraph starts at the top of a page.
   You should not indent the start of paragraphs, but if paragraphs are already indented, don't
   bother removing those spaces&mdash;that can be done automatically during post-processing.
</p>
<p>See the <a href="#chap_head">Chapter Headers</a> image/text for an example.
</p>

<h3><a name="extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></h3>
<p>Most paragraphs start on the line immediately after the end of the previous one. Sometimes
   two paragraphs are separated to indicate a "thought break." A "thought break" may take the
   form of a line of stars, hyphens, or some other character, a plain or floridly decorated
   horizontal line, a simple decoration, or even just an extra blank line or two.
</p>
<p>A "thought break" may represent a change of scene or subject, a lapse in time, or a bit
   of suspense. This is intended by the author, so we preserve it by putting a blank line,
   <tt>&lt;tb&gt;</tt>, and then another blank line.
</p>
<p>Project Managers and/or Post-Processors may make the request for additional information
   to be retained in the thought break markup. For example, some projects delineate different
   types of breaks by the use of different styles of break such as a line of stars in one place
   and a blank line in another. In these cases, the Project Comments may request that these be
   marked up: <tt>&lt;tb stars&gt;</tt> and <tt>&lt;tb&gt;</tt>. Please, as always, read the
   project comments carefully so that you will know what is required for each project. Also
   be careful not to carry these special requests into other projects with different requirements.
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
      <td width="100%" valign="top"> <img src="tbreak.png" alt="thought break"
          width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    like the gentleman with the spiritual hydrophobia<br>
    in the latter end of Uncle Tom's Cabin.<br>
    Unconsciously Mr. Dixon has done his best to<br>
    prove that Legree was not a fictitious character.</tt>
    </p>
    <p><tt>&lt;tb&gt;</tt>
    </p>
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


<h3><a name="illust">Illustrations</a></h3>
<p>Text for an illustration should be surrounded by an illustration tag <tt>[Illustration:&nbsp;</tt> and <tt>]</tt>,
   with the caption text placed in between. Format the caption text as it is printed, preserving
   the line breaks, italics, etc.
</p>
<p>If an illustration has no caption, add a tag <tt>[Illustration]</tt>.
</p>
<p>If the illustration is in the middle of or at the side of a paragraph, move the illustration tag
   to before or after the paragraph and leave a blank line to separate them. Rejoin the paragraph
   by removing any blank lines left by doing so.
</p>
<p>If there is no paragraph break on the page, mark the illustration tag with an
   <tt>*</tt> like so <tt>*[Illustration: <font color="red">(text of caption)</font>]</tt>,
   move it to the top of the page, and leave a blank line after it.
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
     <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
     <p><tt>[Illustration: &lt;sc&gt;Fig.&lt;/sc&gt; 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
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
<p>During formatting, this means:
</p>
<p>1. The number, letter, or other character that marks a footnote location should
   be surrounded with square brackets (<tt>[</tt> and <tt>]</tt>) and
   placed right next to the word being footnoted<tt>[1]</tt> or its
   punctuation mark,<tt>[2]</tt> as shown in the text, and the two examples in this sentence.
</p>
<p>When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;,
   etc.) we replace these with Capital letters in order (A, B, C, etc.).
</p>
<p>2. A footnote should be surrounded by a footnote tag <tt>[Footnote #:&nbsp;</tt> and <tt>]</tt>,
   with the footnote text placed in between, and the footnote number or letter placed where the # is
   shown in the tag. Format the footnote text as it is printed, preserving the line breaks, italics, etc.
   Leave the footnote text at the bottom of the page.  Be sure to use the same tag in the footnote as
   you used in the text where the footnote was referenced. Place each footnote on a separate line in
   order of appearance. Place a blank line between each footnote if there is more than one.
</p>
<!-- END RR -->

<p>In some books, the Project Manager may ask that you move the footnotes
   in-line; read the <a href="#comments">Project Comments</a> for instructions in this case.
</p>
<p>See the <a href="#page_hf">Page Headers/Page Footers</a> image/text for a sample footnote.
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
      <th valign="top" align="left" bgcolor="cornsilk">Format with Out-of-Line Footnotes:</th>
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
   are formatted in the same manner as footnotes. Where you find an
   endnote reference in the text, just surround it with <tt>[</tt> and <tt>]</tt>.
   If you are formatting one of the ending pages with the endnotes text on it,
   surround the text of each note with <tt>[Footnote #: <font color="red">(text of endnote)</font>]</tt>, with
   the endnote text placed in between, and the endnote number or letter placed where the # is.
   Put a blank line after each endnote so that they remain separate
   paragraphs when the text is rewrapped during post-processing.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Footnotes in <a href="#poetry">Poetry</a> or <a href="#tables">Tables</a></b>
   should be treated the same as other footnotes. Volunteers should tag them and leave them
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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

<h3><a name="para_side">Paragraph Side-Descriptions (Sidenotes)</a></h3>
<p>Some books will have short descriptions of the paragraph along the side of the text.
   These are called sidenotes. Move sidenotes to just above the paragraph that they belong to.
   A sidenote should be surrounded by a sidenote tag <tt>[Sidenote:&nbsp;</tt> and <tt>]</tt>,
   with the text of the sidenote placed in between. Format the sidenote text as it is printed,
   preserving the line breaks, italics, etc. Leave a blank line after the sidenote, so that it
   does not get merged into the paragraph when the text is rewrapped during post-processing.
</p>
<p>If there are multiple sidenotes for a single paragraph, put them one after another
   at the start of the paragraph. Leave a blank line separating each of them.
</p>
<p>If the paragraph began on a previous page, put the sidenote at the top of the page
   and mark it with <tt>*</tt> so that the post-processor can see that it belongs on
   the previous page, like this: <tt>*[Sidenote: <font color="red">(text of sidenote)</font>]</tt>. The
   post-processor will move it to the appropriate place.
</p>
<p>Sometimes a Project Manager will request that you put sidenotes next to the sentence
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
    W&uuml;rzburg, in the sixteenth century, for the bishop's followers<br>
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
    [Footnote 1: &lt;i&gt;Op. cit.&lt;/i&gt; iv. 1. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>
    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J. W. Wolf, &lt;i&gt;Beitr&auml;ge zur deutschen<br>
    Mythologie&lt;/i&gt;, i. p. 217, &sect; 185).]<br>
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
    [Footnote 4: A. Birlinger, &lt;i&gt;Volksth&uuml;mliches aus<br>
    Schwaben&lt;/i&gt; (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 &lt;i&gt;sqq.&lt;/i&gt;, &sect; 128, pp. 103<br>
    &lt;i&gt;sq.&lt;/i&gt;, &sect; 129; &lt;i&gt;id.&lt;/i&gt;, &lt;i&gt;Aus Schwaben&lt;/i&gt; (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    &lt;i&gt;Deutsche Sagen, Sitten und Gebr&auml;uche<br>
    aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<br>
    423 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der Baumkultus&lt;/i&gt;,<br>
    p. 510.]<br>
    </tt></p>
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
<p>Apart from adding the markers, block quotations should be formatted as any other text.
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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

<h3><a name="mult_col">Multiple Columns</a></h3>
<p>Format ordinary text that has been printed in two columns as a single column.
</p>
<p>Spans of multiple-column text within single column sections should be formatted as a single column
   by placing the text from the left-most column first, the text from the next one after it, and so on.
   You do not need to mark where the columns were split, just join them together.
</p>
<p>If the columns are lists of items, mark the start of the list with <tt>/*</tt>
   and the end with <tt>*/</tt> so that the lines do not get rewrapped during post-processing.
   Leave a blank line between these markers and the rest of the text.
</p>
<p>See also the <a href="#bk_index">Indexes</a>, <a href="#lists">Lists of Items</a> and
   <a href="#tables">Tables</a> sections of these Guidelines.
</p>

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
Balboa, Vasco Nunez de     Descartes, Ren&eacute;          Pasteur, Louis
Bierce, Ambrose            Earhart, Amelia          Poe, Edgar Allan
Carroll, Lewis             Einstein, Albert         Ponce de Leon, Juan
Churchill, Winston         Freud, Sigmund           Pulitzer, Joseph
Columbus, Christopher      Lewis, Sinclair          Shakespeare, William
Curie, Marie               Magellan, Ferdinand      Tesla, Nikola
</pre>
</td></tr></table>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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
    Daguerre, Louis J. M.<br>
    Darwin, Charles<br>
    Descartes, Ren&eacute;<br>
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
   thread because other volunteers may have settled on a specific format. If there is nothing there, you might
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>/*
Deg. C.   Millimeters of Mercury.    Gasolene.
               Pure Benzene.

 -10&deg;               13.4                 43.5
   0&deg;               26.6                 81.0
 +10&deg;               46.6                132.0
  20&deg;               76.3                203.0
  40&deg;              182.0                301.8
*/</tt></pre>
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>/*
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
Same strip rolled up in|    |     || Same, 16 wires bound    |    |
  the form of wire     | 17 |  15 ||   close together        | 18 |   12
-----------------------+----+-----++-------------------------+----+------
*/</tt></pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>



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
   poetry during formatting. Move the lines to the left margin, and preserve the relative
   indentation of the lines.
</p>
<p><b>Footnotes</b> in poetry should be treated the same as regular footnotes during formatting.
   See <a href="#footnotes">footnotes</a> for details.
</p>
<p><b>Line Numbers</b> in poetry should be kept. Put them at the end of the
   line, leaving at least 6 spaces between them and the end of the text.
   See <a href="#line_no">Line Numbers</a> for details.
</p>
<p>Check the <a href="#comments">Project Comments</a> for the specific text you are formatting.
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
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
Round the elm-tree bole are in tiny leaf,<br>
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

<h3><a name="letter">Letters/Correspondence</a></h3>
<p>Format letters and correspondence as you would <a href="#para_space">paragraphs</a>.
   Put a blank line before the start of the letter; you do not need to duplicate any indenting.
</p>
<p>Surround consecutive heading or footer lines (such as addresses, date blocks, salutations, or signatures) with
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>&lt;i&gt;John James Audubon to Claude Fran&ccedil;ois Rozier&lt;/i&gt;</tt></p>
<p><tt>[Letter No. 1, addressed]</tt></p>
<p><tt>/*<br>
&lt;sc&gt;M. Fr. Rozier&lt;/sc&gt;,<br>
Merchant-Nantes.<br>
&lt;sc&gt;New York&lt;/sc&gt;, &lt;i&gt;10 January, 1807.&lt;/i&gt;</tt></p>
<p><tt>
&lt;sc&gt;Dear Sir:&lt;/sc&gt;<br>
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


<h3><a name="blank_pg">Blank Page</a></h3>
<p>Format as <tt>[Blank Page]</tt> if both the text and the image are blank.
</p>
<p>If there is text in the formatting text area and a blank image, or if there is an image
   but no text, follow the directions for a <a href="#bad_image">Bad Image</a>
   or <a href="#bad_text">Bad Text</a>.
</p>

<h3><a name="title_pg">Front/Back Title Page</a></h3>
<p>Format all the text, just as it was printed on the page, whether all capitals, upper and
   lower case, etc., including the years of publication or copyright.
</p>
<p>Older books often show the first letter as a large ornate graphic&mdash;format this as just the letter.
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
         DODD, MEAD AND COMPANY<br>
         1917</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="toc">Table of Contents</a></h3>
<p>Format the Table of Contents just as it is printed in the book, whether all capitals,
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt><br><br><br><br>CONTENTS<br><br></tt></p>
      <p><tt>/*<br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>
          I. &lt;sc&gt;The First Wayfarer and the Second Wayfarer<br>
          Meet and Part on the Highway&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1<br>
          <br>
          II. &lt;sc&gt;The First Wayfarer Lays His Pack Aside and<br>
          Falls in with Friends&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15<br>
          <br>
          III. &lt;sc&gt;Mr. Rushcroft Dissolves, Mr. Jones Intervenes,<br>
          and Two Men Ride Away&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;33<br>
          <br>
          IV. &lt;sc&gt;An Extraordinary Chambermaid, a Midnight<br>
          Tragedy, and a Man Who Said "Thank You"&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50<br>
          <br>
          V. &lt;sc&gt;The Farm-boy Tells a Ghastly Story, and an<br>
          Irishman Enters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;67<br>
          <br>
          VI. &lt;sc&gt;Charity Begins Far from Home, and a Stroll in<br>
          the Wildwood Follows&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
          <br>
          VII. &lt;sc&gt;Spun-gold Hair, Blue Eyes, and Various Encounters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. &lt;sc&gt;A Note, Some Fancies, and an Expedition in<br>
          Quest of Facts&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;120<br>
          <br>
          IX. &lt;sc&gt;The First Wayfarer, the Second Wayfarer, and<br>
          the Spirit of Chivalry Ascendant&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;134<br>
          <br>
          X. &lt;sc&gt;The Prisoner of Green Fancy, and the Lament of<br>
          Peter the Chauffeur&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;148<br>
          <br>
          XI. &lt;sc&gt;Mr. Sprouse Abandons Literature at an Early<br>
          Hour in the Morning&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;167<br>
          <br>
          XII. &lt;sc&gt;The First Wayfarer Accepts an Invitation, and<br>
          Mr. Dillingford Belabors a Proxy&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. &lt;sc&gt;The Second Wayfarer Receives Two Visitors at<br>
          Midnight&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;199<br>
          <br>
          XIV. &lt;sc&gt;A Flight, a Stone-cutter's Shed, and a Voice<br>
          Outside&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;221<br>
          */<br>
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bk_index">Indexes</a></h3>
<p>Please retain page numbers in index pages. Surround the index with <tt>/*</tt> and <tt>*/</tt> tags,
   leaving a blank line before the opening <tt>/*</tt>. You don't need to align the numbers
   as they appear in the scan; just put a comma or semicolon, followed by the page numbers.
</p>
<p>Indexes are often printed in 2 columns; this narrower space can cause entries to
   split onto the next line. Rejoin these back onto a single line.
</p>
<p>Indexes are a case where long lines created by following this rule are acceptable, since the lines
   will be rewrapped to the proper width and indentation during post-processing.
</p>
<p>Place one blank line between each entry in the index.
</p>
<p>For sub-topic listings in an index, start each one on a new line, indented 2 spaces.
</p>
<p>Treat each new section in an index (A, B, C...) the same as a <a href="#sect_head">section header</a> by placing 2
   blank lines before it.
</p>
<p>Old books sometimes printed the first word of each letter in the index in all caps or small caps;
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text: (with rejoined lines)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt><br>/*<br>
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text: (with index subtopics aligned)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt><br>/*<br>
    Hooker, Jos., maj. gen. U. S. V., 345;<br>
    &nbsp;&nbsp;assigned to command Porter's corps, 350;<br>
    &nbsp;&nbsp;afterwards, McDowell's, 367;<br>
    &nbsp;&nbsp;in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382;<br>
    &nbsp;&nbsp;unacceptable to Halleck, retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209;<br>
    &nbsp;&nbsp;notorious secessionist in Kanawha valley, 217;<br>
    &nbsp;&nbsp;controversy with Gen. Cox over escaped slave, 233.<br>
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
 <li>Format cast listings (Dramatis Person&aelig;) as <a href="#lists">lists</a>.</li>
 <li>Put four blank lines before the beginning of an Act.</li>
 <li>Put two blank lines before the beginning of each Scene.</li>
 <li>In dialogue, treat a change in speaker as a new paragraph, with one blank line between.</li>
 <li>Format actor names as they are in the original text, whether they are <a href="#italics">italics</a>,
   <a href="#bold">bold</a> or <a href="#word_caps">all capital</a> letters.</li>
 <li>Stage directions are formatted as they are in the original text.<br>
     If the stage direction is on a line by itself, format it that way; if it is at the end of a line of dialogue, leave it there;
     if it is right-justified at the end of a line of dialogue, leave six spaces between the dialogue and the stage directions.<br>
     Stage directions often begin with an opening bracket and omit the closing bracket.<br>
     This convention is retained; do not close the brackets. Italics are generally placed inside the brackets.</li>
</ul>
<p>For metrical plays: (Plays written as rhymed poetry)</p>
<ul compact>
 <li>Many plays are metrical, and like poetry should not be rewrapped.
     Surround metred text with <tt>/*</tt> and <tt>*/</tt> as for poetry.
     If stage directions are on their own line, do not surround these with
     <tt>/*</tt> and <tt>*/</tt>.
     (Stage directions are not metrical, and so can be safely rewrapped in the PP stage,
     so should not be contained within the /* */ tags
     that protect the metrical dialogue from being rewrapped.)</li>
 <li>Preserve relative indenting of dialog when a single metrical line is
     shared by more than one speaker.</li>
 <li>Rejoin metrical lines that were split due to width restrictions of the paper, just as in poetry.<br>
     If the continuation is only a word or so, it is often shown on the line
     above or below following a (, rather than having a line of its own.<br>
     See the <a href="#play4">example</a>.</li>
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
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
&lt;i&gt;La.&lt;/i&gt; But why a Peel-crow here?
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
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
Thou only wish and comfort of my soul!<br>
<br>
&lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. (&lt;i&gt;Exeunt.&lt;/i&gt;<br>
*/<br>
<br>
<br>
<br>
<br>
ACT THE THIRD.<br>
<br>
<br>
&lt;sc&gt;Scene I.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; 'Tis now just daybreak.--Why delay I then<br>
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
&lt;sc&gt;Scene II.&lt;/sc&gt;<br>
<br>
&lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Mene.&lt;/sc&gt; (&lt;i&gt;to himself&lt;/i&gt;). Sure I'm by nature form'd for misery<br>
Beyond the rest of humankind, or else<br>
'Tis a false saying, though a common one,<br>
"That time assuages grief." For ev'ry day<br>
My sorrow for the absence of my son<br>
Grows on my mind: the longer he's away,<br>
The more impatiently I wish to see him,<br>
The more pine after him.<br>
<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;)<br>
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
      <td width="100%" valign="top"><img src="play3.png" width="504"
          height="206" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
<br>
/*<br>
&lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
<br>
&lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
<br>
&lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Sample Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
Or not slept well to night; some dream (&lt;i&gt;Ismena?&lt;/i&gt;)<br>
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
<p>While formatting, if you encounter something that isn't covered in these guidelines that you
   think needs special handling or that you are not sure how to handle, post your question, noting
   the png (page) number, in the Project Discussion thread (a link to the project-specific forum thread is
   in the <a href="#comments">Project Comments</a>), and put a note in the formatted text explaining
   the problem. Your note will explain to the next volunteer or post-processor what the problem or
   question is.
</p>
<p>Start your note with a square bracket and two asterisks <tt>[**</tt> and end it with another square bracket <tt>]</tt>.
   This clearly separates it from the Author's text and signals the Post-Processor to stop and carefully examine
   this part of the text &amp; the matching image to address any issues. Any comments put in by a
   previous volunteer <b>must</b> be left in place. Agreement or disagreement can be added,
   but even if you know the answer, you absolutely must not remove the comment.
   If you have found a source which clarifies the problem, please cite it so the post-processor can also
   refer to it.
</p>
<p>If you are formatting in a later round and come across a note from a volunteer in a previous round that you know the answer to,
   please take a moment and provide Feedback to them by clicking on their
   name in the proofreading interface and posting a private message to them explaining how to handle the
   situation in the future. Please, as already stated, do not remove the note.
</p>

<h3><a name="prev_notes">Previous Proofreaders' Notes/Comments</a></h3>
<p>Any notes or comments put in by a previous volunteer <b>must</b> be left in place.
   You may add agreement or disagreement to the existing note
   but even if you know the answer, you absolutely must not remove the comment.
   If you have found a source which clarifies the problem, please cite it so the post-processor can also
   refer to it.
</p>
<p>If you are formatting in a later round and come across a note from a volunteer
   in a previous round that you know the answer to, please take a moment and provide Feedback
   to them by clicking on their name in the proofreading interface and posting a private message
   to them explaining how to handle the situation in the future.
   Please, as already stated, do not remove the note.
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
   books are often difficult, and are not recommended for beginning volunteers.
   They are more appropriate to experienced volunteers or people who have
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

<h3><a name="bad_image">Bad Images</a></h3>
<p>If an image is bad (not loading, chopped off, unable to be read), please put a post
   about this bad image in the project's <a href="#forums">forum thread</a>. Do not
   click on "Return Page to Round"; if you do, the page will be reissued to the next
   formatter. Instead, click on the "Report Bad Page" button so this page is
   'quarantined'.
</p>
<p>Note that some page images are quite large, and it is common for your browser to
   have difficulty displaying them, especially if you have several windows open or are
   using an older computer. Before reporting this as a bad page, try clicking on the
   "Image" line on the bottom of the page to bring up just the image in a new window.
   If that brings up a good image, then the problem is probably in your browser or
   system.
</p>
<p>It's fairly common for the image to be good, but the OCR'd text is missing the first
   line or two of the text. Please just type in the missing line(s). If nearly all of
   the lines are missing in the text box, then either type in the whole page (if you are
   willing to do that), or just click on the "Return Page to Round" button and the page
   will be reissued to someone else. If there are several pages like this, you might
   post a note in the <a href="#forums">project discussion</a> to notify the
   Project Manager.
</p>

<h3><a name="bad_text">Wrong Image for Text</a></h3>
<p>If there is a wrong image for the text given, please put a post about this bad image
   in the <a href="#forums">project discussion</a>. Do not click on "Return Page
   to Round"; if you do, the page will be reissued to the next formatter. Instead, click
   on the "Report Bad Page" button so this page is 'quarantined'.
</p>

<h3><a name="round1">Previous Proofreading and Formatting Mistakes</a></h3>
<p>If a previous volunteer made a lot of mistakes or missed a lot of things,
   please take a moment and provide Feedback to them by clicking on their name
   in the proofreading interface and posting a private message to them explaining
   how to handle the situation so that they will know how in the future.
</p>
<p><em>Please be nice!</em> Everyone here is a volunteer and presumably trying their best.
   The point of your feedback message should be to inform them of the correct way to
   format, rather than to criticize them. Give a specific example from their work
   showing what they did, and what they should have done.
</p>
<p>If the previous volunteer did an outstanding job, you can also send them a message
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
   <tt>[**typo fixed, changed from "txet" to "text"]</tt>.
   Include the two asterisks <tt>**</tt> so the post-processor will notice it.
</p>

<h3><a name="f_errors">Factual Errors in Texts</a></h3>
<p>In general, don't correct factual errors in the author's book. Many of the books we
   are preparing have statements of fact in them that we no longer accept as accurate.
   Leave them as the author wrote them.
</p>
<p>A possible exception is in technical or scientific books, where a known formula or
   equation may be given incorrectly, especially if it is shown correctly on other
   pages of the book. Notify the Project Manager about these, either by sending them a
   message via the <a href="#forums">Forum</a>, or by inserting <tt>[**note sic
   explain-your-concern]</tt> at that point in the text.
</p>

<h3><a name="uncertain">Uncertain Items</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[...to be completed...]
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
