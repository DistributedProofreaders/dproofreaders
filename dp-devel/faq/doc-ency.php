<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofreading Guidelines -- Encyclopedias','header');
?>

<!--
  <style>
                @page { size: 21.59cm 27.94cm; margin-left: 3.18cm; margin-right: 3.18cm; margin-top: 2.54cm; margin-bottom: 2.54cm }
                P { margin-bottom: 0.21cm }
                TD P { margin-bottom: 0.21cm }
  </style>
        -->

<h2 align="center">Proofreading Guidelines -- Special Rules for Encyclopedias </h2>
<p> These are special additions to the Proofreading Guidelines for Encyclopedias.
    They were created because of the specialized content of these books, and the need
    for them to be very carefully and constently formatted through the whole set of volumes.
  <br>
    &nbsp;&nbsp;&nbsp;&nbsp;
    These pages are typically large, both in image size and quantity of text. Each page
    will take much more time than most other books.  <i>Thanks for proofreading it!</i>
</p>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
  <ul>
    <li>Article titles: Leave in UPPER CASE like the original printed form. Do not mark them as boldface.</li>
    <li>Greek: Please transliterate and place in square brackets with the word
        "Greek:".  Translate each Greek character into the matching Latin
        letter using the <a href="http://gutenberg.net/howto/greek/"> PG
        Greek transliteration guidelines</a>.  Example: <tt>like a fish [Greek:
        ichthus]</tt>.                                                          </li>
    <li>Sidenotes: Place at beginning of the paragraph, without a blank line following. If there are additional
        sidenotes in a long paragraph, place each at the beginning of the
        sentence that makes sense.                                              </li>
    <li>Numbers: Watch out for the common OCR mistakes: numeral 1 being mistaken as
        a capital letter I, numeral 0 as lower-case letter o, 9 as g, etc. These occur frequently in dates.</li>
    <li>Tables: please line up text as best possible and place within
        <tt>/*</tt> and <tt>*/</tt> markers before and after. Indent the table
        two spaces. Watch for tables that are
        wider than one column, in case part of it was misplaced.                </li>
    <li>Footnotes: Follow the standard guidelines, in out-of-context form.
    </li>
    <li>Ligatures: Connected letters, such as "ae" in Formul&aelig;, should be proofread as shown - but do not
        create a ligature where the two-character equivalent is found in the original text.</li>
    <li>Illustrations: Mark as indicated in the standard guidelines, but *be sure* to position them before or
    after a paragraph if possible. If such a break is not found on the current page, leave a **proofreader's note**.
        <tt>[Illustration: blah blah blah]</tt> and move it so it is located
        paragraphs.                                                     </li>
    <li>Author listings: The first few pages of the volume list article authors and
        their initials. List the article titles after the author information, indented 2
        spaces, with 1 blank line between article titles.  Leave 2 blank lines between
        authors.
   <br> <b>Example:</b>                                                         </li>
<table width="90%" border="1"  cellpadding="4"
       cellspacing="0"> <col width="128*">
  <tbody>
    <tr valign="top">
      <td>
      <tt>
          E.C.B.  RIGHT REV. EDWARD CUTHBERT BUTLER, O.S.B., D.LITT. (Dubl.).                   <br>
             &nbsp;&nbsp;Abbot of Downside Abbey, Bath.                                         <br>
                                                                                                <br>
             &nbsp;&nbsp;Anthony, Saint; Augustinian Canons; Augustinian Hermits; Augustinians. <br>
                                                                                                <br>
      </tt>
   <!-- would be good to have the next author shown here!  -->
      </td>

   <!-- would be good to have the corresponding image here, too! -->
    </tr>
  </tbody>
</table>
    <li>Priority of guidelines:
    <br>First, follow any special guidelines the Project Manager gives about this
        specific book in the Project Comments.
    <br>Then follow these general guidelines for Encyclopedias.
    <br>Finally, for anything not covered here, follow the standard rules in the
        <a href="document.php">Proofreading Guidelines</a>.</li>
  </ul>

  
<b><font size="+1">Math and Chemical Formulas</font></b> 
<ul>
  <li>Decimal points: Use the standard period character for a decimal point, not 
    the "middle dot" special character. </li>
  <li>Superscripts and subscripts: Use the caret <tt>^</tt> for superscripts and 
    the underscore <tt>_</tt> for subscripts, placed immediately in front of the 
    affected character. Use curly brackets <tt>{ }</tt> for grouping, such as 
    <tt>2^{1/6}</tt>.<br>
    <b>Examples:</b> 
    <table align="center" width="90%" border="1"  cellpadding="4"
           cellspacing="0">
      <col width="128*">
      <tbody>
        <tr valign="top"> 
          <th>Scanned text</th>
          <th>Proofread text</th>
          <th>Description</th>
        </tr>
        <tr> 
          <td>3&middot;844 &times; 10 <sup>8</sup> meters</td>
          <td><tt>3.844 x 10^{8} meters</tt></td>
          <td>Distance to Moon (mean average)</td>
        </tr>
        <tr> 
          <td>6&middot;02 &times; 10 <sup>23</sup></td>
          <td><tt>6.02 x 10^{23}</tt></td>
          <td>Avogardro's number</td>
        </tr>
        <tr> 
          <td>H<sub>2</sub>O</td>
          <td><tt>H_{2}O</tt></td>
          <td>Chemical formula for water</td>
        </tr>
        <tr> 
          <td>C<sub>2</sub>H<sub>6</sub>O</td>
          <td><tt>C_{2}H_{6}O</tt></td>
          <td>Chemical formula for Ethanol (alcohol)</td>
        </tr>
        <!-- would be good to have the corresponding image here, too! -->
      </tbody>
    </table>
  </li>
  <li>Large formulas: Anything large enough to be separated out from the paragraph 
    should be bracketed on a separate line with <tt>/*</tt> and <tt>*/</tt>, and 
    indented by two spaces. </li>
  <li>Individual greek letters used as math symbols can be replaced with the full 
    name of the letter instead of the single letter transliteration used for full 
    Greek words in text. For example, the triangle-shaped Greek character &Delta; 
    would be proofread as <tt>[Delta]</tt>, not <tt>[Greek: D]</tt>. </li>
  <li>Fractions: Simple fractions in the set {½, ¼, and ¾ should be represented 
    as shown in the original text, if not intermixed with unavailable fractions. 
    Otherwise, use the format "n/n", or "n-n/n" if greater than 1. </li>
  <li>Italicisized Singleton Letters: These are commonly found in algebraic formulas, 
    enumerated lists, references to features in illustrations, etc. Do not mark 
    these with italics delimiters.</li>
</ul>

<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
        <a href="document.php">Proofreading Guidelines</a>.
      <br>
        <a href="..">Distributed Proofreaders home page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="faq_central.php">DP FAQ Central page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="http://www.gutenberg.net">Project Gutenberg home page</a>.
        </font>
    </td>
</tr>
</table>

<?
theme('','footer');
?>
