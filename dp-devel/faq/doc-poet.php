<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofing Guidelines -- Poetry','header');
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

<h2 align="center">Proofing Guidelines -- Special Rules for Poetry </h2>
<p> These are special additions to the Proofing Guidelines for Poetry.
    They were created because poetry is rather specialized, and is often printed
    differently than the prose works that make up much of our proofreading.  So
    we wanted to expand on the poetry guidelines here.
  <br>
    &nbsp;&nbsp;&nbsp;&nbsp;
    Poetry if often a bit more challenging to proofread than other texts (but often
    more interesting)! <i>Thanks for your help in proofreading these books.</i>
</p>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
  <ul>
    <li>Insert a new line before the start of the poetry, and type <tt>/*</tt> on it,
    <br>and insert a new line after the end of the poetry, and type <tt>*/</tt> on it.
        (These markers are to help the post processor find the poetry, which has to
        be treated differently.)                                                </li>
    <li>If lines are indented, indent them (in groups of 2 spaces).             </li>
    <li>But don't try to <b>center</b> poetry, even if it was centered in the
        printed version.  (That won't work for an e-book viewed with many
        different screen sizes.) Just put it all along the left margin.         </li>
    <li>If a line of verse is "broken" because it collided with the right
        margin, join it back into a single line.                                </li>
    <li>If some words are set in ALL CAPS at the beginning of a poem (or each
        stanza of a poem), change them to normal capitalization.  (This was a
        common typesetting convention in these older books.)                    </li>
    <li>Footnotes in Poetry should be at the end, so as to not interrupt the
        flow of the Author's verse.
    <br>Put square brackets <tt>[</tt> and <tt>]</tt> around the footnote number
        in the line of verse.
    <br>Then put the footnote itself on a separate line at the end of a stanza,
        or at the end of the poem (just after the closing <tt>*/</tt>). Example:
        <tt>[Footnote 1: text of footnote goes here.]</tt>                      </li>
    <li>Check the Project Comments for the specific book.  Poetry books often
        have special instructions from the Project Manager.  (Many times, you
        won't have to follow all these formatting guidelines for a book that is
        mostly or entirely poetry.)                                             </li>
    <br>
    <li>Priority of guidelines:
    <br>First, follow any special guidelines the Project Manager gives about this
        specific book in the Project Comments.
    <br>Then follow these general guidelines for Poetry.
    <br>Finally, for anything not covered here, follow the standard rules in the
        <a href="document.php">Proofing Guidelines</a>.</li>
  </ul>

<p>
   <!-- we need a good example of poetry here. It should show all the stuff mentioned -->
<b>Example:</b>
    <br>[To be added...]
   <!--
   /* this is the table from encyclopedias -- poetry example will use this format. */
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
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jade library) © 19Feb26, (pub.     <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; abroad 17Sep25, AI-7349), A883674. <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; R112040, 15May53, Alfred A. Knopf, <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; inc. (PWH)                         <br>
                                                                                <br>
            &nbsp;&nbsp;&nbsp; Love letters of Abelard and Heloise,             <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; edited by Lloyd E. Smith. (Little  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; blue book, no. 871) © 31Aug25,     <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A867103. R111277, 23Apr53,         <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Haldeman-Julius Co. (PWH)          <br>
                                                                                <br>
                                                                                <br>
          ABBOT, CHARLES G.                                                     <br>
            &nbsp;&nbsp;&nbsp; The earth and the stars. © 2Sep25,               <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A864710. R112347, 20May53,         <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Charles G. Abbot (A)               <br>
                                                                                <br>
                                                                                <br>
          BENNETT, MARIE MARGUERITE                                             <br>
            &nbsp;&nbsp;&nbsp; Things that have interested me.                  <br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; R109181. SEE Bennett, Arnold.      <br>
      </tt>
      </td>
    </tr>
  </tbody>
</table>
   -->

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