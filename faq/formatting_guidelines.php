<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("en");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Formatting Guidelines', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Formatting Guidelines</a></h1>

<h3 align="center">Version 2.0, revised June 7, 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<p>Formatting Guidelines <a href="fr/formatting_guidelines.php">in French</a> /
      Directives de Formatage <a href="fr/formatting_guidelines.php">en fran&ccedil;ais</a><br>
    Formatting Guidelines <a href="pt/formatting_guidelines.php">in Portuguese</a> /
      Regras de Formata&ccedil;&atilde;o <a href="pt/formatting_guidelines.php">em Portugu&ecirc;s</a><br>
    Formatting Guidelines <a href="nl/formatting_guidelines.php">in Dutch</a> /
      Formatteer-Richtlijnen <a href="nl/formatting_guidelines.php">in het Nederlands</a><br>
    Formatting Guidelines <a href="de/formatting_guidelines.php">in German</a> /
      Formatierungsrichtlinien <a href="de/formatting_guidelines.php">auf Deutsch</a><br>
    Formatting Guidelines <a href="it/formatting_guidelines.php">in Italian</a> /
      Regole di Formattazione <a href="it/formatting_guidelines.php">in Italiano</a><br>
</p>

<p>Check out the <a href="../quiz/start.php?show_only=FQ">Formatting Quiz</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Table of Contents</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">The Primary Rule</a></li>
      <li><a href="#summary">Summary Guidelines</a></li>
      <li><a href="#about">About This Document</a></li>
      <li><a href="#separate_pg">Each Page is a Separate Unit</a></li>
      <li><a href="#comments">Project Comments</a></li>
      <li><a href="#forums">Forum/Discuss This Project</a></li>
      <li><a href="#prev_pg">Fixing Errors on Previous Pages</a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li style="margin-top:.25em;"><font size="+1">Formatting at the Character Level:</font>
        <ul>
          <li><a href="#inline">Placement of Inline Formatting Markup</a></li>
          <li><a href="#italics">Italics</a></li>
          <li><a href="#bold">Bold Text</a></li>
          <li><a href="#underl">Underlined Text</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Spaced Out Text</span> (gesperrt)</a></li>
          <li><a href="#font_ch">Font Changes</a></li>
          <li><a href="#small_caps">Words in <span style="font-variant: small-caps">Small Capitals</span></a></li>
          <li><a href="#word_caps">Words in All Capitals</a></li>
          <li><a href="#font_sz">Font Size Changes</a></li>
          <li><a href="#extra_sp">Extra Spaces or Tabs Between Words</a></li>
          <li><a href="#supers">Superscripts</a></li>
          <li><a href="#subscr">Subscripts</a></li>
          <li><a href="#page_ref">Page References &quot;See p. 123&quot;</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatting at the Paragraph Level:</font>
        <ul>
          <li><a href="#chap_head">Chapter Headings</a></li>
          <li><a href="#sect_head">Section Headings</a></li>
          <li><a href="#maj_div">Other Major Divisions in Texts</a></li>
          <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
          <li><a href="#extra_s">Thought Breaks (Extra Spacing/Decoration Between Paragraphs)</a></li>
          <li><a href="#illust">Illustrations</a></li>
          <li><a href="#footnotes">Footnotes/Endnotes</a></li>
          <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
          <li><a href="#outofline">Placement of Out-of-Line Formatting Markup</a></li>
          <li><a href="#block_qt">Block Quotations</a></li>
          <li><a href="#lists">Lists of Items</a></li>
          <li><a href="#tables">Tables</a></li>
          <li><a href="#poetry">Poetry/Epigrams</a></li>
          <li><a href="#line_no">Line Numbers</a></li>
          <li><a href="#letter">Letters/Correspondence</a></li>
          <li><a href="#r_align">Right-aligned Text</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatting at the Page Level:</font>
        <ul>
          <li><a href="#blank_pg">Blank Page</a></li>
          <li><a href="#title_pg">Front/Back Title Page</a></li>
          <li><a href="#toc">Table of Contents</a></li>
          <li><a href="#bk_index">Indexes</a></li>
          <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        </ul></li>
        <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a></li>
        <li><a href="#prev_notes">Previous Volunteers' Notes/Comments</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li style="margin-top:.25em;"><font size="+1">Common Problems:</font>
        <ul>
          <li><a href="#bad_image">Bad Image</a></li>
          <li><a href="#bad_text">Wrong Image for Text</a></li>
          <li><a href="#round1">Previous Proofreading or Formatting Mistakes</a></li>
          <li><a href="#p_errors">Printer Errors/Misspellings</a></li>
          <li><a href="#f_errors">Factual Errors in Texts</a></li>
        </ul></li>
        <li><a href="#index">Alphabetical Index to the Guidelines</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<div style="margin-right:6%; margin-left:0.5%;">

