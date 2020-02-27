<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Proofreading and Formatting Guidelines Revision History', NO_STATSBAR);
?>

<h1 align="center">Proofreading and Formatting Guidelines Revision History</h1>
<table width="80%" align="center">
  <tbody valign="top">
    <tr>
      <td colspan="4">If you have any suggestions about how the Proofreading or Formatting Guidelines can be improved,
      please see the appropriate posts in the Documentation forum.</td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="silver"><br>
      </td>
    </tr>
    <tr>
      <th>Date </th>
      <th>Version </th>
      <th>Author </th>
      <th>Changes </th>
    </tr>
    <tr>
      <td colspan="4" bgcolor="silver"><br>
      </td>
    </tr>

    <tr>
      <td>06/07/2009 </td>
      <td>Ver.&nbsp;2.0</td>
      <td>acunning40 and JHowse</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.98 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Reorganization of topics and subdivisions for formatting at the character, paragraph, and page level</li>
                <li>New alphabetical index to the bottom</li>
                <li>Removed sections for topics that are handled completely during proofreading</li>
                <li>New sections about the placement of inline and out-of-line markup; italics examples moved to the inline markup section</li>
                <li>Use the /# #/ markup for a block of text that's printed differently from the regular text, regardless of whether it's quoted material</li>
                <li>When aligning tables, account for the way that markup will be converted in the plaintext version</li>
                <li>Mark right-aligned text with /* */</li>
                <li>Removed the special guidelines for poetry and encyclopedias</li>
                <li>Numerous clarifications and changes</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.42 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Reorganization of topics and subdivisions for proofreading at the character, paragraph, and page level</li>
                <li>New alphabetical index to the bottom</li>
                <li>In poetry, don't remove extra quote marks on each line</li>
                <li>Blank line at the top of the page for a new paragraph is now proofreader's responsibility</li>
                <li>Removed [/x] and [\x]</li>
                <li>Braces for superscripts and subscripts are now handled in proofreading not formatting</li>
                <li>For superscripts, use braces any time more than one character is superscripted</li>
                <li>No longer using the ordinal characters, just treat them as superscripts</li>
                <li>New section under Common Problems for degree signs, ordinals, and superscripts</li>
                <li>New section under Common Problems for preexisting formatting</li>
                <li>Several sections about common OCR problems have been condensed into one</li>
                <li>Numerous clarifications and changes</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>07/19/2007 </td>
      <td>Ver.&nbsp;1.9e </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.88 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Fix accented characters incorrectly encoded in previous version</li>
                <li>Insert missing instruction and example in the Dashes section</li>
                <li>Insert missing markup in Plays example</li>
                <li>Various wording changes to make Proofreading and Formatting Guidelines more consistent</li>
                <li>Numerous minor wording changes and typo corrections</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.33 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Fix accented characters incorrectly encoded in previous version</li>
                <li>Change Double Quotes, Accented Characters, Diacritical Marks, and Non-Latin Characters sections to include the utf-8 alternate instructions</li>
                <li>Various wording changes to make Proofreading and Formatting Guidelines more consistent</li>
                <li>Insert Summary Guidelines section</li>
                <li>Numerous minor wording changes and typo corrections</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>03/10/2007 </td>
      <td>Ver.&nbsp;1.9d </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.85 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Change spaced text markup to &lt;g&gt;.</li>
                <li>Add new section for font changes, to be marked with &lt;f&gt;.</li>
                <li>Minor edits to the sections on Chapter Headers, Section Headers,
                    and Font size changes to reflect the new markup tags.</li>
                <li>Change "spellcheck" to "WordCheck".</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.29 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Change "spellcheck" to "WordCheck".</li>
		<li>Reinstate "catchword" section from prior version.</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>01/01/2006 </td>
      <td>Ver.&nbsp;1.9c </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.80 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Give Previous Proofreader Notes its own section.</li>
                <li>Add specific mention of using "Images, Pages Proofread &amp; Differences" and
                    "Just My Pages" links as a means of editing pages previously worked on.
                <li>Numerous minor corrections and clarifications.</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.22 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Restored missing Drama example.</li>
                <li>Give Previous Proofreader Notes its own section.</li>
                <li>Add specific mention of using "Images, Pages Proofread &amp; Differences" and
                    "Just My Pages" links as a means of editing pages previously worked on.
                <li>Numerous minor corrections and clarifications.</li>
        </ul>
      </td>
    </tr>
    <tr>
    <tr>
      <td>12/08/2005 </td>
      <td>Ver.&nbsp;1.9b </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.79 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
		<li>Numerous minor corrections and updates to relevant examples.</li>
                <li>Clarification of formatting multiple footnotes on the same page.</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.19 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
		<li>Numerous minor corrections and updates to relevant examples.</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>11/01/2005 </td>
      <td>Ver.&nbsp;1.9 </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.78 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Small caps are now completely handled by the Formatters.</li>
                <li>The five-star thought break is now indicated by &lt;tb&gt;.</li>
                <li>Drama guidelines and examples enhanced.</li>
                <li>Proofreader notes are now to be indicated by <kbd>[**Note]</kbd>.</li>
                <li>Explicitly state that notes from previous proofreaders are not to be removed.</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.17 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Ellipses are now the responsibility of the Proofreaders.</li>
                <li>Spaces between initials are no longer removed.</li>
                <li>Em-dashes are no longer normalized to two hyphens, they are now
                    proofread as four hyphens if long, two hyphens if short.</li>
                <li>Drama guidelines and examples enhanced.</li>
                <li>Proofreader notes are now to be indicated by <kbd>[**Note]</kbd>.</li>
                <li>Explicitly state that notes from previous proofreaders are not to be removed.</li>
        </ul>
      </td>
    </tr>
    <tr>
    <tr>
      <td>06/01/2005 </td>
      <td>Ver.&nbsp;1.8 </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.69 -->
        <h3>Formatting Guidelines changes</h3>
        <ul compact="compact">
                <li>Guidelines split into separate Proofreading and Formatting documents.</li>
                <li>See below.</li>
        </ul>
        <!-- CVS version of proofreading_guidelines.php which reflects this entry is v1.1 through 1.16 -->
        <h3>Proofreading Guidelines changes</h3>
        <ul compact="compact">
                <li>Separate sections on Small Caps and All Capitals.</li>
                <li>Clarification of [OE] [oe] ligature handling.</li>
                <li>Clarification of Fraction handling.</li>
                <li>Expansion of small-caps and hyphens examples.</li>
        </ul>
      </td>
    </tr>
    <tr>
    <tr>
      <td>09/01/2004 </td>
      <td>Ver.&nbsp;1.7 </td>
      <td>The DP Community</td>
      <td>
        <!-- CVS version of formatting_guidelines.php which reflects this entry is v1.49 -->
        <h3>Formatting changes</h3>
        <ul compact="compact">
                <li>Terminology standardised.</li>
                <li>The tables &amp; examples have all been resized to (hopefully) make it easier for people to read with lower resolution screens.</li>
                <li>The Abbreviation section is now called Initials.</li>
                <li>Some of the layout of the rules have been changed so that we can automatically generate the random rules from the Guideline document.</li>
                <li>A section has been added for drama guidelines.</li>
                <li>A section has been added for major divisions in the text.</li>
                <li>The character map tables have been updated to include all Latin-1 characters.</li>
        </ul>
        <h3>Guideline changes</h3>
        <ul compact="compact">
                <li>Page number references within the text are to be kept throughout a project including the Table of Contents.</li>
                <li>Pages which start with a new paragraph get a blank line at the top to mark the new paragraph.</li>
                <li>We now mark-up all italics, including scholarly abbreviations.</li>
                <li>We now mark superscripts with ^</li>
                <li>A section has been added for subscripts - they are marked with _{}</li>
                <li>Random rules generation framework added.</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>05/05/2004 </td>
      <td>Ver.&nbsp;1.65 </td>
      <td>The DP Community</td>
      <td>Following items added or changed:
        <ul compact="compact">
                <li>Chapter Headings - use 4-1-2 spacing.</li>
                <li>Title Page - leave all text </li>
                <li>Sidenotes - clarification of and new example</li>
                <li>Footnotes - remove all reference to in-line footnotes </li>
                <li>Spaced Out text - added </li>
                <li>Single word at bottom of page - added </li>
                <li>Poetry/Epigrams - clarification of</li>
                <li>Letter Indentation - added /* */ markup </li>
                <li>Block Quotations - now mark with /# #/ </li>
                <li>Characters with Diacritical marks - added </li>
                <li>Indexes - leave all page number references </li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>06/19/2003 </td>
      <td>Ver.&nbsp;1.5 </td>
      <td>Tim Bonham (with suggestions from many people)</td>
      <td>Following items added or changed:
        <ul compact="compact">
          <li>Changes in Guidelines:                  </li>
            <ul compact="compact">
              <li>Changed name from Document Guidelines to Proofreading Guidelines.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php"><font size=-1 color="green">(new)</font></a></li>
              <li>Revised Footnotes section, added out-of-line ones, plus example.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#footnotes"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#footnotes"><font size=-1 color="green">(new)</font></a></li>
              <li>Bold text: default is now html-style markup instead of all-caps.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#bold"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#bold"><font size=-1 color="green">(new)</font></a></li>
              <li>Poetry: we no longer indent by 2 spaces within the /* and */ markers.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#poetry"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#poetry"><font size=-1 color="green">(new)</font></a></li>
              <li>Block Quotes: we no longer indent by 4 spaces within the /* and */ markers.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#block_qt"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#block_qt"><font size=-1 color="green">(new)</font></a></li>
            </ul>
          <li>New sections added:                     </li>
            <ul compact="compact">
              <li>Added Lists section.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#lists"><font size=-1 color="green">(new)</font></a></li>
              <li>Added Special Books section, and links to specific guides.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#sp_copy"><font size=-1 color="green">(new)</font></a></li>
              <li>Added new document of special guidelines for copyright renewals.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#sp_copy"><font size=-1 color="green">(new)</font></a></li>
              <li>Added new document of special guidelines for Encyclopedias.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#sp_ency"><font size=-1 color="green">(new)</font></a></li>
              <li>Added new document of special guidelines for Poetry.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#sp_poet"><font size=-1 color="green">(new)</font></a></li>
              <li>Added Abbreviations section
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#abbrev"><font size=-1 color="green">(new)</font></a></li>
              <li>Added Drop Caps section.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#drop_caps"><font size=-1 color="green">(new)</font></a></li>
              <li>Added Quote marks on each line section.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#quote_ea"><font size=-1 color="green">(new)</font></a></li>
              <li>Added link to French version of Proofreading Guidelines.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php"><font size=-1 color="green">(new)</font></a></li>
              <li>Added standard links at the bottom of this (and all Doc pages).
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#uncertain"><font size=-1 color="green">(new)</font></a></li>
            </ul>
          <li>More details added:                     </li>
            <ul compact="compact">
              <li>Added to Indexes section note & example of rejoining split entries.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#bk_index"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#bk_index"><font size=-1 color="green">(new)</font></a></li>
              <li>Added to eol-hyphenated section note about "if you're not sure".
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#eol_hyphen"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#eol_hyphen"><font size=-1 color="green">(new)</font></a></li>
              <li>In Sidenotes section, added note about no blank line afterwards.
                  And included "Sidenotes" in the section title.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#para_side"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#para_side"><font size=-1 color="green">(new)</font></a></li>
              <li>For Ellipsis, state that there is no space after any punctuation.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#period_p"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#period_p"><font size=-1 color="green">(new)</font></a></li>
              <li>In Bold text section, removed confusing note about 1st word of chapter.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#bold"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#bold"><font size=-1 color="green">(new)</font></a></li>
              <li>Added note in Poetry section about rejoining split lines.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#poetry"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#poetry"><font size=-1 color="green">(new)</font></a></li>
              <li>Extra spaces between words section: added tabs.
                  Also rewrote part about extra spaces around punctuation to make it clearer.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#extra_sp"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#extra_sp"><font size=-1 color="green">(new)</font></a></li>
              <li>Added example of [**Note...] to "Anything Else that needs Special...
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#anything"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#anything"><font size=-1 color="green">(new)</font></a></li>
              <li>Double Quotes: added part about non-English quote marks.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#double_q"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#double_q"><font size=-1 color="green">(new)</font></a></li>
              <li>Added part about [Transcriber's note:] to the errors sections.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#p_errors"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#p_errors"><font size=-1 color="green">(new)</font></a></li>
              <li>Added more info to "Fixing errors on Previous Pages section.  Big_Bill's rewrite.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#prev_pg"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#prev_pg"><font size=-1 color="green">(new)</font></a></li>
              <li>Illustrations section: added note about multiple lines.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#illust"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#illust"><font size=-1 color="green">(new)</font></a></li>
            </ul>
          <li>Re-worded or Typo's fixed:              </li>
            <ul compact="compact">
              <li>Chapter Header example: corrected it: skip 4/2 lines, also
                  added note to do blank lines even if it starts on a new page.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#chap_head"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#chap_head"><font size=-1 color="green">(new)</font></a></li>
              <li>Fixed typo in Sidenotes section.
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#para_side"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#para_side"><font size=-1 color="green">(new)</font></a></li>
              <li>Replaced Dashes section with Bill Flis's rewritten version.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#em_dashes"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#em_dashes"><font size=-1 color="green">(new)</font></a></li>
              <li>Added Small Caps to Word in all Caps section
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#word_caps"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#word_caps"><font size=-1 color="green">(new)</font></a></li>
              <li>Slight rewording in section "About this Document".
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#about"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#about"><font size=-1 color="green">(new)</font></a></li>
              <li>Slight rewording in section on Greek & other non-Latin words, added biblos example.
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#f_chars"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#f_chars"><font size=-1 color="green">(new)</font></a></li>
              <li>Block Quotes: refer to "text" rather than "book".
                <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="olddoc14.html#block_qt"><font size=-1 color="red">(old)</font></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="formatting_guidelines.php#block_qt"><font size=-1 color="green">(new)</font></a></li>
            </ul>
        </ul>
      </td>
    </tr>
    <tr>
      <td>02/20/2003 </td>
      <td>Ver.&nbsp;1.4 </td>
      <td>Tim Bonham (with suggestions from many people)</td>
      <td>Following items added or changed:
          <ul compact="compact">
            <li>Chapter Headers: specify 4 & 2 blank lines instead of 3 & 1. </li>
            <li>Line Breaks: mention that poetry & footnotes will mess up line breaks. </li>
            <li>fixed the broken link to the DP banner.</li>
            <li>added links to home pages for DP &amp; PG.</li>
            <li>Endnotes: added instruction to put blank lines between endnotes.
                Also bolded endnotes so people find this part. </li>
            <li>Italics: added note & example for numerals in italics.<br>
                Also highlighted different symbol for closing italics.</li>
            <li>Underlined Text: added this section to the Guidelines.</li>
            <li>Page Headers: fixed the formatting of the Footnote.   </li>
            <li>Chapter Headers: mention 1st word of paragraphs in all caps. </li>
            <li>added  Extra spaces Between Words section.                   </li>
            <li>Em-Dashes: reworded Guideline slightly.                      </li>
            <li>Illustrations: fixed error in example.                       </li>
            <li>Summary: added an item & link to Bill Flis's pdf document.   </li>
            <li>Footnotes; Poetry,Tables: added info about Footnotes in Poetry &amp; Tables.     </li>
            <li>added "Fixing errors on Previous Pages" section.             </li>
            <li>changed "Foreign Language" to "non-English".                 </li>
          </ul>
      </td>
    </tr>
    <tr>
      <td>01/13/2003 </td>
      <td>Ver.&nbsp;1.3 </td>
      <td>Tim Bonham </td>
      <td>Major rewrite to more standard html.
          <br>Added DP logo at top, added dividers between sections &amp; other pretty
              stuff.
          <br>Wording changes in many sections.  Generally, changed to active voice and
              put the proofreaders action-to-take as the opening phrase.
          <br>Added many more links between sections.
          <br>Added the following sections:
              <ul compact="compact">
                <li>Primary Rule </li>
                <li>Project Comments </li>
                <li>Forum/Discuss this Project </li>
                <li>Superscripts </li>
                <li>Font Size changes </li>
                <li>Word in all Caps </li>
                <li>Poetry/Epigrams (merged old sections) </li>
                <li>Block Quotations </li>
                <li>Punctuation </li>
                <li>Accented/Non-ASCII Characters (split old section)          </li>
                <li>Accented/Non-ASCII Characters (added printer-friendly page)</li>
                <li>Foreign Language Characters   (split old section)          </li>
                <li>Fractions                     (split old section)          </li>
                <li>Indexes </li>
                <li>Anything else that needs special handling   </li>
                <li>1/l/I OCR problems </li>
                <li>other OCR problems </li>
                <li>Factual Errors in Texts </li>
              </ul>
      </td>
    </tr>
    <tr>
      <td>12/18/2002 </td>
      <td>Ver.&nbsp;1.23 </td>
      <td>Tim Bonham </td>
      <td>Reword parts about Foreign transliteration, including Greek,
          Cyrillic, Hebrew and Arabic. <br>
          Added more Apple keyboard shortcuts for  foreign / non-ASCII
          characters. <br>
          Added sections on index pages &amp; fractions. </td>
    </tr>
    <tr>
      <td>12/18/2002 </td>
      <td>Ver.&nbsp;1.22 </td>
      <td>Tim Bonham </td>
      <td>Change format of code table for foreign / non-ASCII characters, minor wording
          changes.</td>
    </tr>
    <tr>
      <td>12/16/2002 </td>
      <td>Ver.&nbsp;1.21 </td>
      <td>Charles Aldarondo </td>
      <td>Minor spelling/grammar errors fixed.                         </td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="silver"><br>
      </td>
    </tr>
  </tbody>
</table>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<?php
// vim: sw=4 ts=4 expandtab
