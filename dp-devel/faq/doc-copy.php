<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofing Guidelines -- Copyright Renewal Books','header');
?>

  <style TYPE="text/css">
<!--
  tt {color: red}
        -->
  </style>
<!--
  <style>
                @page { size: 21.59cm 27.94cm; margin-left: 3.18cm; margin-right: 3.18cm; margin-top: 2.54cm; margin-bottom: 2.54cm }
                P { margin-bottom: 0.21cm }
                TD P { margin-bottom: 0.21cm }
  </style>
        -->

<h2 align="center">Proofing Guidelines -- Special Rules for Copyright Renewal Books</h2>
<p> These are special additions to the Proofing Guidelines for Copyright Renewal books.
    They were created because of the specialized content of these books, and the need
    for them to be very consistently formatted to be useful to readers.</p>
<p> Those 'readers' for these books are mainly researchers, trying to determine if
    certain books first published after 1923 might nevertheless be in the public domain,
    because of NOT having had their copyright renewed (which used to be required to
    maintain copyright).  So, if nothing else, they help us (or at least, someone) track
    down more recent work for possible inclusion in Distributed Proofreaders and Project
    Gutenberg.</p>
<p> &nbsp;&nbsp;&nbsp;&nbsp;
    Many people consider this very boring stuff but it is important that we get it
    right. <i>Thanks for proofreading it!</i>
</p>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
  <ul>
    <li>Leave 2 blank lines before each author's name.                          </li>
    <li>CAPITALIZE the Authors entire name (first line of a main entry).
    <br>If the name runs over onto the next line (due to narrow columns), join
        it back together so it's all on one line.                               </li>
    <li>Do NOT leave a blank line after the author's name.                      </li>
    <li>Indent 3 spaces for the first line of each sub-entry.                   </li>
    <li>Indent 5 spaces for the remaining lines of each sub-entry.              </li>
    <li>Leave 1 blank line between each sub-entry under an author.              </li>
    <li>Leave 2 blank lines between each main entry (each new author).          </li>
    <li>Leave in the special characters such as <b>&copy;</b>, <b>&eacute;</b>,
        <b>&oacute;</b>, etc.                                                   </li>
    <li>Follow the normal rules for hyphens (remove hyphens that split a word
        across lines and join the word back together on the top line).          </li>
    <li>Check very carefully the sequences like <tt>A867103. R111277</tt>.
        The usual format is a capital letter followed by 6 numbers, but the OCR
        scanner often has trouble with this, and frequently mis-reads them.     </li>
    <li>Capitalize the word SEE when used to refer to another entry.
        But the name referred to should be in normal case (only first letter
        capitalized), <i>even if</i> it was in all caps on the printed page.
        (An exception to our normal rules to "proof it like it was printed", but
        the consistent format is important to the researchers using these
        books.)
        <br>(See a sample in the last entry of the example below.)             </li>
    <br>
    <li>Priority of guidelines:
    <br>First, follow any special guidelines the Project Manager gives about this
        specific book in the Project Comments.
    <br>Then follow these general guidelines for Copyright Renewals.
    <br>Finally, for anything not covered here, follow the standard rules in the
        <a href="document.php">Proofing Guidelines</a>.</li>
  </ul>

<p>
<b>Example:</b>
<table width="50%" border="1"  cellpadding="4"
       cellspacing="0"> <col width="128*">
  <tbody>
    <tr valign="top">
      <td>
      <tt>

          ABAILARD, PIERRE.                                                     <br>
            &nbsp;&nbsp;&nbsp; The letters of Abelard and Heloise;              <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; translated from the Latin by C. K. <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Scott Moncrieff, with a prefatory  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; letter by George Moore. (The Blue  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jade library) &#169; 19Feb26, (pub.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; abroad 17Sep25, AI-7349), A883674. <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; R112040, 15May53, Alfred A. Knopf, <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; inc. (PWH)                         <br>
                                                                                <br>
            &nbsp;&nbsp;&nbsp; Love letters of Abelard and Heloise,             <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; edited by Lloyd E. Smith. (Little  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; blue book, no. 871) &#169; 31Aug25,<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A867103. R111277, 23Apr53,         <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Haldeman-Julius Co. (PWH)          <br>
                                                                                <br>
                                                                                <br>
          ABBOT, CHARLES G.                                                     <br>
            &nbsp;&nbsp;&nbsp; The earth and the stars. &#169; 2Sep25,          <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A864710. R112347, 20May53,         <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Charles G. Abbot (A)               <br>
                                                                                <br>
                                                                                <br>
          BENNETT, MARIE MARGUERITE                                             <br>
            &nbsp;&nbsp;&nbsp; Things that have interested me.                  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; R109181. SEE Bennett, Arnold.      <br>
      </tt>
      </td>
   <!-- would be good to have the corresponding image here, too! -->
    </tr>
  </tbody>
</table>

<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
        <a href="document.php">Proofing Guidelines</a>.
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