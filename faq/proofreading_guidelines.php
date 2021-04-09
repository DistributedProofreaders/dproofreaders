<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("en");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Proofreading Guidelines', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Proofreading Guidelines</a></h1>

<h3 align="center">Version 2.0, revised June 7, 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<p>Proofreading Guidelines <a href="fr/proofreading_guidelines.php">in French</a> /
      Directives de Relecture et Correction <a href="fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br>
    Proofreading Guidelines <a href="pt/proofreading_guidelines.php">in Portuguese</a> /
      Regras de Revis&atilde;o <a href="pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br>
    Proofreading Guidelines <a href="es/proofreading_guidelines.php">in Spanish</a> /
      Reglas de Revisi&oacute;n <a href="es/proofreading_guidelines.php">en espa&ntilde;ol</a><br>
    Proofreading Guidelines <a href="nl/proofreading_guidelines.php">in Dutch</a> /
      Proeflees-Richtlijnen <a href="nl/proofreading_guidelines.php">in het Nederlands</a><br>
    Proofreading Guidelines <a href="de/proofreading_guidelines.php">in German</a> /
      Korrekturlese-Richtlinien <a href="de/proofreading_guidelines.php">auf Deutsch</a><br>
    Proofreading Guidelines <a href="it/proofreading_guidelines.php">in Italian</a> /
      Regole di Correzione <a href="it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Check out the <a href="../quiz/start.php?show_only=PQ">Proofreading Quiz and Tutorial</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Table of Contents</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">The Primary Rule</a></li>
        <li><a href="#summary">Summary Guidelines</a></li>
        <li><a href="#about">About This Document</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Proofreading at the Character Level:</font>
        <ul>
          <li><a href="#double_q">Double Quotes</a></li>
          <li><a href="#single_q">Single Quotes</a></li>
          <li><a href="#quote_ea">Quote Marks on Each Line</a></li>
          <li><a href="#period_s">End-of-sentence Periods</a></li>
          <li><a href="#punctuat">Punctuation Spacing</a></li>
          <li><a href="#extra_sp">Extra Spaces or Tabs Between Words</a></li>
          <li><a href="#trail_s">Trailing Space at End-of-line</a></li>
          <li><a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a></li>
          <li><a href="#eol_hyphen">End-of-line Hyphenation and Dashes</a></li>
          <li><a href="#eop_hyphen">End-of-page Hyphenation and Dashes</a></li>
          <li><a href="#period_p">Period Pause &quot;...&quot; (Ellipsis)</a></li>
          <li><a href="#contract">Contractions</a></li>
          <li><a href="#fract_s">Fractions</a></li>
          <li><a href="#a_chars">Accented/Non-ASCII Characters</a></li>
          <li><a href="#d_chars">Characters with Diacritical Marks</a></li>
          <li><a href="#f_chars">Non-Latin Characters</a></li>
          <li><a href="#supers">Superscripts</a></li>
          <li><a href="#subscr">Subscripts</a></li>
          <li><a href="#drop_caps">Large, Ornate Opening Capital Letter (Drop Cap)</a></li>
          <li><a href="#small_caps">Words in Small Capitals</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Proofreading at the Paragraph Level:</font>
        <ul>
          <li><a href="#line_br">Line Breaks</a></li>
          <li><a href="#chap_head">Chapter Headings</a></li>
          <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
          <li><a href="#page_hf">Page Headers/Page Footers</a></li>
          <li><a href="#illust">Illustrations</a></li>
          <li><a href="#footnotes">Footnotes/Endnotes</a></li>
          <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
          <li><a href="#mult_col">Multiple Columns</a></li>
          <li><a href="#tables">Tables</a></li>
          <li><a href="#poetry">Poetry/Epigrams</a></li>
          <li><a href="#line_no">Line Numbers</a></li>
          <li><a href="#next_word">Single Word at Bottom of Page</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Proofreading at the Page Level:</font>
        <ul>
          <li><a href="#blank_pg">Blank Page</a></li>
          <li><a href="#title_pg">Front/Back Title Page</a></li>
          <li><a href="#toc">Table of Contents</a></li>
          <li><a href="#bk_index">Indexes</a></li>
          <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        </ul></li>
        <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a></li>
        <li><a href="#prev_notes">Previous Proofreaders' Notes/Comments</a></li>
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
          <li><a href="#formatting">Formatting</a></li>
          <li><a href="#common_OCR">Common OCR Problems</a></li>
          <li><a href="#OCR_scanno">OCR Problems: Scannos</a></li>
          <li><a href="#OCR_raised_o">OCR Problems: Is that &deg; &ordm; really a degree sign?</a></li>
          <li><a href="#hand_notes">Handwritten Notes in Book</a></li>
          <li><a href="#bad_image">Bad Image</a></li>
          <li><a href="#bad_text">Wrong Image for Text</a></li>
          <li><a href="#round1">Previous Proofreader Mistakes</a></li>
          <li><a href="#p_errors">Printer Errors/Misspellings</a></li>
          <li><a href="#f_errors">Factual Errors in Texts</a></li>
          <li><a href="#insert_char">Inserting Special Characters</a></li>
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
   wrote outrageous racist or biased statements, we leave them that way. If the author put commas, superscripts,
   or footnotes every third word, we keep the commas, superscripts, or footnotes. We are proofreaders,
   <b>not</b> editors; if something in the text does not match the original page image, you should change
   the text so that it does match. (See <a href="#p_errors">Printer's Errors</a> for proper handling of
   obvious misprints.)
</p>
<p>We do change minor typographical conventions that don't affect the sense of what the author wrote.
   For example, we rejoin words that were broken at the end of a line (<a href="#eol_hyphen">End-of-line Hyphenation</a>).
   Changes such as these help us produce a <em>consistently formed</em> version of the book.
   The proofreading rules we follow are designed to achieve this result. Please carefully read the rest of the
   Proofreading Guidelines with this concept in mind. These guidelines are
   intended for proofreading <i>only</i>.
   As a proofreader you are matching the image's content while later the formatters will match the image's look.
</p>
<p>To assist the next proofreader, the formatters, and the post-processor, we also preserve <a href="#line_br">line breaks</a>.
   This allows them to easily compare the lines in the text to the lines in the image.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="summary">Summary Guidelines</a></h3>
<p>The <a href="proofing_summary.pdf">Proofreading Summary</a> is a short, 2-page
   printer-friendly (.pdf) document that summarizes the main points of these
   Guidelines and gives examples of how to proofread. Beginning proofreaders are
   encouraged to print out this document and keep it handy while proofreading.
</p>
<p>You may need to download and install a .pdf reader. You can get one free from Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">here</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="about">About This Document</a></h3>
<p>This document is written to explain the proofreading rules we use to maintain consistency when proofreading
   a single book that is distributed among many proofreaders, each of whom is working on different pages.
   This helps us all do proofreading <em>the same way</em>, which in turn makes it
   easier for the formatters and for the post-processor who will complete the work on this e-book.
</p>
<p><i>It is not intended as any kind of a general editorial or typesetting rulebook</i>.
</p>
<p>We've included in these proofreading guidelines all the items that new users have asked about
   while proofreading. There is a separate set of <a href="formatting_guidelines.php">Formatting
   Guidelines</a>. A second group of volunteers will be working on the formatting of the text.
   If you come across a situation and you do not find a reference in these
   guidelines, it is likely that it will be handled in the formatting rounds and so is not
   mentioned here. If you aren't sure, please ask about it in the <a href="#forums">Project Discussion</a>.
</p>
<p>If there are any items missing, or items that you
   consider should be done differently, or if something is vague, please let us know.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   If you come across an unfamiliar term in these guidelines, see the
   <a href="http://www.pgdp.net/wiki/DP_Jargon">wiki jargon guide</a>.
<?php } ?>
   This document is a work in progress. Help us to improve it by posting your suggested changes in the
   Documentation Forum in <a href="<?php echo $Guideline_discussion_URL; ?>">this thread</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="comments">Project Comments</a></h3>
<p>When you select a project for proofreading, the Project Page is loaded. On this page there is a section called
   "Project Comments" containing information specific to that project (book). <b>Read these
   before you start proofreading pages!</b> If the Project Manager wants you to do
   something in this book differently from the way specified in these Guidelines, that
   will be noted here. Instructions in the Project Comments <em>override</em> the rules
   in these Guidelines, so follow them. There may also be instructions in the project
   comments that apply to the formatting phase, which do not apply during proofreading. Finally,
   this is also where the Project Manager may give
   you interesting tidbits of information about the author or the project.
</p>
<p><em>Please also read the Project Thread (discussion)</em>: The Project Manager may clarify project-specific
   guidelines here, and it is often used by proofreaders to alert other proofreaders to recurring
   issues within the project and how they can best be addressed. (See below.)