<h3><a name="prime">The Primary Rule</a></h3>
<p><em>"Don't change what the author wrote!"</em>
</p>
<p>The final electronic book seen by a reader, possibly many years in the future, should accurately convey
   the intent of the author. If the author spelled words oddly, we leave them spelled that way. If the author
   wrote outrageous racist or biased statements, we leave them that way. If the author put italics, bold text,
   or a footnote every third word, we mark them italicized, bold, or footnoted. If something in the
   text does not match the original page image, you should change the text so that it does match.
   (See <a href="#p_errors">Printer's Errors</a> for proper handling of obvious misprints.)
</p>
<p>We do change minor typographical conventions that don't affect the sense of what the author wrote.
   For example, we move illustration captions if necessary so that they only appear
   between paragraphs (<a href="#illust">Illustrations</a>).
   Changes such as these help us produce a <em>consistently formatted</em> version of the book.
   The rules we follow are designed to achieve this result. Please carefully read the rest of
   these Guidelines with this concept in mind. These guidelines are intended for formatting only.
   The proofreaders matched the image's content, and now as a formatter you match the image's look.
</p>
<p>To assist the next formatter and the post-processor, we also preserve line breaks.
   This allows them to easily compare the lines in the text to the lines in the image.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="summary">Summary Guidelines</a></h3>
<p>The <a href="formatting_summary.pdf">Formatting Summary</a> is a short, 2-page
   printer-friendly (.pdf) document that summarizes the main points of these
   Guidelines and gives examples of how to format. Beginning formatters are
   encouraged to print out this document and keep it handy while formatting.
</p>
<p>You may need to download and install a .pdf reader. You can get one free from Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">here</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="about">About This Document</a></h3>
<p>This document is written to explain the formatting rules we use to maintain consistency when formatting
   a single book that is distributed among many formatters, each of whom is working on different pages.
   This helps us all do formatting <em>the same way</em>, which in turn makes it
   easier for the post-processor to eventually combine all these pages into one e-book.
</p>
<p><i>It is not intended as any kind of a general editorial or typesetting rulebook</i>.
</p>
<p>We've included in this document all the items that new users have asked about
   formatting. There is a separate set of <a href="proofreading_guidelines.php">Proofreading
   Guidelines</a>. If you come across a situation and you do not find a reference in these
   guidelines, it is likely that it was handled in the proofreading rounds and so is not
   mentioned here. If you aren't sure, please ask about it in the <a href="#forums">Project Discussion</a>.
</p>
<p>If there are any items missing, or items that you
   consider should be done differently, or if something is vague, please let us know.
<?php if ($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   If you come across an unfamiliar term in these guidelines, see the
   <a href="http://www.pgdp.net/wiki/DP_Jargon">wiki jargon guide</a>.
<?php } ?>
   This document is a work in progress. Help us to improve it by posting your suggested changes in the
   Documentation Forum in <a href="<?php echo $Guideline_discussion_URL; ?>">this thread</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="separate_pg">Each Page is a Separate Unit</a></h3>
<p>Since each project is distributed among many formatters, each of whom is working on
   different pages, there is no guarantee that you will see the next page of the project.
   With this in mind, be sure to open and close all markup tags on each page. This will
   make it easier for the post-processor to eventually combine all these pages into one e-book.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="comments">Project Comments</a></h3>
<p>When you select a project for formatting, the Project Page is loaded. On this page there is a section called
   "Project Comments" containing information specific to that project (book). <b>Read these
   before you start formatting pages!</b> If the Project Manager wants you to format
   something in this book differently from the way specified in these Guidelines, that
   will be noted here. Instructions in the Project Comments <em>override</em> the rules
   in these Guidelines, so follow them. (This is also where the Project Manager may give
   you interesting tidbits of information about the author or the project.)
</p>
<p><em>Please also read the Project Thread (discussion)</em>: The Project Manager may clarify project-specific
   guidelines here, and it is often used by volunteers to alert other volunteers to recurring
   issues within the project and how they can best be addressed. (See below.)
</p>
<p>On the Project Page, the link 'Images, Pages Proofread, &amp; Differences' allows you to
   see how other volunteers have made changes.
   <a href="<?php echo $Using_project_details_URL; ?>">This forum thread</a>
   discusses different ways to use this information.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="forums">Forum/Discuss This Project</a></h3>
<p>On the Project Page where you start formatting pages, on the line "Forum", there is
   a link titled "Discuss this Project" (if the discussion has already started), or "Start
   a discussion on this Project" (if it hasn't). Clicking on that link will take you to a
   thread in the projects forum dedicated to this specific project. That is the place to ask
   questions about this book, inform the Project Manager about problems, etc. Using this project
   forum thread is the recommended way to communicate with the Project Manager and other
   volunteers who are working on this book.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="prev_pg">Fixing Errors on Previous Pages</a></h3>
<p>The <a href="#comments">Project Page</a> contains links to pages from this project that you have
   recently worked on. (If you haven't formatted any pages yet, no links will be
   shown.)
</p>
<p>Pages listed under either "DONE" or "IN PROGRESS" are available to make
   corrections or to finish formatting. Just click on the link to the page. Thus, if you
   discover that you made a mistake on a page or marked something incorrectly, you can
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
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatting at the Character Level:</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Placement of Inline Formatting Markup</a></h3>
<p>Inline formatting refers to markup such as <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>,
   <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>, <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>,
   <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>, or <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>.
   Place punctuation <b>outside</b> the tags unless the markup is around an
   entire sentence or paragraph, or the punctuation is itself part of the phrase, title,
   or abbreviation that you are marking. If the formatting goes on for multiple paragraphs,
   put the markup around each paragraph.
</p>
<p>The periods that mark an abbreviated word in the title of a journal such as <i>Phil. Trans.</i>
   are part of the title, so they are included within the tags, thus:
   <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Many typefaces found in older books used the same design for numbers
   in both regular text and italics or bold. For dates and similar phrases, format the
   entire phrase with one set of markup, rather than marking the words as italics (or bold) and not the numbers.
</p>
<p>If there is a series/list of words or phrases (such as names, titles, etc.),
   mark each item of the list individually.
</p>
<p>See the <a href="#tables">Tables</a> section for handling markup in tables. 
</p>
<!-- END RR -->
<p><b>Examples</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Inline markup examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Image:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><kbd>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</kbd> </td>
    </tr>
    <tr>
      <td valign="top">It cost 9<i>l.</i> 4<i>s.</i> 1<i>d.</i></td>
      <td valign="top"><kbd>It cost 9&lt;i&gt;l.&lt;/i&gt; 4&lt;i&gt;s.&lt;/i&gt; 1&lt;i&gt;d.&lt;/i&gt;</kbd></td>
    </tr>
    <tr>
      <td valign="top"><b>God knows what she saw in me!</b> I spoke<br> in such an affected manner.</td>
      <td valign="top"><kbd>&lt;b&gt;God knows what she saw in me!&lt;/b&gt; I spoke<br> in such an affected manner.</kbd></td>
    </tr>
    <tr>
      <td valign="top">As in many other of these <i>Studies</i>, and</td>
      <td valign="top"><kbd>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, and</kbd></td>
    </tr>
    <tr>
      <td valign="top">(<i>Psychological Review</i>, 1898, p. 160)</td>
      <td valign="top"><kbd>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 160)</kbd></td>
    </tr>
    <tr>
      <td valign="top">L. Robinson, art. "<span style="font-variant:small-caps;">Ticklishness</span>,"</td>
      <td valign="top"><kbd>L. Robinson, art. "&lt;sc&gt;Ticklishness&lt;/sc&gt;,"</kbd></td>
    </tr>
    <tr>
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br>
                     1323 Picadilly Circus</td>
      <td valign="top"><kbd>/*<br>
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br>
         1323 Picadilly Circus<br>
         */</kbd></td>
    </tr>
    <tr>
      <td valign="top">
      Volunteers may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <kbd>Volunteers may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</kbd>
      </td>
    </tr>
    <tr>
      <td valign="top">&ldquo;<i>That's the idea!</i>&rdquo; exclaimed Tacks.</td>
      <td valign="top"><kbd>"&lt;i&gt;That's the idea!&lt;/i&gt;" exclaimed Tacks.</kbd></td>
    </tr>
    <tr>
      <td valign="top">The professor set the reading assignment<br>
        for&nbsp; <span style="letter-spacing: .2em;">Erlebnis Geschichte Deutschland<br>
        seit 184</span>5.</td>
      <td valign="top"><kbd>The professor set the reading assignment<br>
        for &lt;g&gt;Erlebnis Geschichte Deutschland<br>
        seit 1845&lt;/g&gt;.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="italics">Italics</a></h3>
<p>Format <i>italicized</i> text with <kbd>&lt;i&gt;</kbd> inserted at the start and
   <kbd>&lt;/i&gt;</kbd> inserted at the end of the italics. (Note the "/" in the closing
   tag.)
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bold">Bold Text</a></h3>
<p>Format <b>bold text</b> (text printed in a heavier typeface) with
   <kbd>&lt;b&gt;</kbd> inserted before the bold text and <kbd>&lt;/b&gt;</kbd> after it. (Note the "/" in the closing
   tag.)
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a> and <a href="#chap_head">Chapter Headings</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="underl">Underlined Text</a></h3>
<p>Format <u>underlined text</u> as <a href="#italics">Italics</a>, with <kbd>&lt;i&gt;</kbd> and
   <kbd>&lt;/i&gt;</kbd>. (Note the "/" in the closing tag.)
   Underlining was often used to indicate emphasis when the typesetter was unable to actually
   italicize the text, for example in a typewritten document.
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a>.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   that underlined text be marked up with the <kbd>&lt;u&gt;</kbd> and <kbd>&lt;/u&gt;</kbd> tags.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Spaced Out Text</span> (gesperrt)</a></h3>
<p>Format&nbsp; <span style="letter-spacing: .2em;">spaced out</span>&nbsp; text with <kbd>&lt;g&gt;</kbd> inserted
   before the text and <kbd>&lt;/g&gt;</kbd> after it. (Note the "/" in the closing
   tag.) Remove the extra spaces between letters in each word.
   This was a typesetting technique used for emphasis in some older books,
   especially in German.
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a> and <a href="#chap_head">Chapter Headings</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="font_ch">Font Changes</a></h3>
<p>Some Project Managers may request that you mark a change of font within a paragraph
   or line of normal text by inserting <kbd>&lt;f&gt;</kbd> before
   the change in font and <kbd>&lt;/f&gt;</kbd> after it. (Note the "/" in the closing tag.)
   This markup may be used to identify a special font or other formatting
   that does not already have its own markup (such as italics and bold).
