<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofing Guidelines Revision History','header');
?>

<h1 align="center">Proofing Guidelines Revision History</h1>
<table width="80%" align="center">
  <tbody valign="top">
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
      <td>06/03/2003 </td>
      <td>Ver.&nbsp;1.5 </td>
      <td>Tim Bonham (with suggestions from many people)</td>
      <td>Following items added or changed:
          <ul compact="compact">
            <li>Lists: added this section.                                       </li>
            <li>Chapter Header example: corrected it: skip 4/2 lines, also
                added note to do blank lines even if it starts on a new page.    </li>
            <li>Double Quotes: added paragraph about quote marks on each line.   </li>
            <li>Changed link to point to new DP logo.                            </li>
            <li>Added link to French version of Proofing Guidelines.             </li>
            <li>Added secion for Special Books, and links to specific guides.    </li>
            <li>Added new document of special guidelines for copyright renewals. </li>
            <li>Added new document of special guidelines for Encyclopedias.      </li>
            <li>Added new document of special guidelines for Poetry.             </li>
            <li>Added to Indexes section note & example of rejoining split entries. </li>
            <li>Fixed typo in Sidenotes section.                                 </li>
            <li>In Sidenotes section, added note about no blank line afterwards. </li>
            <li>Added to eol-hyphenated section note about "if you're not sure". </li>
            <li>Replaced Dashes section with Bill Flis's rewritten version.      </li>
            <li>Added Abbreviations section                                      </li>
            <li>Changed name from Document Guidlines to Proofing Guidelines.     </li>
            <li>Added Small Caps to Word in all Caps section                     </li>
            <li>Added standard links at the bottom of this (and all Doc pages).  </li>
            <li>For Ellipsis, state that there is no space after any punctuation.</li>
            <li>Added section on Drop Caps.                                      </li>
            <li>In Bold test section, removed confusing note about 1st word of chapter. </li>
            <li>Slight rewording in section "About this Document".               </li>
            <li>Revised Footnotes section, added out-of-line ones, plus example. </li>
            <li>Slight rewording in section on Greek & other non-Latin words.    </li>
            <li>Added note in Poetry section about rejoining split lines.        </li>
            <li>Added tabs to section Extra spaces between words.                </li>
            <li>                                                                 </li>
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
    <tr>
      <td colspan="4">If you have any updates to this document, either update the
          Sourceforge CVS tree, or send them to Aldarondo via PM or email.  (Please
          don't make suggestions for someone else to change.  Just go ahead and make the
          changes yourself, and then send in the revised document, with a note about
          what you've changed.) </td>
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
<?
theme('','footer');
?>