</p>
<p>On the Project Page, the link 'Images, Pages Proofread, &amp; Differences' allows you to
   see how other proofreaders have made changes.
   <a href="<?php echo $Using_project_details_URL; ?>">This forum thread</a>
   discusses different ways to use this information.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="forums">Forum/Discuss This Project</a></h3>
<p>On the Project Page where you start proofreading pages, on the line "Forum", there is
   a link titled "Discuss this Project" (if the discussion has already started), or "Start
   a discussion on this Project" (if it hasn't). Clicking on that link will take you to a
   thread in the projects forum dedicated to this specific project. That is the place to ask
   questions about this book, inform the Project Manager about problems, etc. Using this project
   forum thread is the recommended way to communicate with the Project Manager and other
   proofreaders who are working on this book.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="prev_pg">Fixing Errors on Previous Pages</a></h3>
<p>The <a href="#comments">Project Page</a> contains links to pages from this project that you have
   recently proofread. (If you haven't proofread any pages yet, no links will be
   shown.)
</p>
<p>Pages listed under either "DONE" or "IN PROGRESS" are available to make proofreading
   corrections or to finish proofreading. Just click on the link to the page. Thus, if you
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


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proofreading at the Character Level:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Double Quotes</a></h3>
<p>Proofread &ldquo;double quotes&rdquo; as plain ASCII <kbd>"</kbd> double quotes. Do not change
   double quotes to single quotes. Leave them as the author wrote them.
   See <a href="#chap_head">Chapter Headings</a> if a double quote is missing at the start of a chapter.
</p>
<p>For quotation marks other than <kbd>"</kbd>, use the same marks that
   appear in the image if they are available. The
   French equivalent, guillemets&nbsp; <kbd>&laquo;like this&raquo;</kbd>, are available
   from the pulldown menus in the proofreading interface, since they are part of
   Latin-1. Remember to remove space between the quotation marks and the quoted text;
   if needed, it will be added in post-processing. The same applies to languages
   which use reversed guillemets,&nbsp; <kbd>&raquo;like this&laquo;</kbd>.
</p>
<p>The quotation marks used in some texts (in German or other languages)&nbsp; <kbd>&bdquo;like this&ldquo;</kbd>&nbsp;
   are also available in the pulldown menus; for the sake of simplicity, you should always
   use&nbsp; <kbd>&bdquo;</kbd>&nbsp; and&nbsp; <kbd>&ldquo;</kbd>&nbsp; regardless of the actual quotes used in the original
   text, as long as the quotes used in the original text are clearly lower and upper. If needed,
   the quotes will be changed to ones used in the text in post-processing.
</p>
<p>The Project Manager may instruct you in the <a href="#comments">Project Comments</a>
   to proofread non-English language quotation marks differently for a particular book.
   Please be sure not to apply those directions to other projects.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="single_q">Single Quotes</a></h3>
<p>Proofread these as the plain ASCII <kbd>'</kbd> single quote (apostrophe). Do not
   change single quotes to double quotes. Leave them as the author wrote them.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="quote_ea">Quote Marks on Each Line</a></h3>
<p>Proofread quotation marks at the beginning of each line of a quotation by removing
   all of them <b>except for</b> the one at the start of the quotation.
   If a quotation like this goes on for multiple paragraphs, leave the quote mark
   that appears on the first line of each paragraph.
</p>
<p>However, in poetry keep the extra quote marks where they appear in the image, since
   the line breaks will not be changed.
</p>
<p>Often there is no closing quotation mark until the very end of the quoted section of text,
   which may not be on the same page you are proofreading. Leave it that way&mdash;do not
   add closing quotation marks that are not in the page image.
</p>
<p>There are some language-specific exceptions. In French, for example, dialog within quotations
   uses a combination of different punctuation to indicate various speakers. If you are not familiar
   with a particular language, check the <a href="#comments">Project Comments</a> or leave a
   message for the Project Manager in the Project Discussion for clarification.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr>
      <td valign="top">
        Clearly he wasn't an academic with a preface like this<br>
        one. &ldquo;I do not give the name of the play, act or scene,<br>
        &ldquo;in head or foot lines, in my numerous quotations from<br>
        &ldquo;Shakspere, designedly leaving the reader to trace and<br>
        &ldquo;find for himself a liberal education by studying the<br>
        &ldquo;wisdom of the Divine Bard.<br>
        <br>
        &ldquo;There are many things in this volume that the ordinary<br>
        &ldquo;mind will not understand, yet I only contract with the<br>
        &ldquo;present and future generations to give rare and rich<br>
        &ldquo;food for thought, and cannot undertake to furnish the<br>
        &ldquo;reader brains with each book!&rdquo;
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <kbd>
        Clearly he wasn't an academic with a preface like this<br>
        one. "I do not give the name of the play, act or scene,<br>
        in head or foot lines, in my numerous quotations from<br>
        Shakspere, designedly leaving the reader to trace and<br>
        find for himself a liberal education by studying the<br>
        wisdom of the Divine Bard.<br>
        <br>
        "There are many things in this volume that the ordinary<br>
        mind will not understand, yet I only contract with the<br>
        present and future generations to give rare and rich<br>
        food for thought, and cannot undertake to furnish the<br>
        reader brains with each book!"
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="period_s">End-of-sentence Periods</a></h3>
<p>Proofread periods that end sentences with a single space after them.
</p>
<p>You do not need to remove extra spaces after periods if they're already in the OCR'd
   text&mdash;we can do that automatically during post-processing.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="punctuat">Punctuation Spacing</a></h3>
<p>Spaces before punctuation sometimes appear because books typeset in the 1700's &amp; 1800's
   often used partial spaces before punctuation such as a semicolon or colon.
</p>
<p>In general, a punctuation mark should have a space after it but no space before it. If the OCR'd text has
   no space after a punctuation mark, add one; if there is a space before punctuation, remove it. This applies even to
   languages such as French that normally use spaces before punctuation characters.
   However, punctuation marks that normally appear in pairs, such as "quotation marks",
   (parentheses), [brackets], and {braces} normally have a space before the opening mark, which should be retained.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Punctuation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><kbd>and so it goes; ever and ever.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="extra_sp">Extra Spaces or Tabs Between Words</a></h3>
<p>Extra spaces between words are common in OCR output. You don't need to bother
   removing these&mdash;that can be done automatically during post-processing.
   However, extra spaces around punctuation, em-dashes, quote marks, etc. <b>do</b> need to be
   removed when they separate the symbol from the word.
</p>
<p>For example, in <kbd>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</kbd> the space between
   the word "horse" and the semicolon should be removed. But the 2 spaces after the semicolon are
   fine&mdash;you don't have to delete one of them.
</p>
<p>In addition, if you find any tab characters in the text you should remove them.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="trail_s">Trailing Space at End-of-line</a></h3>
<p>Do not bother inserting spaces at the ends of lines of text; any such spaces will automatically
   be removed from the text when you save the page. When the text is post-processed, each end-of-line
   will be converted into a space.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="em_dashes">Dashes, Hyphens, and Minus Signs</a></h3>
<p>There are generally four such marks you will see in books:
</p>
  <ol compact>
    <li><i>Hyphens</i>. These are used to <b>join</b> words together, or sometimes to
        join prefixes or suffixes to a word.
    <br>Leave these as a single hyphen, with no spaces on either side.
        Note that there is a common exception to this shown in the second example below.
    </li>
    <li><i>En-dashes</i>. These are just a little longer, and are used for a
        <b>range</b> of numbers, or for a mathematical <b>minus</b> sign.
    <br>Proofread these as a single hyphen, too. Spaces before or after are determined by the
        way it was done in the book; usually no spaces in number ranges, usually spaces
        around mathematical minus signs, sometimes both sides, sometimes just before.
    </li>
    <li><i>Em-dashes &amp; long dashes</i>. These serve as <b>separators</b> between
        words&mdash;sometimes for emphasis like this&mdash;or when a speaker gets a word caught in
        his throat&mdash;&mdash;!
    <br>Proofread these as two hyphens if the dash is as long as 2-3 letters (an <i>em-dash</i>)
        and four hyphens if the dash is as long as 4-5 letters (a <i>long dash</i>). Don't leave a space before or after,
        even if it looks like there was a space in the original book image.
    </li>
    <li><i>Deliberately Omitted or Censored Words or Names</i>.
    <br>If represented by a dash in the image, proofread these as two hyphens
        or four hyphens as described for em-dashes &amp; long dashes.
        When it represents a word, we leave appropriate space
        around it like it's really a word. If it's only part of a word, then no
        spaces&mdash;join it with the rest of the word.
    </li>
  </ol>