</p>
<p>Possible uses of this markup include:</p>
<ul compact>
  <li>antiqua (a variant of roman font) inside fraktur</li>
  <li>blackletter ("gothic" or "Old English" font) within a section of regular font</li>
  <li>smaller or larger font only if it is <b>within</b> a paragraph in regular font
    (for a whole paragraph in a different font or size, see the
    <a href="#block_qt">block quotation</a> section)</li>
  <li>upright font inside of a paragraph of italicized text</li>
</ul>
<p>The particular use or uses of this markup in a project will usually be spelled
   out in the <a href="#comments">Project Comments</a>. Formatters should post in the
   <a href="#forums">Project Discussion</a> if the markup appears to be needed
   and has not yet been requested.
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="small_caps">Words in Small Capitals</a></h3>
<p>The formatting is different for <span style="font-variant:small-caps;">Mixed Case Small Caps</span>
   and <span style="font-variant: small-caps;">all small caps</span>:
</p>
<p>Format words that are printed in <span style="font-variant: small-caps;">Mixed Small Caps</span>
   as Mixed Upper and Lowercase. Format words that are printed in
   <span style="font-variant: small-caps;">all small caps</span> as ALL-CAPS. For both mixed case and
   all small caps, surround the text with <kbd>&lt;sc&gt;</kbd> and <kbd>&lt;/sc&gt;</kbd> markup.
</p>
<p>Headings (<a href="#chap_head">Chapter Headings</a>, <a href="#sect_head">Section Headings</a>, Captions, etc.) may
   appear to be in <span style="font-variant: small-caps;">all small caps</span>,
   but this is usually the result of a change in <a href="#font_sz">font size</a>
   and should not be marked as small caps. The <a href="#chap_head">first word of a chapter</a>
   that is in small caps should be changed to mixed case without the tags.
</p>
<p>See also <a href="#inline">Placement of Inline Formatting Markup</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Small caps examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Image:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td valign="top"><span style="font-variant: small-caps;">This is Small Caps</span></td>
      <td valign="top"><kbd>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</kbd></td>
    </tr>
    <tr>
      <td valign="top">You cannot be serious about <span style="font-variant: small-caps;">aardvarks</span>!</td>
      <td valign="top"><kbd>You cannot be serious about &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="word_caps">Words in All Capitals</a></h3>
<p>Format words that are printed in all capital letters as all capital letters.
</p>
<p>The exception to this is the <a href="#chap_head">first word of a chapter</a>:
   many old books typeset the first word of these in all caps; this should be changed to upper and
   lower case, so "ONCE upon a time," becomes "<kbd>Once upon a time,</kbd>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="font_sz">Font Size Changes</a></h3>
<p>Normally we do not do anything to mark changes in font size.
   The exceptions to this are when it indicates a
   <a href="#block_qt">block quotation</a> or when the font size changes within a single
   paragraph or line of text (see <a href="#font_ch">Font Changes</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="extra_sp">Extra Spaces or Tabs Between Words</a></h3>
<p>Extra spaces between words are common in OCR output. You generally don't need to bother
   removing these&mdash;that can be done automatically during post-processing.
   However, extra spaces around punctuation, em-dashes, quote marks, etc. <b>do</b> need to be
   removed when they separate the symbol from the word. In addition, within the <kbd>/* */</kbd>
   markup that preserves spacing, be sure to remove any extra spaces since they will not
   be automatically removed later on.
</p>
<p>Finally, if you find any tab characters in the text you should remove them.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Superscripts</a></h3>
<p>Older books often abbreviated words as contractions, and printed them as
   superscripts. Format these by inserting a single caret (<kbd>^</kbd>) followed by the
   superscripted text. If the superscript continues for more than one character,
   then surround the text with curly braces <kbd>{</kbd> and <kbd>}</kbd> as well. For example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>If the superscript represents a footnote marker, then see the
   <a href="#footnotes">Footnotes</a> section instead.
</p>
<p>The Project Manager may specify in the <a href="#comments">Project Comments</a>
   that superscripted text be marked differently.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Subscripts</a></h3>
<p>Subscripted text is often found in scientific works, but is not common in other
   material. Format subscripted text by inserting an underline character <kbd>_</kbd> and
   surrounding the text with curly braces <kbd>{</kbd> and <kbd>}</kbd>. For example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="page_ref">Page References &quot;See p. 123&quot;</a></h3>
<p>Format page number references within the text such as <kbd>(see p. 123)</kbd> as
   they appear in the image.</p>
<p>Check the <a href="#comments">Project Comments</a> to see if the Project Manager
   has special requirements for page references.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatting at the Paragraph Level:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">Chapter Headings</a></h3>
<p>Format chapter headings as they appear in the image.
   A chapter heading may start a bit farther down the page than the page header
   and won't have a page number on the same line. Chapter Headings are often printed all caps; if so,
   keep them as all caps. Mark any <a href="#italics">italics</a> or <b>mixed case</b>
   <a href="#small_caps">small caps</a> that appear in the image.
</p>
<p>Put 4 blank lines before the "CHAPTER XXX". Include these blank
   lines even if the chapter starts on a new page; there are no 'pages' in an e-book,
   so the blank lines are needed. Then separate with a blank line each additional part
   of the chapter heading, such as a chapter description, opening quote, etc., and
   finally leave two blank lines before the start of the text of the chapter.
</p>
<p>Old books often printed the first word or two of every chapter in all caps or small caps;
   change these to upper and lower case (first letter only capitalized).
</p>
<p>While chapter headings may appear to be bold or spaced out,
   these are usually the result of font or font size changes and <b>should not be marked</b>.
   The extra blank lines separate the heading, so do not mark the font change as well.
   See the first example below.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>
        <br>
        <br>
        <br>
        <br>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>/#<br>In the United States?[A] In a railroad? In a mining company?<br>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="sect_head">Section Headings</a></h3>
<p>Some books have sections within chapters. Format these headings as they appear in the image.
   Leave 2 blanks lines before the heading and one after, unless the Project Manager has requested
   otherwise. If you are not sure if a heading indicates a chapter or a section, post a question in
   the <a href="#forums">Project Discussion</a>, noting the page number.
</p>
<p>Mark any <a href="#italics">italics</a> or <b>mixed case</b> <a href="#small_caps">small caps</a>
   that appear in the image. While section headings may appear to be bold or spaced out,
   these are usually the result of font or font size changes and <b>should not be marked</b>.
   The extra blank lines separate the heading, so do not mark the font change as well.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Section Heading example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        and numerous, found in collections of well-authenticated<br>
        specimens. The suggested caution implied<br>
        is not unnecessary, for the periods overlap, and there<br>
        is but little to show when such things as lamps and<br>
        lanterns were actually made.<br>
        <br>
        <br>
        RUSHLIGHTS AND HOLDERS.<br>
        <br>
        In tracing the development of lighting from quite<br>
        homely beginnings, rushlights, prepared by the<br>
        cottager and the farm hand for the winter supply,<br>
        seem to come first on the list. Rushlights, however,</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="maj_div">Other Major Divisions in Texts</a></h3>
<p>Major Divisions in the text such as Preface, Foreword, Table of Contents, Introduction, Prologue, Epilogue,
   Appendix, References, Conclusion, Glossary, Summary, Acknowledgements, Bibliography, etc., should
   be formatted in the same way as <a href="#chap_head">Chapter Headings</a>, <i>i.e.</i> 4 blank lines before the heading and 2 blank lines
   before the start of the text.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Paragraph Spacing/Indenting</a></h3>
<p>Put a blank line before the start of a paragraph, even if it starts at the top of a page.
   You should not indent the start of the paragraph, but if it is already indented don't
   bother removing those spaces&mdash;that can be done automatically during post-processing.
</p>
<p>See the <a href="#chap_head">Chapter Headings</a> image/text for an example.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="extra_s">Thought Breaks (Extra Spacing/Decoration Between Paragraphs)</a></h3>
<p>In the image, most paragraphs start on the line immediately after the end of the previous one. Sometimes
   two paragraphs are separated to indicate a "thought break." A thought break may take the
   form of a line of stars, hyphens, or some other character, a plain or floridly decorated
   horizontal line, a simple decoration, or even just an extra blank line or two.
</p>
<p>A thought break may represent a change of scene or subject, a lapse in time, or a bit
   of suspense. This is intended by the author, so we preserve it by putting a blank line,
   <kbd>&lt;tb&gt;</kbd>, and then another blank line.
</p>
<p>Sometimes printers used decorative lines to mark the ends of <a href="#chap_head">chapters</a>
   or <a href="#sect_head">sections</a>. These are not thought breaks so they should
   <b>not</b> be marked with <kbd>&lt;tb&gt;</kbd>.
</p>
<p>Please check the <a href="#comments">Project Comments</a> as the Project Manager may
   request that additional information be retained in the thought break markup, such as
   <kbd>&lt;tb stars&gt;</kbd> for a row of stars.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Thought Break example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="tbreak.png" alt="" width="500" height="249"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        last week, but my dressmaker put me off, because she<br>
        was working for Phillis B.'s wedding."</kbd>
        </p>
        <p><kbd>
        We both gave a glance at Hattie. She sat gazing at<br>
        Miss ----, her lips partly open, her eyes moistened,--a<br>
        picture in which delight and incredulity were in pleasant<br>
        strife.</kbd>
        </p>
        <p><kbd>&lt;tb&gt;</kbd>
        </p>
        <p><kbd>
        We have been in the interior a fortnight. One thing<br>
        filled me with astonishment, soon after I came here, namely,<br>
        to find widow ladies and their daughters, all through the</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="illust">Illustrations</a></h3>
<p>Text for an illustration should be surrounded by an illustration tag <kbd>[Illustration:&nbsp;</kbd> and <kbd>]</kbd>,
   with the caption text placed in between. Format the caption text as it is printed, preserving
   the line breaks, italics, etc. Text that could be (part of) a caption should be
   included, such as "See page 66" or a title within the bounds of the illustration.
</p>
<p>If an illustration has no caption, add a tag <kbd>[Illustration]</kbd>. (Be sure to remove the colon and space
   before the <kbd>]</kbd> in this case.)