<p>See also the guidelines for <a href="#eol_hyphen">end-of-line</a> and
   <a href="#eop_hyphen">end-of-page</a> hyphens and dashes.
</p>
<!-- END RR -->

<p><b>Examples</b>&mdash;Dashes, Hyphens, and Minus Signs:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Image:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Proofread Text:</th>
      <th valign="top" bgcolor="cornsilk">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><kbd>semi-detached</kbd></td>
      <td>Hyphen</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><kbd>three- and four-part harmony</kbd></td>
      <td>Hyphens</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><kbd>discoveries which the Crusaders<br>
        made and brought home with</kbd></td>
      <td>Hyphen</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><kbd>factors which mold character--environment,<br>
        training and heritage,</kbd></td>
      <td>Hyphen &amp; Em-dash</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><kbd>See pages 21-25</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><kbd>It was -14&deg;C outside.</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><kbd>X - Y = Z</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><kbd>2-1/2</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><kbd>--A plague on both<br> your houses!--I am dead.</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><kbd>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><kbd>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><kbd>It is the east, and Juliet is the sun--!</kbd></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top"><img src="dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><kbd>how a--a--cannon-ball goes----"</kbd></td>
      <td>Em-dashes, Hyphen,<br> &amp; Long Dash</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><kbd>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</kbd></td>
      <td>Long Dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. ---- testified,</kbd></td>
      <td>Long Dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. S---- testified,</kbd></td>
      <td>Long Dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><kbd>the famous detective of ----B Baker St.</kbd></td>
      <td>Long Dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><kbd>"You ---- Yankee", she yelled.</kbd></td>
      <td>Long Dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><kbd>"I am not a d--d Yankee", he replied.</kbd></td>
      <td>Em-dash</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="eol_hyphen">End-of-line Hyphenation and Dashes</a></h3>
<p>Where a hyphen appears at the end of a line, join the two halves of the hyphenated
   word back together. Remove the hyphen when you join it, unless
   it is really a hyphenated word like well-meaning. See <a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a>
   for examples of each kind. Keep the joined word on the top line, and put
   a line break after it to preserve the line formatting&mdash;this makes it easier for
   volunteers in later rounds. If the word is followed
   by punctuation, then carry that punctuation onto the top line, too.
</p>
<p>Words like to-day and to-morrow that we don't commonly hyphenate now were often
   hyphenated in the old books we are working on. Leave them hyphenated the way the
   author did. If you're not sure if the author hyphenated it or not, leave the hyphen,
   put an <kbd>*</kbd> after it, and join the word together like this:
   <kbd>to-*day</kbd>. The asterisk will bring it to the attention of the post-processor,
   who has access to all the pages and can determine how the author
   typically wrote this word.
</p>
<p>Similarly, if an em-dash appears at the start or end of a line of your OCR'd text, join it with the
   other line so that there are no spaces or line breaks around it. However, if the author
   used an em-dash to start or end a paragraph or a line of poetry, you should leave it as it is,
   without joining it to the next line. See <a href="#em_dashes">Dashes,
   Hyphens, and Minus Signs</a> for examples.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="eop_hyphen">End-of-page Hyphenation and Dashes</a></h3>
<p>Proofread end-of-page hyphens or em-dashes by leaving the hyphen or em-dash at the end
   of the last line, and mark it with a <kbd>*</kbd> after the hyphen or dash. For example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="End-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><kbd>something Pat had already become accus-*</kbd></td>
    </tr>
  </tbody>
</table>
<p>On pages that start with part of a word from the previous page or an em-dash,
   place a <kbd>*</kbd> before the partial word or em-dash.
   To continue the above example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><kbd>*tomed to from having to do his own family</kbd></td>
    </tr>
  </tbody>
</table>
<p>These markings indicate to the post-processor that the word must be rejoined when the pages are
   combined to produce the final e-book. Please do not join the fragments across the pages yourself.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="period_p">Period Pause &quot;...&quot; (Ellipsis)</a></h3>
<p>The guidelines are different for English and Languages Other Than English (LOTE).
</p>
<p><b>ENGLISH</b>: An ellipsis should have three dots.  Regarding the spacing, in the middle of a
   sentence treat the three dots as a single word (i.e., usually a space before the 3 dots and a
   space after). At the end of a sentence treat the ellipsis as ending punctuation, with no space
   before it.
</p>
<p>Note that there will also be an ending punctuation mark at the end of a sentence,
   so in the case of a period there will be 4 dots total.
   Remove extra dots, if any, or add new ones, if necessary,
   to bring the number to three (or four) as appropriate.
   A good hint that you're at the end of a sentence is the use of a capital
   letter at the start of the next word, or the presence of an ending punctuation mark
   (e.g., a question mark or exclamation point).
</p>
<p><b>LOTE:</b> (Languages Other Than English) Use the general rule "Follow closely the style
   used in the printed page." In particular, insert spaces, if there are spaces before or
   between the periods, and use the same number of periods as appear in the image. Sometimes
   the printed page is unclear; in that case, insert a <kbd>[**unclear]</kbd> to draw the
   attention of the post-processor.
   (Note: Post-processors should replace those regular spaces with non-breaking spaces.)
</p>
<!-- END RR -->
<p>English examples:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Ellipses examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Original Image:</th>
      <th valign="top" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td valign="top">That I know .&nbsp;.&nbsp;. is true.</td>
      <td valign="top"><kbd>That I know ... is true.</kbd></td>
    </tr>
    <tr>
      <td valign="top">This is the end....</td>
      <td valign="top"><kbd>This is the end....</kbd></td>
    </tr>
    <tr>
      <td valign="top">The moving finger writes; and.&nbsp;.&nbsp;. The poet<br> surely had a pen though!</td>
      <td valign="top"><kbd>The moving finger writes; and.... The poet<br> surely had a pen though! </kbd></td>
    </tr>
    <tr>
      <td valign="top">Wherefore art thou Romeo.&nbsp;.&nbsp;.&nbsp;?</td>
      <td valign="top"><kbd>Wherefore art thou Romeo...?</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I went to the store,&nbsp;.&nbsp;.&nbsp;.&rdquo; said Harry.</td>
      <td valign="top"><kbd>"I went to the store, ..." said Harry.</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;... And I did too!&rdquo; said Sally.</td>
      <td valign="top"><kbd>"... And I did too!" said Sally.</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;Really?&nbsp;&nbsp;.&nbsp;.&nbsp;. Oh, Harry!&rdquo;</td>
      <td valign="top"><kbd>"Really?... Oh, Harry!"</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="contract">Contractions</a></h3>
<p>In English, remove any extra space in contractions. For example, <kbd>would&nbsp;n't</kbd> should
   be proofread as <kbd>wouldn't</kbd> and <kbd>'t&nbsp;is</kbd> as <kbd>'tis</kbd>.
</p>
<p>This was a 19th century printers' convention in which the space was retained
   to indicate that 'would' and 'not' were originally separate words. It is
   also sometimes an artifact of the OCR. Remove the extra space in either case.
</p>
<p>Some Project Managers may specify in the <a href="#comments">Project Comments</a>
   not to remove extra spaces in contractions, particularly in the case of books that
   contain slang, dialect, or poetry.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="fract_s">Fractions</a></h3>
<p>Proofread fractions as follows: <kbd>&frac14;</kbd> becomes <kbd>1/4</kbd>,
   and <kbd>2&frac12;</kbd> becomes <kbd>2-1/2</kbd>.
   The hyphen prevents the whole and fractional part from becoming
   separated when the lines are rewrapped during post-processing.
   Unless specifically requested in the <a href="#comments">Project Comments</a>,
   please do not use the actual fraction symbols.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="a_chars">Accented/Non-ASCII Characters</a></h3>
<p>Please proofread these using the proper UTF-8 characters. For characters which are not in Unicode, see
   the Project Manager's instructions in the <a href="#comments">Project Comments</a>.
   If they are not on your keyboard, see <a href="#insert_char">Inserting Special Characters</a>
   for information on how to input these characters during proofreading.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="d_chars">Characters with Diacritical Marks</a></h3>
<p>In some projects, you will find characters with special marks either above or below
   the normal Latin A...Z character. These are called <i>diacritical marks</i>, and
   indicate a special pronunciation for this character.
</p>
<p>If such a character does not exist in Unicode, it should be entered by using
   <i>combining diacritical marks</i>: these are Unicode symbols which can't
   appear alone, but appear above (or below) the letter after which they are
   placed. They could be entered by first entering the base letter, and then
   the combining mark, using applets and programs mentioned in
   <a href="#insert_char">Inserting Special Characters</a>.