</p>
<p>If the illustration is in the middle of or at the side of a paragraph, move the illustration tag
   to before or after the paragraph and leave a blank line to separate them. Rejoin the paragraph
   by removing any blank lines left by doing so.
</p>
<p>If there is no paragraph break on the page, mark the illustration tag with an
   <kbd>*</kbd> like so <kbd>*[Illustration: <font color="red">(text of caption)</font>]</kbd>,
   move it to the top of the page, and leave a blank line after it.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>[Illustration: Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        /*<br>
        &lt;i&gt;Frontispiece&lt;/i&gt;<br>
        &lt;i&gt;Her Weight in Gold&lt;/i&gt;<br>
        */<br>
        ]
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image: (Illustration in middle of paragraph)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        such study are due to Italians. Several of these instruments<br>
        have already been described in this journal, and on the present<br>
        occasion we shall make known a few others that will<br>
        serve to give an idea of the methods employed.<br>
        </kbd></p>
        <p><kbd>[Illustration: &lt;sc&gt;Fig. 1.&lt;/sc&gt;--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
        SEISMIC MOVEMENTS.]</kbd></p>
        <p><kbd>
        For the observation of the vertical and horizontal motions<br>
        of the ground, different apparatus are required. The</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="footnotes">Footnotes/Endnotes</a></h3>
<p>Format footnotes by leaving the text of the footnote
   at the bottom of the page and placing a tag where it is referenced in the text.
   This means:
</p>
<p>1. In the main text, the character that marks a footnote location should
   be surrounded with square brackets (<kbd>[</kbd> and <kbd>]</kbd>) and
   placed right next to the word being footnoted<kbd>[1]</kbd> or its
   punctuation mark,<kbd>[2]</kbd> as shown in the image and the two examples in this sentence.
   Footnote markers may be numbers, letters, or symbols.
   When footnotes are marked with a symbol or a series of symbols (*, &dagger;, &Dagger;, &sect;,
   etc.) we replace these with Capital letters in order (A, B, C, etc.).