</p>
<p>On some systems, diacritical marks may not appear exactly where they should,
   but, for example, moved to the right. They should still be used, as people
   with other systems will see them correctly. However, if, for any reason, you
   can't see or enter combining marks properly, mark such letter with a [**note].
   Note that <i>Spacing modifier letters</i> also exist; these
   should not be used.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="f_chars">Non-Latin Characters</a></h3>
<p>Some projects contain text printed in non-Latin characters; that is, characters
   other than the Latin A...Z&mdash;for example, Greek, Cyrillic (used in
   Russian, Slavic, and other languages), Hebrew, or Arabic characters.
</p>
<p>These characters should be entered in the text just as Latin characters are.
   (<b>WITHOUT transliteration!</b>)
</p>
<p>If a document is written entirely in a non-Latin script, it is best to install a
   keyboard driver which supports the language. Consult your operating system manual for
   instructions on how to do that.
</p>
<p>If the script appears only occasionally, you may use a separate program to enter it.
   See <a href="#insert_char">Inserting Special Characters</a> for some of the programs.
</p>
<p>If you are uncertain about a character or an accent, mark it with a [**note] to
   bring it to the attention of the next proofreader or the post-processor.
</p>
<p>For scripts which cannot be so easily entered, such as Arabic, identify it
   with the appropriate mark: <kbd>[Arabic:&nbsp;**]</kbd>.
   Include the <kbd>**</kbd> so the post-processor can address it later.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="supers">Superscripts</a></h3>
<p>Older books often abbreviated words as contractions, and printed them as
   superscripts. Proofread these by inserting a single caret (<kbd>^</kbd>) followed by the
   superscripted text. If the superscript continues for more than one character,
   then surround the text with curly braces <kbd>{</kbd> and <kbd>}</kbd> as well. For example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
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


<h3><a name="subscr">Subscripts</a></h3>
<p>Subscripted text is often found in scientific works, but is not common in other
   material. Proofread subscripted text by inserting an underline character <kbd>_</kbd> and
   surrounding the text with curly braces <kbd>{</kbd> and <kbd>}</kbd>. For example:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="drop_caps">Large, Ornate Opening Capital Letter (Drop Cap)</a></h3>
<p>Proofread a large and ornate graphic first letter of a chapter, section, or paragraph
   as if it were an ordinary letter. See also the <a href="#chap_head">Chapter Headings</a>
   section of the Proofreading Guidelines.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="small_caps">Words in Small Capitals</a></h3>
<p>Please proofread only the characters in <span style="font-variant: small-caps">Small Caps</span>
   (capital letters which are smaller than the standard capitals).
   Do not worry about case changes.
   If the OCR'd text is already ALL-CAPPED, Mixed-Cased, or lower-cased,
   leave it ALL-CAPPED, Mixed-Cased, or lower-cased.
   Small caps may occasionally appear with <kbd>&lt;sc&gt;</kbd> and <kbd>&lt;/sc&gt;</kbd> around
   it; see <a href="#formatting">Formatting</a> in that case.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proofreading at the Paragraph Level:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Line Breaks</a></h3>
<p><b>Leave all line breaks in</b> so that later in the process other volunteers can easily compare
   the lines in the text to the lines in the image. Be especially careful about this
   when rejoining <a href="#eol_hyphen">hyphenated words</a> or moving words around
   <a href="#em_dashes">em-dashes</a>. If the previous proofreader removed the line breaks,
   please replace them so that they once again match the image.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="chap_head">Chapter Headings</a></h3>
<p>Proofread chapter headings as they appear in the image.
</p>
<p>A chapter heading may start a bit farther down the page than the <a href="#page_hf">page header</a>
   and won't have a page number on the same line. Chapter Headings are often printed all caps; if so,
   keep them as all caps.
</p>
<p>Watch out for a missing double quote at the start of the first paragraph, which some
   publishers did not include or which the OCR missed due to a large capital in the
   image. If the author started the paragraph with dialog, insert the double quote.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="para_space">Paragraph Spacing/Indenting</a></h3>
<p>Put a blank line before the start of a paragraph, even if it starts at the top of a page.
   You should not indent the start of the paragraph, but if it is already indented don't
   bother removing those spaces&mdash;that can be done automatically during post-processing.
</p>
<p>See the <a href="#para_side">Sidenotes</a> image/text for an example.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="page_hf">Page Headers/Page Footers</a></h3>
<p>Remove page headers and page footers, but <em>not</em> <a href="#footnotes">footnotes</a>,
   from the text.