</p>
<p>2. At the bottom of the page, a footnote should be surrounded by a footnote tag <kbd>[Footnote #:&nbsp;</kbd> and <kbd>]</kbd>,
   with the footnote text placed in between and the footnote number or letter placed where the # is
   shown in the tag. Format the footnote text as it is printed, preserving the line breaks, italics, etc.
   Be sure to use the same tag in the footnote as
   you used in the text where the footnote was referenced. Place each footnote on a separate line in
   order of appearance, with a blank line before each one.
</p>
<!-- END RR -->

<p>If a footnote is incomplete at the end of the page,
   leave it at the bottom of the page and just put an asterisk <kbd>*</kbd>
   where the footnote ends, like this: <kbd>[Footnote 1: <font color="red">(text of footnote)</font>]*</kbd>. The
   <kbd>*</kbd> will bring it to the
   attention of the post-processor, who will eventually join the parts of the
   footnote together.
</p>
<p>If a footnote started on a previous page, leave it at the bottom of the page and surround it with
   <kbd>*[Footnote: <font color="red">(text of footnote)</font>]</kbd>
   (without any footnote number or marker). The <kbd>*</kbd> will bring it to the attention of the post-processor,
   who will eventually join the parts of the footnote together.
</p>
<p>If a continued footnote ends or starts on a hyphenated word, mark <b>both</b> the footnote
   and the word with <kbd>*</kbd>, thus:<br>
   <kbd>[Footnote 1: This footnote is continued and the last word in it is also con-*]*</kbd><br>
   for the leading fragment, and<br>
   <kbd>*[Footnote: *tinued onto the next page.]</kbd>.
</p>
<p>Do not include any horizontal lines separating the footnotes from the main text.
</p>
<p><b>Endnotes</b> are just footnotes that have been located together at the end of a
   chapter or at the end of the book, instead of on the bottom of each page. These
   are formatted in the same manner as footnotes. Where you find an
   endnote reference in the text, just surround it with <kbd>[</kbd> and <kbd>]</kbd>.
   If you are formatting one of the pages with endnotes,
   surround the text of each note with <kbd>[Footnote #: <font color="red">(text of endnote)</font>]</kbd>, with
   the endnote text placed in between, and the endnote number or letter placed where the # is.
   Put a blank line before each endnote so that they remain separate
   paragraphs when the text is rewrapped during post-processing.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Footnotes in <a href="#tables">Tables</a> should remain where they are in the original image.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr>
      <td valign="top">
        The principal persons involved in this argument were Caesar*, former military<br>
        leader and Imperator, and the orator Cicero&dagger;. Both were of the aristocratic<br>
        (Patrician) class, and were quite wealthy.<br>
        <hr align="left" width="25%" noshade size="2">
        <font size=-1>* Gaius Julius Caesar.</font><br>
        <font size=-1>&dagger; Marcus Tullius Cicero.</font>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
      <tr valign="top">
      <td>
        <kbd>The principal persons involved in this argument were Caesar[A], former military</kbd><br>
        <kbd>leader and Imperator, and the orator Cicero[B]. Both were of the aristocratic</kbd><br>
        <kbd>(Patrician) class, and were quite wealthy.</kbd><br>
        <br>
        <kbd>[Footnote A: Gaius Julius Caesar.]</kbd><br>
        <br>
        <kbd>[Footnote B: Marcus Tullius Cicero.]</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Footnoted Poetry:</th></tr>
    <tr>
      <td valign="top">
        Mary had a little lamb<sup>1</sup><br>
        &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
        <hr align="left" width="10%" noshade size=2>
        <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.</font>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td valign="top">
        <kbd>/*<br>
        Mary had a little lamb[1]<br>
        &nbsp;&nbsp;Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        &nbsp;&nbsp;The lamb was sure to go!<br>
        */<br>
        <br>
        [Footnote 1: This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.]<br>
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="para_side">Paragraph Side-Descriptions (Sidenotes)</a></h3>
<p>Some books will have short descriptions of the paragraph along the side of the text.
   These are called sidenotes. Move sidenotes to just above the paragraph that they belong to.
   A sidenote should be surrounded by a sidenote tag <kbd>[Sidenote:&nbsp;</kbd> and <kbd>]</kbd>,
   with the text of the sidenote placed in between. Format the sidenote text as it is printed,
   preserving the line breaks, italics, etc. (while handling end-of-line hyphenation and dashes normally).
   Leave a blank line before and after the sidenote to separate it from the normal text.
</p>
<p>If there are multiple sidenotes for a single paragraph, put them one after another
   at the start of the paragraph. Leave a blank line separating each of them.
</p>
<p>If the paragraph began on a previous page, put the sidenote at the top of the page
   and mark it with <kbd>*</kbd> so that the post-processor can see that it belongs on
   the previous page, like this: <kbd>*[Sidenote: <font color="red">(text of sidenote)</font>]</kbd>. The
   post-processor will move it to the appropriate place.
</p>
<p>Sometimes a Project Manager will request that you put sidenotes next to the sentence
   they apply to, rather than at the top or bottom of the paragraph. In this case,
   don't separate them out with blank lines.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
        <p><kbd>
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
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="outofline">Placement of Out-of-Line Formatting Markup</a></h3>
<p>Out-of-line formatting refers to the <kbd>/#</kbd> <kbd>#/</kbd> and <kbd>/*</kbd> <kbd>*/</kbd> markup tags.
   The <kbd>/#</kbd> <kbd>#/</kbd> "rewrap" markup indicates text that is printed differently, but can still
   be rewrapped during post-processing. The <kbd>/*</kbd> <kbd>*/</kbd> "no-wrap" markup indicates text that
   should not be rewrapped later on during post-processing&mdash;where the line breaks, indentation,
   and spacing need to be preserved.
</p>
<p>On any page where you use an opening marker, be sure to include the closing markup tag
   as well. After the text is rewrapped during post-processing, each marker will be removed
   along with the entire line that it is on.  Because of this, leave a blank line between
   the regular text and the opening marker, and similarly leave a blank line between the closing
   marker and the regular text.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="block_qt">Block Quotations</a></h3>
<p>Block quotations are blocks of text (typically several lines and sometimes several pages) that are
   distinguished from the surrounding text by wider margins, a smaller font size, different
   indentation, or other means. Surround block quotations with <kbd>/#</kbd> and <kbd>#/</kbd> markers. 
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup.
</p>
<p>Apart from adding the markers, block quotations should be formatted as any other text.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Block Quotation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>later day was welcomed in their home on the Hudson.<br>
        Dr. Bakewell's contribution was as follows:[24]</kbd></p>
        <p><kbd>/#<br>
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
        #/<br></kbd>
        </p>
        <p><kbd>We do not doubt the candor and sincerity of the<br>
        excellent Dr. Bakewell, but are bound to say that the<br>
        incidents as related above betray a striking lapse of<br>
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="lists">Lists of Items</a></h3>
<p>Surround lists with <kbd>/*</kbd> and <kbd>*/</kbd> markers.
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">
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
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td valign="top">
        <kbd>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="tables">Tables</a></h3>
<p>Surround tables with <kbd>/*</kbd> and <kbd>*/</kbd> markers. See <a href="#outofline">Placement
   of Out-of-Line Formatting Markup</a> for details on this markup.
   Format the table with spaces (<b>not tabs</b>) to look approximately like the original table.
   Try to avoid overly wide tables where possible; generally under 75 characters wide is best.
</p>
<p>Do not use tabs for formatting&mdash;use space
   characters only. Tab characters will line up differently between computers, and your careful
   formatting will not always display the same way. Remove any periods or other punctuation
   (leaders) used to align the items.
</p>
<p>If inline formatting (italics, bold, etc.) is needed in the table, mark
   up each table cell separately. When aligning the text, keep in mind that
   inline markup will appear differently in the final text version. For example,
   <kbd>&lt;i&gt;</kbd>italics markup<kbd>&lt;/i&gt;</kbd> normally becomes <kbd>_</kbd>underscores<kbd>_</kbd>,
   and most other inline markup will be treated similarly. On the other hand,
   <kbd>&lt;sc&gt;</kbd>Small Caps Markup<kbd>&lt;/sc&gt;</kbd> is removed completely.
</p>
<p>It's often hard to format tables in plain text; just do your best.
   Be sure to use a mono-spaced font, such as <a href="font_sample.php">DPCustomMono</a> or Courier.
   Remember that the goal is to preserve the Author's meaning, while producing a
   readable table in an e-book. Sometimes this requires sacrificing the original
   format of the table on the printed page. Check the <a href="#comments">Project Comments</a> and discussion
   thread because other volunteers may have settled on a specific format. If there is nothing there, you might
   find something useful in the <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a> forum thread.
</p>
<p><b>Footnotes</b> in tables should remain where they are in the image. See <a href="#footnotes">footnotes</a> for details.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>
TABLE II.

/*
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
*/</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>/*
                        &lt;i&gt;Agents.&lt;/i&gt;      &lt;i&gt;Objects.&lt;/i&gt;
            { 1st person,  I,             me,
            { 2d    "      thou,          thee,
&lt;i&gt;Singular&lt;/i&gt;  { 3d    "  mas. { he,         him,
            {       "  fem. { she,        her,
            {              it,            it.

            { 1st person,  we,            us,
 &lt;i&gt;Plural&lt;/i&gt;   { 2d    "      ye, or you,    you,
            { 3d    "      they,          them,
                           who,           whom.
*/</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="poetry">Poetry/Epigrams</a></h3>
<p>Mark poetry or epigrams with <kbd>/*</kbd> and <kbd>*/</kbd> so that the line breaks and spacing will be preserved. 
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup.
</p>
<p>Preserve the relative indentation of the individual lines of the poem or epigram by adding
   2, 4, 6 (or more) spaces in front of the indented lines to make them resemble the image.
   If the entire poem is centered on the printed page, don't try to center the lines of
   poetry during formatting. Move the lines to the left margin, and preserve the relative
   indentation of the lines.
</p>
<p>When a line of verse is too long for the printed page, many books wrap the continuation
   onto the next printed line and place a wide indentation in front of it. These continuation lines
   should be rejoined with the line above. Continuation lines usually start with a lower case
   letter. They will appear randomly unlike normal indentation, which occurs at regular intervals
   in the meter of the poem.
</p>
<p>If a row of dots appears in a poem, treat this as a <a href="#extra_s">thought break</a>.
</p>
<p><a href="#line_no">Line Numbers</a> in poetry should be kept.
</p>
<p>Check the <a href="#comments">Project Comments</a> for the specific project you are formatting.
   Books of poetry often have special instructions from the Project Manager. Many times, you won't
   have to follow all these formatting guidelines for a book that is mostly or entirely poetry.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>
        to the scenery of his own country:<br></kbd>
        <p><kbd>
        /*<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, to be in England<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that April's there,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And whoever wakes in England<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, unaware,<br>
        That the lowest boughs and the brushwood sheaf<br>
        Round the elm-tree bole are in tiny leaf,<br>
        While the chaffinch sings on the orchard bough<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In England--now!</kbd>
        </p><p><kbd>
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
        */<br></kbd>
        </p><p><kbd>
        So it runs; but it is only a momentary memory;<br>
        and he knew, when he had done it, and to his</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="line_no">Line Numbers</a></h3>
<p>Line numbers are common in books of poetry, and usually appear near the margin
   every fifth or tenth line.
   Keep line numbers, placing them at least six spaces past the right hand end of
   the line, even if they are on the left side of the poetry/text in the original image.
   Since poetry will not be rewrapped in the e-book
   version, the line numbers will be useful to readers.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="letter">Letters/Correspondence</a></h3>
<p>Format letters and correspondence as you would <a href="#para_space">paragraphs</a>.
   Put a blank line before the start of the letter; do not duplicate any indenting.
</p>
<p>Surround consecutive heading or footer lines (such as addresses, date blocks, salutations, or signatures) with
   <kbd>/*</kbd> and <kbd>*/</kbd> markers. See <a href="#outofline">Placement of Out-of-Line Formatting
   Markup</a> for details on this markup.
</p>
<p>Don't indent the heading or footer lines, even if they are indented or right justified in the image&mdash;just
   put them at the left margin. The post-processor will format them as needed.
</p>
<p>If the correspondence is printed differently than the main text, see <a href="#block_qt">Block Quotations</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>&lt;i&gt;John James Audubon to Claude Fran&ccedil;ois Rozier&lt;/i&gt;</kbd></p>
        <p><kbd>[Letter No. 1, addressed]</kbd></p>
        <p><kbd>/*<br>
        &lt;sc&gt;M. Fr. Rozier&lt;/sc&gt;,<br>
        Merchant-Nantes.<br>
        &lt;sc&gt;New York&lt;/sc&gt;, &lt;i&gt;10 January, 1807&lt;/i&gt;.</kbd></p>
        <p><kbd>
        &lt;sc&gt;Dear Sir&lt;/sc&gt;:<br>
        */</kbd></p>
        <p><kbd>
        We have had the pleasure of receiving by the &lt;i&gt;Penelope&lt;/i&gt; your<br>
        consignment of 20 pieces of linen cloth, for which we send our<br>
        thanks. As soon as we have sold them, we shall take great<br>
        pleasure in making our return.</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/#<br>
        lack of memory which &lt;i&gt;baffles belief&lt;/i&gt;, I have a certain<br>
        "uptaking" knack. My preachment will bore you, but you<br>
        will (if you read it) detect an &lt;i&gt;ensemble&lt;/i&gt;; but, for goodness'<br>
        sake, &lt;i&gt;zitti&lt;/i&gt;! They'll think, when they hear the P.R.A., that,<br>
        Lor' bless him! he'd known it all his life. Nevertheless,<br>
        enough for the day, &amp;c. Best love to Gussey.--Affect. bro.,</kbd></p>
        <p><kbd>/*<br>
        &lt;sc&gt;Fred.&lt;/sc&gt;<br>
        */<br>
        #/</kbd></p>
        <p><kbd>I remember--when my husband and I were<br>
        sitting with him one afternoon after his return<br>
        home that autumn--his saying, "I feel distinctly I</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="r_align">Right-aligned Text</a></h3>
<p>Surround lines of right-justified text with <kbd>/*</kbd> and <kbd>*/</kbd> markers.
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup, and the <a href="#letter">Letters/Correspondence</a> section for examples.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatting at the Page Level:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Blank Page</a></h3>
<p>Format as <kbd>[Blank Page]</kbd> if both the text and the image are blank.
</p>
<p>If there is text in the formatting text area and a blank image, or if there is text in the image
   but none in the text box, follow the directions for a <a href="#bad_image">Bad Image</a>
   or <a href="#bad_text">Bad Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="title_pg">Front/Back Title Page</a></h3>
<p>Format all the text just as it was printed on the page, whether all capitals, upper and
   lower case, etc., including the years of publication or copyright.
</p>
<p>Older books often show the first letter as a large ornate graphic&mdash;format this as just the letter.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        GREEN FANCY</kbd></p>
        <p><kbd>BY<br>
        GEORGE BARR McCUTCHEON</kbd></p>
        <p><kbd>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
        "THE PRINCE OF GRAUSTARK," ETC.</kbd></p>
        <p><kbd>&lt;i&gt;WITH FRONTISPIECE BY<br>
        C. ALLAN GILBERT&lt;/i&gt;</kbd></p>
        <p><kbd>[Illustration]</kbd></p>
        <p><kbd>NEW YORK<br>
        DODD, MEAD AND COMPANY<br>
        1917<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="toc">Table of Contents</a></h3>
<p>Format the Table of Contents just as it is printed in the book, whether all capitals,
   upper and lower case, etc. and surround it with <kbd>/*</kbd> and <kbd>*/</kbd>.
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup.
</p>
<p>Page number references should be placed <b>at least six spaces</b> past the end
   of the text. Remove any periods or other punctuation (leaders) used to align the page numbers.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="tablec.png" alt="" width="500" height="650"></td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd><br><br><br><br>CONTENTS<br>
        <br>
        <br>
        /*<br>
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
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bk_index">Indexes</a></h3>
<p>Surround the index with <kbd>/*</kbd> and <kbd>*/</kbd> tags.
   See <a href="#outofline">Placement of Out-of-Line Formatting Markup</a> for details on
   this markup. You don't need to align the numbers
   as they appear in the image; just put a comma followed by the page numbers.
</p>
<p>Indexes are often printed in 2 columns; this narrower space can cause entries to
   split onto the next line. Rejoin these back onto a single line.
   This may create long lines, but they
   will be rewrapped to the proper width and indentation during post-processing.
</p>
<p>Place one blank line before each entry in the index.
   For sub-topic listings (often separated by a semicolon <kbd>;</kbd>), start each one
   on a new line, indented 2 spaces.
</p>
<p>Treat each new section in an index (A, B, C...) the same as a <a href="#sect_head">section heading</a> by placing 2
   blank lines before it.
</p>
<p>Old books sometimes printed the first word of each section in the index in all caps or small caps;
   change this to match the style used for the rest of the index entries.
</p>
<p>Please check the <a href="#comments">Project Comments</a> as the Project Manager may
   request different formatting, such as treating the index like a <a href="#toc">Table of Contents</a> instead.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr>
      <td valign="top">
        Elizabeth I, her royal Majesty the<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.<br>
        &nbsp;&nbsp;birth of, 145.<br>
        &nbsp;&nbsp;christening, 146-147.<br>
        &nbsp;&nbsp;death and burial, 152.<br>
        <br>
        Ethelred II, the Unready, 33.
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
        Elizabeth I, her royal Majesty the Queen, 123, 144-155.<br>
        &nbsp;&nbsp;birth of, 145.<br>
        &nbsp;&nbsp;christening, 146-147.<br>
        &nbsp;&nbsp;death and burial, 152.<br>
        <br>
        Ethelred II, the Unready, 33.<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Aligning Index Subtopics">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">
        Hooker, Jos., maj. gen. U. S. V., 345; assigned<br>
        &nbsp;&nbsp;&nbsp;to command Porter's corps, 350; afterwards,<br>
        &nbsp;&nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 380;<br>
        &nbsp;&nbsp;&nbsp;at South Mt., 382; unacceptable to Halleck,<br>
        &nbsp;&nbsp;&nbsp;retires from active service, 390.<br>
        Hopkins, Henry H., 209; notorious secessionist in<br>
        &nbsp;&nbsp;&nbsp;Kanawha valley, 217; controversy with Gen.<br>
        &nbsp;&nbsp;&nbsp;Cox over escaped slave, 233.<br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>J</b><br>
        <br>
        <span style="font-variant: small-caps">James</span>, Lewis M., 187; capt. on Gen. Wilson's staff, 194.<br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
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
        <br>
        J<br>
        <br>
        James, Lewis M., 187;<br>
        &nbsp;&nbsp;capt. on Gen. Wilson's staff, 194.<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Index Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th></tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
        Sales committee, 52<br>
        <br>
        Sales manager, 30<br>
        <br>
        Sales records, 120<br>
        &nbsp;&nbsp;daily, 121<br>
        &nbsp;&nbsp;monthly, 123<br>
        &nbsp;&nbsp;salesmen's, 123<br>
        <br>
        Shipping clerk, 184<br>
        &nbsp;&nbsp;class rates, 186<br>
        &nbsp;&nbsp;commodity rate file, 193<br>
        &nbsp;&nbsp;commodity rates, 186<br>
        &nbsp;&nbsp;freight tariffs, 188<br>
        &nbsp;&nbsp;routing shipments, 194<br>
        <br>
        Shipping department, 183-229<br>
        &nbsp;&nbsp;back orders, 199<br>
        &nbsp;&nbsp;checking shipments, 200<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="play_n">Plays: Actor Names/Stage Directions</a></h3>
<p>For all plays:</p>
<ul compact>
 <li>Format cast listings (Dramatis Person&aelig;) as <a href="#lists">lists</a>.</li>
 <li>Treat each new Act the same as a <a href="#chap_head">chapter heading</a> by placing 4
     blank lines before it and 2 after.</li>
 <li>Treat each new Scene the same as a <a href="#sect_head">section heading</a> by placing 2
     blank lines before it.</li>
 <li>In dialog, treat a change in speaker as a new paragraph, with one blank line before it.
     If the speaker's name is on its own line, treat that as a separate paragraph as well.</li>
 <li>Format actor names as they are in the original image, whether they are <a href="#italics">italics</a>,
     <a href="#bold">bold</a>, or all capital letters.</li>
 <li>Stage directions are formatted as they are in the original image,
     so if the stage direction is on a line by itself, format it that way; if it is at the end of a line of dialog, leave it there;
     if it is right-justified at the end of a line of dialog, leave at least six spaces between the dialog and the stage directions.<br>
     Stage directions often begin with an opening bracket and omit the closing bracket.
     This convention is retained; do not close the brackets. Italics markup is generally placed inside the brackets.</li>
</ul>
<p>For metrical plays (plays written as poetry):</p>
<ul compact>
 <li>Many plays are metrical, and like poetry should not be rewrapped.
     Surround metered text with <kbd>/*</kbd> and <kbd>*/</kbd> as for <a href="#poetry">poetry</a>.
     If stage directions are on their own line, do not surround these with
     <kbd>/*</kbd> and <kbd>*/</kbd>.
     (Since stage directions are not metrical, and can be safely rewrapped in the PP stage,
     they should not be contained within the /* */ tags
     that protect the metrical dialog.)</li>
 <li>Preserve relative indention of dialog as with <a href="#poetry">poetry</a>.</li>
 <li>Rejoin metrical lines that were split due to width restrictions of the paper, just as in <a href="#poetry">poetry</a>.
     If the continuation is only a word or so, it is often shown on the line
     above or below following a (, rather than having a line of its own.
     See the <a href="#play4">example</a>.</li>
</ul>
<p>Please check the <a href="#comments">Project Comments</a>, as the Project Manager may
   specify different formatting.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        Has not his name for nought, he will be trode upon:<br>
        What says my Printer now?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
        You shall have perfect Books now in a twinkling.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
        Blows should have marks, or else they are nothing worth.
        </kbd></p><p><kbd>
        &lt;i&gt;La.&lt;/i&gt; But why a Peel-crow here?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
        A scare-crow had been better.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
        Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
        Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
        And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
        Indeed I know not what to make on 'em.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
        &lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        &lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
        Thou only wish and comfort of my soul!<br>
        <br>
        &lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&lt;i&gt;Exeunt.&lt;/i&gt;<br>
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
        &lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus&lt;/sc&gt;.<br>
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
        &lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus&lt;/sc&gt;.)<br>
        Yonder he stands. I'll go and speak with him.<br>
        Good-morrow, neighbor! I have news for you;<br>
        Such news as you'll be overjoy'd to hear.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play3"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
        <br>
        /*<br>
        &lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
        <br>
        &lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
        Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
        <br>
        &lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Formatted Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        &lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
        Or not slept well to night; some dream (&lt;i&gt;Ismena?&lt;/i&gt;)<br>
        <br>
        &lt;i&gt;Ism.&lt;/i&gt; My dreams are like my thoughts, honest and innocent,<br>
        Yours are unhappy; who are these that coast us?<br>
        You told me the walk was private.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="anything">Anything else that needs special handling or that you're unsure of</a></h3>
<p>While formatting, if you encounter something that isn't covered in these guidelines that you
   think needs special handling or that you are not sure how to handle, post your question, noting
   the png (page) number, in the <a href="#forums">Project Discussion</a>.
</p>
<p>You should also put a note in the formatted text to
   explain to the next volunteer or post-processor what the problem or question is.
   Start your note with a square bracket and two asterisks <kbd>[**</kbd> and end it with another square bracket <kbd>]</kbd>.
   This clearly separates it from the author's text and signals the post-processor to
   stop and carefully examine this part of the text and the matching image to address any issues.
   You may also want to identify which round you are working in just before the <kbd>]</kbd>
   so that later volunteers know who left the note.
   Any comments put in by a previous volunteer <b>must</b> be left in place. See the next section for details.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="prev_notes">Previous Volunteers' Notes/Comments</a></h3>
<p>Any notes or comments put in by a previous volunteer <b>must</b> be left in place.
   You may add agreement or disagreement to the existing note
   but even if you know the answer, you absolutely must not remove the comment.
   If you have found a source which clarifies the problem, please cite it so the post-processor can also
   refer to it.
</p>
<p>If you come across a note from a previous volunteer
   that you know the answer to, please take a moment and provide feedback
   to them by clicking on their name in the formatting interface and posting a private message
   to them explaining how to handle the situation in the future.
   Please, as already stated, do not remove the note.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Common Problems:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="bad_image">Bad Image</a></h3>
<p>If an image is bad (not loading, mostly illegible, etc.), please post
   about this bad image in the <a href="#forums">project discussion</a>.
</p>
<p>Note that some page images are quite large, and it is common for your browser to
   have difficulty displaying them, especially if you have several windows open or are
   using an older computer. Try closing some
   of your windows and programs to see if that helps, or post in the project
   discussion to see if anyone else has the same problem.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bad_text">Wrong Image for Text</a></h3>
<p>If there is a wrong image for the text given, please post about this bad page
   in the <a href="#forums">project discussion</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="round1">Previous Proofreading or Formatting Mistakes</a></h3>
<p>If a previous volunteer made a lot of mistakes or missed a lot of things,
   please take a moment and provide feedback to them by clicking on their name
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
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="p_errors">Printer Errors/Misspellings</a></h3>
<p>Correct all of the words that the OCR has misread (scannos), but do not correct
   what may appear to you to be misspellings or printer errors that occur on the page
   image. Many of the older texts have words spelled differently from modern usage and
   we retain these older spellings, including any accented characters.
</p>
<p>Place a note in the text next to a printer's erorr<kbd>[**typo for error?]</kbd>.
   If you are unsure whether it is actually an error, please also ask in the
   <a href="#forums">project discussion</a>. If you do make a change, include a note describing what you changed:
   <kbd>[**typo "erorr" fixed]</kbd>.
   Include the two asterisks <kbd>**</kbd> so the post-processor will notice it.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="f_errors">Factual Errors in Texts</a></h3>
<p>Do not correct factual errors in the author's book. Many of the books we
   are preparing have statements of fact in them that we no longer accept as accurate.
   Leave them as the author wrote them. See <a href="#p_errors">Printer Errors/Misspellings</a>
   for how to leave a note if you think the printed text is not what the author intended.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">Alphabetical Index to the Guidelines</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#about">About This Document</a></li>
        <li><a href="#play_n">Actor Names (Plays)</a></li>
        <li><a href="#word_caps">All Capitals</a></li>
        <li><a href="#font_ch">Antiqua Text</a></li>
        <li><a href="#anything">Anything else that needs special handling</a></li>
        <li><a href="#title_pg">Back Title Page</a></li>
        <li><a href="#bad_image">Bad Image</a></li>
        <li><a href="#bad_text">Bad Text</a></li>
        <li><a href="#blank_pg">Blank Page</a></li>
        <li><a href="#block_qt">Block Quotations</a></li>
        <li><a href="#bold">Bold Text</a></li>
        <li><a href="#word_caps">Capitals, All</a></li>
        <li><a href="#small_caps">Capitals, <span style="font-variant: small-caps">Small</span></a></li>
        <li><a href="#illust">Captions, Illustration</a></li>
        <li><a href="#font_ch">Changes in Font</a></li>
        <li><a href="#font_sz">Changes in Font Size</a></li>
        <li><a href="#chap_head">Chapter Headings</a></li>
        <li><a href="#prev_notes">Comments, Previous Volunteers'</a></li>
        <li><a href="#toc">Contents, Table of</a></li>
        <li><a href="#letter">Correspondence</a></li>
        <li><a href="#extra_s">Decorations Between Paragraphs</a></li>
        <li><a href="#maj_div">Divisions in Texts, Major</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#separate_pg">Each Page is a Separate Unit</a></li>
        <li><a href="#footnotes">Endnotes</a></li>
        <li><a href="#poetry">Epigrams</a></li>
        <li><a href="#f_errors">Errors, Factual</a></li>
        <li><a href="#p_errors">Errors, Printer</a></li>
        <li><a href="#extra_sp">Extra Spaces Between Words</a></li>
        <li><a href="#extra_s">Extra Spacing Between Paragraphs</a></li>
        <li><a href="#f_errors">Factual Errors in Texts</a></li>
        <li><a href="#prev_pg">Fixing Errors on Previous Pages</a></li>
        <li><a href="#font_ch">Font Changes</a></li>
        <li><a href="#font_sz">Font Size Changes</a></li>
        <li><a href="#footnotes">Footnotes</a></li>
        <li><a href="#separate_pg">Formatting Each Page Separately</a></li>
        <li><a href="#inline">Formatting Markup, Inline</a></li>
        <li><a href="#outofline">Formatting Markup, Out-of-Line</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#title_pg">Front/Back Title Page</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrt</span> (Spaced Out Text)</a></li>
        <li><a href="#summary">Handy Formatting Guide</a></li>
        <li><a href="#chap_head">Headings, Chapter</a></li>
        <li><a href="#maj_div">Headings, Other</a></li>
        <li><a href="#sect_head">Headings, Section</a></li>
        <li><a href="#extra_s">Horizontal Rules</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#maj_div">Illustrations, List of</a></li>
        <li><a href="#bad_image">Image, Bad</a></li>
        <li><a href="#para_space">Indenting, Paragraph</a></li>
        <li><a href="#bk_index">Indexes</a></li>
        <li><a href="#inline">Inline Formatting Markup, Placement of</a></li>
        <li><a href="#maj_div">Introduction</a></li>
        <li><a href="#italics">Italics</a></li>
        <li><a href="#letter">Letters/Correspondence</a></li>
        <li><a href="#extra_s">Line Between Paragraphs</a></li>
        <li><a href="#line_no">Line Numbers</a></li>
        <li><a href="#extra_s">Lines, Horizontal</a></li>
        <li><a href="#maj_div">List of Illustrations</a></li>
        <li><a href="#lists">Lists of Items</a></li>
        <li><a href="#subscr">Lowered Text (Subscripts)</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#maj_div">Major Divisions in Texts, Other</a></li>
        <li><a href="#p_errors">Misspellings, Printer</a></li>
        <li><a href="#round1">Mistakes, Previous Proofreading or Formatting</a></li>
        <li><a href="#prev_notes">Notes, Previous Volunteers'</a></li>
        <li><a href="#line_no">Numbers, Line</a></li>
        <li><a href="#maj_div">Other Major Divisions in Texts</a></li>
        <li><a href="#anything">Other things that you're unsure of</a></li>
        <li><a href="#outofline">Out-of-Line Formatting Markup</a></li>
        <li><a href="#blank_pg">Page, Blank</a></li>
        <li><a href="#page_ref">Page References "See p. 123"</a></li>
        <li><a href="#title_pg">Page, Title</a></li>
        <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
        <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
        <li><a href="#inline">Placement of Inline Formatting Markup</a></li>
        <li><a href="#outofline">Placement of Out-of-Line Formatting Markup</a></li>
        <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        <li><a href="#poetry">Poetry</a></li>
        <li><a href="#maj_div">Preface</a></li>
        <li><a href="#prev_pg">Previous Pages, Fixing Errors on</a></li>
        <li><a href="#round1">Previous Proofreading or Formatting Mistakes</a></li>
        <li><a href="#prev_notes">Previous Volunteers' Notes/Comments</a></li>
        <li><a href="#prime">Primary Rule</a></li>
        <li><a href="#p_errors">Printer Errors/Misspellings</a></li>
        <li><a href="#comments">Project Comments</a></li>
        <li><a href="#forums">Project Discussion</a></li>
        <li><a href="#block_qt">Quotations, Block</a></li>
        <li><a href="#supers">Raised Text (Superscripts)</a></li>
        <li><a href="#r_align">Right-aligned Text</a></li>
        <li><a href="#extra_s">Rules, Horizontal</a></li>
        <li><a href="#sect_head">Section Headings</a></li>
        <li><a href="#page_ref">"See p. 123" (Page References)</a></li>
        <li><a href="#para_side">Sidenotes</a></li>
        <li><a href="#font_sz">Size Changes, Font</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#extra_s">Space Between Paragraphs, Extra</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Spaced Out Text</span> (gesperrt)</a></li>
        <li><a href="#extra_sp">Spaces, Extra</a></li>
        <li><a href="#para_space">Spacing, Paragraph</a></li>
        <li><a href="#play_n">Stage Directions (Plays)</a></li>
        <li><a href="#extra_s">Stars Between Paragraphs</a></li>
        <li><a href="#subscr">Subscripts</a></li>
        <li><a href="#summary">Summary Guidelines</a></li>
        <li><a href="#supers">Superscripts</a></li>
        <li><a href="#extra_sp">Tabs</a></li>
        <li><a href="#toc">Table of Contents</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#r_align">Text, Right-aligned</a></li>
        <li><a href="#bad_text">Text, Wrong Image for</a></li>
        <li><a href="#extra_s">Thought Breaks</a></li>
        <li><a href="#title_pg">Title Page</a></li>
        <li><a href="#chap_head">Titles, Chapter</a></li>
        <li><a href="#sect_head">Titles, Section</a></li>
        <li><a href="#underl">Underlined Text</a></li>
        <li><a href="#word_caps">Words in All Capitals</a></li>
        <li><a href="#small_caps">Words in <span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#bad_text">Wrong Image for Text</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
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