</p>
<p>The page headers are normally at the top of the image and have a page
   number opposite them. Page headers may be the same all through the book (often the
   title of the book and the author's name), they may be the same for each chapter
   (often the chapter number), or they may be different on each page (describing the
   action on that page). Remove them all, regardless, including the page number.
   Extra blank lines should be removed except where we intentionally
   add them for proofreading. But blank lines at the bottom of the page are fine&mdash;these are removed
   when you save the page.
</p>
<p>Page footers are at the bottom of the image and may contain a page number
   or other extraneous marks that are not part of what the author wrote.
</p>
<!-- END RR -->

<p>A <a href="#chap_head">chapter heading</a> will usually start further down the page and won't
   have a page number on the same line. See the example below.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>In the United States?[*] In a railroad? In a mining company?<br>
        In a bank? In a church? In a college?<br>
        <br>
        Write a list of all the corporations that you know or have<br>
        ever heard of, grouping them under the heads public and private.<br>
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
        CHAPTER XXXV.<br>
        <br>
        Commercial Paper.<br>
        <br>
        Kinds and Uses.--If a man wishes to buy some commodity<br>
        from another but has not the money to pay for<br>
        it, he may secure what he wants by giving his written<br>
        promise to pay at some future time. This written<br>
        promise, or note, the seller prefers to an oral promise<br>
        for several reasons, only two of which need be mentioned<br>
        here: first, because it is prima facie evidence of<br>
        the debt; and, second, because it may be more easily<br>
        transferred or handed over to some one else.<br>
        <br>
        If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
        of Chicago, a hundred dollars, and Nelson Blake, of<br>
        Chicago, owes J. M. Johnson a hundred dollars, it is<br>
        plain that the risk, expense, time and trouble of sending<br>
        the money to and from Chicago may be avoided,<br>
        <br>
        * The United States: "Its charter, the constitution. * * * Its flag the<br>
        symbol of its power; its seal, of its authority."--Dole.
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="illust">Illustrations</a></h3>
<p>Ignore illustrations, but proofread any caption text as it is printed, preserving
   the line breaks. If the caption falls in the middle of a paragraph, use blank lines to set it apart from
   the rest of the text. Text that could be (part of) a caption should be included, such as
   "See page 66" or a title within the bounds of the illustration.
</p>
<p>Most pages with an illustration but no text will already be
   marked with <kbd>[Blank Page]</kbd>. Leave this marking as is.
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        Frontispiece<br>
        Her Weight in Gold
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        such study are due to Italians. Several of these instruments<br>
        have already been described in this journal, and on the present<br>
        </kbd></p>
        <p><kbd>FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
        SEISMIC MOVEMENTS.</kbd></p>
        <p><kbd>
        occasion we shall make known a few others that will<br>
        serve to give an idea of the methods employed.<br>
        </kbd></p>
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
<p>Proofread footnotes by leaving the text of the footnote
   at the bottom of the page and placing a tag where it is referenced in the text.
</p>
<p>In the main text, the character that marks a footnote location should
   be surrounded with square brackets (<kbd>[</kbd> and <kbd>]</kbd>) and
   placed right next to the word being footnoted<kbd>[1]</kbd> or its
   punctuation mark,<kbd>[2]</kbd> as shown in the image and the two examples in this sentence.
   Footnote markers may be numbers, letters, or symbols.
   When footnotes are marked with a symbol or a series of symbols (*, &dagger;, &Dagger;, &sect;,
   etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself.
</p>
<p>At the bottom of the page, proofread the footnote text as it is printed, preserving the line breaks.
   Be sure to use the same tag before the footnote as
   you used in the text where the footnote was referenced. Use just the character itself
   for the tag, without any brackets or other punctuation.
</p>
<p>Place each footnote on a separate line in order of appearance, with a blank line before each
   one.
</p>
<!-- END RR -->
<p>Do not include any horizontal lines separating the footnotes from the main text.
</p>
<p><b>Endnotes</b> are just footnotes that have been located together at the end of a
   chapter or at the end of the book, instead of on the bottom of each page. These
   are proofread in the same manner as footnotes. Where you find an
   endnote reference in the text, just surround it with <kbd>[</kbd> and <kbd>]</kbd>.
   If you are proofreading one of the pages with endnotes,
   put a blank line before each endnote so that it is clear where each begins and ends.
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
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <kbd>The principal persons involved in this argument were Caesar[*], former military</kbd><br>
        <kbd>leader and Imperator, and the orator Cicero[*]. Both were of the aristocratic</kbd><br>
        <kbd>(Patrician) class, and were quite wealthy.</kbd><br>
        <br>
        <kbd>* Gaius Julius Caesar.</kbd><br>
        <br>
        <kbd>* Marcus Tullius Cicero.</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote Example">
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top">
        <kbd>
        Mary had a little lamb[1]<br>
        Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        The lamb was sure to go!<br>
        <br>
        1 This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.<br>
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="para_side">Paragraph Side-Descriptions (Sidenotes)</a></h3>
<p>Some books will have short descriptions of the paragraph along the side of the text.
   These are called sidenotes. Proofread the sidenote text as it is printed,
   preserving the line breaks (while handling <a href="#eol_hyphen">end-of-line hyphenation
   and dashes</a> normally). Leave a blank line before and after the sidenote so that it
   can be distinguished from the text around it. The OCR may place the sidenotes anywhere
   on the page, and may even intermingle the sidenote text with the rest of the text.
   Separate them so that the sidenote text is all together, but don't worry about the
   position of the sidenotes on the page.
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
        <p><kbd>
        Burning<br>
        discs<br>
        thrown into<br>
        the air.<br>
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
        (J. W. Wolf, Beitr&auml;ge zur deutschen<br>
        Mythologie, i. p. 217, &sect; 185).<br>
        <br>
        2 J. Boemus, Mores, leges et ritus<br>
        omnium gentium (Lyons, 1541), p.<br>
        226.<br>
        <br>
        3 Karl Freiherr von Leoprechting,<br>
        Aus dem Lechrain (Munich, 1855),<br>
        pp. 181 sqq.; W. Mannhardt, Der<br>
        Baumkultus, p. 510.<br>
        <br>
        4 A. Birlinger, Volksth&uuml;mliches aus<br>
        Schwaben (Freiburg im Breisgau, 1861-1862),<br>
        ii. pp. 96 sqq., &sect; 128, pp. 103<br>
        sq., &sect; 129; id., Aus Schwaben (Wiesbaden,<br>
        1874), ii. 116-120; E. Meier,<br>
        Deutsche Sagen, Sitten und Gebr&auml;uche<br>
        aus Schwaben (Stuttgart, 1852), pp.<br>
        423 sqq.; W. Mannhardt, Der Baumkultus,<br>
        p. 510.<br>
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="mult_col">Multiple Columns</a></h3>
<p>Proofread ordinary text that has been printed in multiple columns as a single column.
   Place the text from the left-most column first, the text from the next column below that, and so on.
   Do not mark where the columns were split, just join them together. See the
   very bottom of the <a href="#para_side">Sidenotes</a> example for an example of multiple columns.
</p>
<p>See also the <a href="#bk_index">Index</a> and
   <a href="#tables">Table</a> sections of the Proofreading Guidelines.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="tables">Tables</a></h3>
<p>A proofreader's job is to be sure that all the information in a table is correctly proofread.
   Separate items with spaces as needed, but do not worry about precise alignment.
   Retain line breaks (while handling <a href="#eol_hyphen">end-of-line hyphenation
   and dashes</a> normally). Ignore any periods or other punctuation (leaders) used to align the items.
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>TABLE II.

Flat strips compared    Copper.                            Copper.
with round wire 30 cm.       Iron.  Parallel wires 30 cm. in     Iron.
in length.                                 length.

Wire 1 mm. diameter   20  100  Wire 1 mm. diameter   20  100

        STRIPS.                 SINGLE WIRE.
0.25 mm. thick, 2 mm.
  wide           ...... 15  35      0.25 mm. diameter ....  16   48
Same, 5 mm. wide  ....     13  20  Two similar wires  ...... 12  30
 "   10  "    "           11   15    Four    "    "     9   18
 "   20    "    "         10  14      Eight  "    "     8   10
 "   40  "    "          9   13     Sixteen "    "     7    6
Same strip rolled up in           Same, 16 wires bound
  the form of wire  .. 17   15    close together .....  18    12
</kbd></pre>
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
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>Agents.   Objects.
        {     1st person, I,       me,
            {  2d   "    thou,   thee,
Singular  {      "  mas.  {  he,   him,
           {  3d  "  fem.  {  she,   her,
         {            it,        it.

       {  1st person, we,         us,
Plural   {   2d   "  ye, or you,   you,
        {  3d  "   they,         them,
                  who,       whom.
</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="poetry">Poetry/Epigrams</a></h3>
<p>Insert a blank line at the start of the poetry or epigram and another
   blank line at the end, so that the formatters can clearly see the beginning and end.
   Leave each line left justified and maintain the line breaks. Insert
   a blank line between stanzas, when there is one in the image.
</p>
<p><a href="#line_no">Line Numbers</a> in poetry should be kept.
</p>
<p>Check the <a href="#comments">Project Comments</a> for the specific project you are proofreading.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>THE CHAMBERED NAUTILUS<br>
        <br>
        This is the ship of pearl which, poets<br>
        feign,<br>
        Sails the unshadowed main,--<br>
        The venturous bark that flings<br>
        On the sweet summer wind its purpled<br>
        wings<br>
        In gulfs enchanted, where the Siren sings<br>
        And coral reefs lie bare,<br>
        Where the cold sea maids rise to sun<br>
        their streaming hair.<br>
        <br>
        Its webs of living gauze no more unfurl;<br>
        Wrecked is the ship of pearl!</kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="line_no">Line Numbers</a></h3>
<p>Line numbers are common in books of poetry, and usually appear near the margin every fifth or tenth
   line. Keep line numbers, using a few spaces to separate them from the other text on the line so that
   the formatters can easily find them. Since poetry will not be rewrapped in the e-book
   version, the line numbers will be useful to readers.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="next_word">Single Word at Bottom of Page</a></h3>
<p>Proofread this by deleting the word, even if it's the second half of a hyphenated word.
</p>
<p>In some older books, the single word at the bottom of the page (called a "catchword", usually
   printed near the right margin) indicates the first word on the next page of the book (called
   an "incipit"). It was used to alert the printer to print the correct reverse (called "verso"),
   to make it easier for printers' helpers to make up the pages prior to binding, and to help
   the reader avoid turning over more than one page.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proofreading at the Page Level:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Blank Page</a></h3>
<p>Most blank pages, or pages with an illustration but no text, will already be
   marked with <kbd>[Blank Page]</kbd>. Leave this marking as is. If the page is
   blank, and [Blank Page] does not appear, there is no need to add it.
</p>
<p>If there is text in the proofreading text area and a blank image, or if there is text in the image
   but none in the text box, follow the directions for a <a href="#bad_image">Bad Image</a>
   or <a href="#bad_text">Bad Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="title_pg">Front/Back Title Page</a></h3>
<p>Proofread all the text just as it was printed on the page, whether all capitals, upper and
   lower case, etc., including the years of publication or copyright.
</p>
<p>Older books often show the first letter as a large ornate graphic&mdash;proofread this as just the letter.
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>GREEN FANCY</kbd>
        </p>
        <p><kbd>BY</kbd></p>
        <p><kbd>GEORGE BARR McCUTCHEON</kbd></p>
        <p><kbd>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
        "THE PRINCE OF GRAUSTARK," ETC.</kbd></p>
        <p><kbd>WITH FRONTISPIECE BY<br>
        C. ALLAN GILBERT</kbd></p>
        <p><kbd>NEW YORK<br>
        DODD, MEAD AND COMPANY<br>
        1917</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="toc">Table of Contents</a></h3>
<p>Proofread the Table of Contents just as it is printed in the book, whether all capitals,
   upper and lower case, etc. If there are <span style="font-variant: small-caps">Small Capitals</span>,
   see the guidelines for <a href="#small_caps">Small Capitals</a>.
</p>
<p>Ignore any periods or other punctuation (leaders) used to align the page numbers. These will be removed later in the process.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Original Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>CONTENTS</kbd></p>
        <p><kbd>
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
        II.  THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>
        FALLS IN WITH FRIENDS&nbsp;&nbsp;....&nbsp;...&nbsp;15<br>
        <br>
        III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>
        AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;33<br>
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
        MIDNIGHT &nbsp;,,,..&nbsp;&nbsp;....&nbsp;199<br>
        <br>
        XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>
        OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bk_index">Indexes</a></h3>
<p>You don't need to align the page numbers in index pages as they appear in the image;
   just make sure that the numbers and punctuation match the image and retain the line breaks.
</p>
<p>Specific formatting of indexes will occur later in the process. The proofreader's job is to make sure that all
   the text and numbers are correct.
</p>
<p>See also <a href="#mult_col">Multiple Columns</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="play_n">Plays: Actor Names/Stage Directions</a></h3>
<p>In dialog, treat a change in speaker as a new paragraph, with one blank line before it.
   If the speaker's name is on its own line, treat that as a separate paragraph as well.
</p>
<p>Stage directions are kept as they are in the original image, so
   if the stage direction is on a line by itself, proofread it that way; if it is at the end of a line
   of dialog, leave it there.
   Stage directions often begin with an opening bracket and omit the closing bracket.
   This convention is retained; do not close the brackets.
</p>
<p>Sometimes, especially in metrical plays, a word is split due to page-size constraints and placed
   above or below following a (, rather than having a line of its own. Please rejoin the word as
   per normal <a href="#eol_hyphen">end-of-line hyphenation</a>.
   See the <a href="#play4">example</a>.
</p>
<p>Please check the <a href="#comments">Project Comments</a>, as the Project Manager may
   specify different handling.
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        Has not his name for nought, he will be trode upon:<br>
        What says my Printer now?
        </kbd></p><p><kbd>
        Clow. Here's your last Proof, Sir.<br>
        You shall have perfect Books now in a twinkling.
        </kbd></p><p><kbd>
        Lap. These marks are ugly.
        </kbd></p><p><kbd>
        Clow. He says, Sir, they're proper:<br>
        Blows should have marks, or else they are nothing worth.
        </kbd></p><p><kbd>
        La. But why a Peel-crow here?
        </kbd></p><p><kbd>
        Clow. I told 'em so Sir:<br>
        A scare-crow had been better.
        </kbd></p><p><kbd>
        Lap. How slave? look you, Sir,<br>
        Did not I say, this Whirrit, and this Bob,<br>
        Should be both Pica Roman.
        </kbd></p><p><kbd>
        Clow. So said I, Sir, both Picked Romans,<br>
        And he has made 'em Welch Bills,<br>
        Indeed I know not what to make on 'em.
        </kbd></p><p><kbd>
        Lap. Hay-day; a Souse, Italica?
        </kbd></p><p><kbd>
        Clow. Yes, that may hold, Sir,<br>
        Souse is a bona roba, so is Flops too.</kbd></p>
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
      <th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        Am. Sure you are fasting;<br>
        Or not slept well to night; some dream (Ismena?)<br>
        <br>
        Ism. My dreams are like my thoughts, honest and innocent,<br>
        Yours are unhappy; who are these that coast us?<br>
        You told me the walk was private.<br>
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="anything">Anything else that needs special handling or that you're unsure of</a></h3>
<p>While proofreading, if you encounter something that isn't covered in these guidelines that you
   think needs special handling or that you are not sure how to handle, post your question, noting
   the png (page) number, in the <a href="#forums">Project Discussion</a>.
</p>
<p>You should also put a note in the proofread text to
   explain to the next proofreader, formatter, or post-processor what the problem or question is.
   Start your note with a square bracket and two asterisks <kbd>[**</kbd> and end it with another square bracket <kbd>]</kbd>.
   This clearly separates it from the author's text and signals the post-processor to
   stop and carefully examine this part of the text and the matching image to address any issues.
   You may also want to identify which round you are working in just before the <kbd>]</kbd>
   so that later volunteers know who left the note.
   Any comments put in by a previous volunteer <b>must</b> be left in place. See the next section for details.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="prev_notes">Previous Proofreaders' Notes/Comments</a></h3>
<p>Any notes or comments put in by a previous volunteer <b>must</b> be left in place.
   You may add agreement or disagreement to the existing note
   but even if you know the answer, you absolutely must not remove the comment.
   If you have found a source which clarifies the problem, please cite it so the post-processor can also
   refer to it.
</p>
<p>If you come across a note from a previous volunteer
   that you know the answer to, please take a moment and provide feedback
   to them by clicking on their name in the proofreading interface and posting a private message
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


<h3><a name="formatting">Formatting</a></h3>
<p>You may sometimes find formatting already present in the text.
   <b>Do not add or correct this formatting information</b>; the formatters will do that later in the process.
   However, you can remove it if it interferes with your proofreading. The <s>&lt;x&gt;</s> button in the
   proofreading interface will remove markup such as &lt;i&gt; and &lt;b&gt; from highlighted text.
   Some examples of formatting tasks include:
</p>
<ul>
  <li>&lt;i&gt;italics&lt;/i&gt;, &lt;b&gt;bold&lt;/b&gt;, &lt;sc&gt;Small Caps&lt;/sc&gt;</li>
  <li>Spaced-out text</li>
  <li>Font size changes</li>
  <li>Spacing of chapter and section headings</li>
  <li>Extra spaces, stars, or lines between paragraphs</li>
  <li>Footnotes that continue for more than one page</li>
  <li>Footnotes marked with symbols</li>
  <li>Illustrations</li>
  <li>Sidenote locations</li>
  <li>Arrangement of data in tables</li>
  <li>Indentation (in poetry or elsewhere)</li>
  <li>Rejoining long lines in poetry and indexes</li>
</ul>
<p>If the previous proofreader inserted formatting, please take a moment and provide feedback
   to them by clicking on their name in the proofreading interface and posting a private message
   to them explaining how to handle the situation in the future. <b>Remember to leave the
   formatting to the Formatting rounds.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="common_OCR">Common OCR Problems</a></h3>
<p>OCR commonly has trouble distinguishing between the similar characters.  Some examples are:
</p>
<ul>
  <li>The digit '1' (one), the lowercase letter 'l' (ell), and the uppercase letter 'I'.
    Note that in some fonts the number one may look like <small>I</small> (like a small capital letter 'i').</li>
  <li>The digit '0' (zero), and the uppercase letter 'O'.</li>
  <li>Dashes &amp; hyphens: Proofread these carefully&mdash;OCR'd text often
    has only one hyphen for an em-dash that should have two.
    See the guidelines for <a href="#eol_hyphen">hyphenated words</a> and <a href="#em_dashes">em-dashes</a>
    for more detailed information.</li>
  <li>Parentheses ( ) and curly braces { }.</li>
</ul>
<p>Watch out for these. Normally the context of the sentence is sufficient to determine which is the
   correct character, but be careful&mdash;often your mind will automatically 'correct' these as
   you are reading.
</p>
<p>Noticing these is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="OCR_scanno">OCR Problems: Scannos</a></h3>
<p>Another common OCR issue is misrecognition of characters. We call these errors "scannos" (like "typos").
   This misrecognition can create a word that:</p>
<ul compact>
   <li>appears to be correct at first glance, but is actually misspelled. <br>
       This can usually be caught by running <a href="wordcheck-faq.php">WordCheck</a> from the proofreading interface.</li>
   <li>is changed to a different but otherwise valid word that does not match what is in the page image.<br>
       This is subtle because it can only be caught by someone actually reading the text.</li>
</ul>
<p>Possibly the most common example of the second type is "and" being OCR'd as "arid." Other examples: "eve" for "eye",
   "Torn" for "Tom", "train" for "tram". This type is harder to spot and we have a special term for them: "Stealth Scannos."
   We collect examples of Stealth Scannos in <a href="<?php echo $Stealth_Scannos_URL; ?>">this thread</a>.
</p>
<p>Spotting scannos is much easier if you use a mono-spaced font such as
   <a href="font_sample.php">DPCustomMono</a> or Courier.  To aid proofreading, the use of
   <a href="wordcheck-faq.php">WordCheck</a> (or its equivalent) is recommended in <?php echo $ELR_round->id; ?>,
   and required in the other proofreading rounds.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="OCR_raised_o">OCR Problems: Is that &deg; &ordm; really a degree sign?</a></h3>
<p>There are three different symbols that can look very similar in the image and that
   the OCR software interprets the same (and usually incorrectly):
</p>
<ul>
  <li>The degree sign <kbd style="font-size:150%;">&deg;</kbd>: This should be used only to indicate degrees (of temperature, of angle, etc.).</li>
  <li>The superscript o: Virtually all other occurrences of a raised o should be proofread  as <kbd>^o</kbd>,
      following the guidelines for <a href="#supers">Superscripts</a>.</li>
  <li>The masculine ordinal <kbd style="font-size:150%;">&ordm;</kbd>: Proofread this like a superscript too unless
      the special character is requested in the <a href="#comments">Project Comments</a>.
      It may be used in languages such as Spanish and Portuguese, and is the equivalent of the -th in English 4th, 5th, etc.
      It follows numbers and has the feminine equivalent in the superscript a (<kbd>&ordf;</kbd>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="hand_notes">Handwritten Notes in Book</a></h3>
<p>Do not include handwritten notes in a book (unless it is overwriting faded, printed text to make it more visible).
   Do not include handwritten marginal notes made by readers, etc.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bad_image">Bad Image</a></h3>
<p>If an image is bad (not loading, mostly illegible, etc.), please post
   about this bad image in the <a href="#forums">project discussion</a> and
   click on the "Report Bad Page" button so this page is 'quarantined',
   rather than returning the page to the round. If only a small portion of the
   image is bad, leave a note as described <a href="#anything">above</a>,
   and please post in the project discussion without marking the whole page bad.
   The "Bad Page" button is only available during the first round of proofreading, so it is
   important that these issues be resolved early.
</p>
<p>Note that some page images are quite large, and it is common for your browser to
   have difficulty displaying them, especially if you have several windows open or are
   using an older computer. Before reporting this as a bad page, try zooming in on the
   image, closing some of your windows and programs, or posting in the project
   discussion to see if anyone else has the same problem.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="bad_text">Wrong Image for Text</a></h3>
<p>If there is a wrong image for the text given, please post about this bad page
   in the <a href="#forums">project discussion</a> and click on the "Report Bad
   Page" button so this page is 'quarantined', rather than returning the page to the round.
   The "Bad Page" button is only available during the first round of proofreading, so it is
   important that these issues be resolved early.
</p>
<p>It's fairly common for the OCR'd text to be mostly correct, but missing the first
   line or two of the text. Please just type in the missing line(s). If nearly all of
   the lines are missing in the text box, then either type in the whole page (if you are
   willing to do that), or just click on the "Return Page to Round" button and the page
   will be reissued to someone else. If there are several pages like this, you might
   post a note in the <a href="#forums">project discussion</a> to notify the
   Project Manager.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="round1">Previous Proofreader Mistakes</a></h3>
<p>If a previous proofreader made a lot of mistakes or missed a lot of things,
   please take a moment to provide feedback to them by clicking on their name
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
   are proofreading have statements of fact in them that we no longer accept as accurate.
   Leave them as the author wrote them. See <a href="#p_errors">Printer Errors/Misspellings</a>
   for how to leave a note if you think the printed text is not what the author intended.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Back to top</a></p>


<h3><a name="insert_char">Inserting Special Characters</a></h3>
<p>If they are not on your keyboard, there are several ways to input special
   characters:
</p>
<ul compact>
  <li>The pull-down menus in the proofreading interface.</li>
  <li>Applets included with your operating system. 
    <ul compact>
      <li>Windows: "Character Map"<br> Access it through:<br>
          Start: Run: charmap, or<br>
          Start: Accessories: System Tools: Character Map.</li>
      <li>Macintosh: Key Caps or "Keyboard Viewer"<br>
          For OS 9 and lower this is on the Apple Menu,<br>
          For OS X through 10.2, this is located the in Applications, Utilities folder<br>
          For OS X 10.3 and higher, this is in the Input Menu as "Keyboard Viewer."</li>
      <li>Linux: The name and location of the character picker will vary depending on your desktop environment.</li>
    </ul>
  </li>
  <li>An on-line program.</li>
  <li>Keyboard shortcuts.<br>
       (See the tables for <a href="#a_chars_win">Windows</a> and <a href="#a_chars_mac">Macintosh</a> below.)</li>
  <li>Switching to a keyboard layout or locale which supports "deadkey" accents.
    <ul compact>
      <li>Windows: Control Panel (Keyboard, Input Locales)</li>
      <li>Macintosh: Input Menu (on Menu Bar)</li>
      <li>Linux: Change the keyboard in your X configuration.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>For Windows</b>:
</p>
<ul compact>
  <li>You can use the Character Map program
     (Start: Run: charmap) to select an individual letter, and then cut &amp; paste.
  </li>
  <li>The character picker in the proofreading interface.
  </li>
  <li>Or you can type the Alt+NumberPad shortcut codes listed below for these characters.
          This is faster than using cut &amp; paste, once you get used to the codes.
      <br>Hold the Alt key and type the four digits on the
          <i>Number Pad</i>&mdash;the number row over the letters won't work.
      <br>You must type all 4 digits, including the leading 0 (zero).
          Note that the capital version of a letter is 32 less than the lower case.
      <br>These instructions are for the US-English keyboard layout. It may not work for other keyboard layouts.
      <br>(<a href="charwin.pdf">Print-friendly version of this table</a>)
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
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Alt-0248</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Capital O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="Small y acute"         >&yacute; </td><td>Alt-0253</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital Y acute"       >&Yacute; </td><td>Alt-0221</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>Alt-0185&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">sz ligature        </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>Alt-0189&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>Alt-0179&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Alt-0223</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>Alt-0190&nbsp;&dagger;</td>
  </tr>
  </tbody>
</table>
<p>* Unless specifically requested by the <a href="#comments">Project
   Comments</a>, please do not use the ordinal or superscript symbols, but instead use the guidelines for
   <a href="#supers">Superscripts</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; Unless specifically requested by the <a href="#comments">Project
   Comments</a>, please do not use the fraction symbols, but instead use the guidelines for
   <a href="#fract_s">Fractions</a>. (1/2, 1/4, 3/4, etc.)
</p>
<p><b>For Apple Macintosh</b>:
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
  <li>The character picker in the proofreading interface.
  </li>
  <li>Or you can type the Apple Opt- shortcut codes list below for these characters.
      <br>This is a lot faster than using cut &amp; paste, once you get used to the codes.
      <br>Hold the Opt key and type the accent symbol, then type the letter to be accented
          (or, for some codes, only hold the Opt key and type the symbol).
      <br>These instructions are for the US-English keyboard layout. It may not work for other keyboard layouts.
      <br>(<a href="charapp.pdf">Print-friendly version of this table</a>)
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Apple Mac Shortcuts for Latin-1 symbols</th>
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
  <tr><td align="center" bgcolor="mistyrose" title="Small a grave"         >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a acute"         >&aacute; </td><td>Opt-e, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a circumflex"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a tilde"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a umlaut"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a ring"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Small ae ligature"     >&aelig;  </td><td>Opt-'   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital A grave"       >&Agrave; </td><td>Opt-`, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A acute"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A circumflex"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A tilde"       >&Atilde; </td><td>Opt-n, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A umlaut"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A ring"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="Capital AE ligature"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small e grave"         >&egrave; </td><td>Opt-`, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e acute"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e circumflex"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small e umlaut"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital E grave"       >&Egrave; </td><td>Opt-`, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E acute"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E circumflex"  >&Ecirc;  </td><td>Opt-i, E</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital E umlaut"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small i grave"         >&igrave; </td><td>Opt-`, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i acute"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i circumflex"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small i umlaut"        >&iuml;   </td><td>Opt-u, i</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital I grave"       >&Igrave; </td><td>Opt-`, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I acute"       >&Iacute; </td><td>Opt-e, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital I umlaut"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ slash</th>
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Opt-o   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Opt-O   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small u grave"         >&ugrave; </td><td>Opt-`, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u acute"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u circumflex"    >&ucirc;  </td><td>Opt-i, u</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small u umlaut"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital U grave"       >&Ugrave; </td><td>Opt-`, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U acute"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U circumflex"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital U umlaut"      >&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor="cornsilk">currency     </th>
      <th colspan=2 bgcolor="cornsilk">mathematics  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small y acute"         >&yacute; </td><td>Opt-e, y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital Y acute"       >&Yacute; </td><td>Opt-e, Y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(none)&nbsp;&Dagger;</td>
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
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(none)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz ligature        </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(none)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* Unless specifically requested by the <a href="#comments">Project
   Comments</a>, please do not use the ordinal or superscript symbols, but instead use the guidelines for
   <a href="#supers">Superscripts</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; Unless specifically requested by the <a href="#comments">Project
   Comments</a>, please do not use the fraction symbols, but instead use the guidelines for
   <a href="#fract_s">Fractions</a>. (1/2, 1/4, 3/4, etc.)
</p>
<p>&Dagger;&nbsp;Note: No equivalent shortcut; use the character picker if needed.
</p>
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
        <li><a href="#a_chars">Accented/Non-ASCII Characters</a></li>
        <li><a href="#play_n">Actor Names (Plays)</a></li>
        <li><a href="#a_chars">ae Ligatures</a></li>
        <li><a href="#anything">Anything else that needs special handling</a></li>
        <li><a href="#title_pg">Back Title Page</a></li>
        <li><a href="#bad_image">Bad Image</a></li>
        <li><a href="#bad_text">Bad Text</a></li>
        <li><a href="#blank_pg">Blank Page</a></li>
        <li><a href="#formatting">Bold Text</a></li>
        <li><a href="#drop_caps">Capital Letter, Ornate (Drop Cap)</a></li>
        <li><a href="#small_caps">Capitals, <span style="font-variant: small-caps">Small</span></a></li>
        <li><a href="#illust">Captions, Illustration</a></li>
        <li><a href="#next_word">Catchwords</a></li>
        <li><a href="#chap_head">Chapter Headings</a></li>
        <li><a href="#a_chars">Characters, Accented/Non-ASCII</a></li>
        <li><a href="#d_chars">Characters with Diacritical Marks</a></li>
        <li><a href="#mult_col">Columns, Multiple</a></li>
        <li><a href="#prev_notes">Comments, Previous Proofreaders'</a></li>
        <li><a href="#common_OCR">Common OCR Problems</a></li>
        <li><a href="#toc">Contents, Table of</a></li>
        <li><a href="#contract">Contractions</a></li>
        <li><a href="#em_dashes">Dashes</a></li>
        <li><a href="#eol_hyphen">Dashes, End-of-line</a></li>
        <li><a href="#eop_hyphen">Dashes, End-of-page</a></li>
        <li><a href="#OCR_raised_o">Degree Signs</a></li>
        <li><a href="#d_chars">Diacritical Marks</a></li>
        <li><a href="#double_q">Double Quotes</a></li>
        <li><a href="#chap_head">Double Quotes, missing at start of chapter</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#drop_caps">Drop Cap</a></li>
        <li><a href="#insert_char">Drop-down Menus</a></li>
        <li><a href="#period_p">Ellipsis</a></li>
        <li><a href="#em_dashes">Em-dashes</a></li>
        <li><a href="#footnotes">Endnotes</a></li>
        <li><a href="#eol_hyphen">End-of-line Hyphenation and Dashes</a></li>
        <li><a href="#trail_s">End-of-line Space</a></li>
        <li><a href="#eop_hyphen">End-of-page Hyphenation and Dashes</a></li>
        <li><a href="#period_s">End-of-sentence Periods</a></li>
        <li><a href="#poetry">Epigrams</a></li>
        <li><a href="#extra_sp">Extra Spaces Between Words</a></li>
        <li><a href="#f_errors">Errors, Factual</a></li>
        <li><a href="#p_errors">Errors, Printer</a></li>
        <li><a href="#f_errors">Factual Errors in Texts</a></li>
        <li><a href="#prev_pg">Fixing Errors on Previous Pages</a></li>
        <li><a href="#page_hf">Footers, Page</a></li>
        <li><a href="#footnotes">Footnotes</a></li>
        <li><a href="#formatting">Formatting</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#fract_s">Fractions</a></li>
        <li><a href="#title_pg">Front/Back Title Page</a></li>
        <li><a href="#period_s">Full Stops, End-of-sentence</a></li>
        <li><a href="#f_chars">Greek Text</a></li>
        <li><a href="#hand_notes">Handwritten Notes in Book</a></li>
        <li><a href="#summary">Handy Proofreading Guide</a></li>
        <li><a href="#page_hf">Headers, Page</a></li>
        <li><a href="#chap_head">Headings, Chapter</a></li>
        <li><a href="#f_chars">Hebrew Text</a></li>
        <li><a href="#eol_hyphen">Hyphenation, End-of-line</a></li>
        <li><a href="#eop_hyphen">Hyphenation, End-of-page</a></li>
        <li><a href="#em_dashes">Hyphens</a></li>
        <li><a href="#illust">Illustrations</a></li>
        <li><a href="#bad_image">Image, Bad</a></li>
        <li><a href="#para_space">Indenting, Paragraph</a></li>
        <li><a href="#bk_index">Indexes</a></li>
        <li><a href="#insert_char">Inserting Special Characters</a></li>
        <li><a href="#formatting">Italics</a></li>
        <li><a href="#insert_char">Keyboard Shortcuts for Latin-1 Characters</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#period_p">Language Other Than English (LOTE), Ellipses in</a></li>
        <li><a href="#drop_caps">Large, Ornate Opening Capital Letter (Drop Cap)</a></li>
        <li><a href="#insert_char">Latin-1 Characters, Inserting</a></li>
        <li><a href="#a_chars">Ligatures</a></li>
        <li><a href="#line_br">Line Breaks</a></li>
        <li><a href="#line_no">Line Numbers</a></li>
        <li><a href="#subscr">Lowered Text (Subscripts)</a></li>
        <li><a href="#em_dashes">Minus Signs</a></li>
        <li><a href="#p_errors">Misspellings, Printer</a></li>
        <li><a href="#round1">Mistakes, Previous Proofreader</a></li>
        <li><a href="#mult_col">Multiple Columns</a></li>
        <li><a href="#a_chars">Non-ASCII Characters</a></li>
        <li><a href="#f_chars">Non-Latin Characters</a></li>
        <li><a href="#hand_notes">Notes, Handwritten</a></li>
        <li><a href="#prev_notes">Notes, Previous Proofreaders'</a></li>
        <li><a href="#line_no">Numbers, Line</a></li>
        <li><a href="#common_OCR">OCR Problems, Common</a></li>
        <li><a href="#OCR_raised_o">OCR Problems: Is that &deg; &ordm; really a degree sign?</a></li>
        <li><a href="#OCR_scanno">OCR Problems: Scannos</a></li>
        <li><a href="#a_chars">oe Ligatures</a></li>
        <li><a href="#OCR_raised_o">Ordinal Symbol</a></li>
        <li><a href="#drop_caps">Ornate Capital Letter (Drop Cap)</a></li>
        <li><a href="#anything">Other things that you're unsure of</a></li>
        <li><a href="#blank_pg">Page, Blank</a></li>
        <li><a href="#page_hf">Page Headers/Page Footers</a></li>
        <li><a href="#title_pg">Page, Title</a></li>
        <li><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></li>
        <li><a href="#para_space">Paragraph Spacing/Indenting</a></li>
        <li><a href="#period_p">Period Pause "..." (Ellipsis)</a></li>
        <li><a href="#period_s">Periods, End-of-sentence</a></li>
        <li><a href="#play_n">Plays: Actor Names/Stage Directions</a></li>
        <li><a href="#poetry">Poetry</a></li>
        <li><a href="#formatting">Preexisting Formatting</a></li>
        <li><a href="#round1">Previous Proofreader Mistakes</a></li>
        <li><a href="#prev_notes">Previous Proofreaders' Notes/Comments</a></li>
        <li><a href="#prev_pg">Previous Pages, Fixing Errors on</a></li>
        <li><a href="#prime">Primary Rule</a></li>
        <li><a href="#comments">Project Comments</a></li>
        <li><a href="#forums">Project Discussion</a></li>
        <li><a href="#punctuat">Punctuation Spacing</a></li>
        <li><a href="#p_errors">Printer Errors/Misspellings</a></li>
        <li><a href="#quote_ea">Quote Marks on Each Line</a></li>
        <li><a href="#double_q">Quotes, Double</a></li>
        <li><a href="#chap_head">Quotes, Missing at start of chapter</a></li>
        <li><a href="#single_q">Quotes, Single</a></li>
        <li><a href="#supers">Raised Text (Superscripts)</a></li>
        <li><a href="#OCR_scanno">Scannos</a></li>
        <li><a href="#insert_char">Shortcuts for Latin-1 Characters</a></li>
        <li><a href="#para_side">Sidenotes</a></li>
        <li><a href="#single_q">Single Quotes</a></li>
        <li><a href="#next_word">Single Word at Bottom of Page</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#trail_s">Space at End-of-line</a></li>
        <li><a href="#extra_sp">Spaces, Extra</a></li>
        <li><a href="#para_space">Spacing, Paragraph</a></li>
        <li><a href="#punctuat">Spacing, Punctuation</a></li>
        <li><a href="#insert_char">Special Characters, Inserting</a></li>
        <li><a href="#play_n">Stage Directions (Plays)</a></li>
        <li><a href="#subscr">Subscripts</a></li>
        <li><a href="#summary">Summary Guidelines</a></li>
        <li><a href="#supers">Superscripts</a></li>
        <li><a href="#toc">Table of Contents</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#extra_sp">Tabs</a></li>
        <li><a href="#bad_text">Text, Wrong Image for</a></li>
        <li><a href="#title_pg">Title Page</a></li>
        <li><a href="#chap_head">Titles, Chapter</a></li>
        <li><a href="#trail_s">Trailing Space at End-of-line</a></li>
        <li><a href="#next_word">Word at Bottom of Page</a></li>
        <li><a href="#OCR_scanno">WordCheck</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Words in Small Capitals</span></a></li>
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
