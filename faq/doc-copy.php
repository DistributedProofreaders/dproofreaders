<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

output_header('Proofreading Guidelines -- Copyright Renewal Books', NO_STATSBAR);
?>

<h2>Proofreading Guidelines -- Special Rules for Copyright Renewal Books</h2>
<p> These are special additions to the Proofreading Guidelines for Copyright Renewal books.
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
<hr>

<p>
In mid-1973, the U.S. Copyright Office significantly changed the format
(and collation order) of the entries in the Catalog of Copyright Entries.
Thus, we have some different guidelines for the two formats,
and also some common guidelines.
</p>

<h3>All years</h3>

<ul>
    <li>Leave in the special characters such as <b>&copy;</b>, <b>&eacute;</b>,
        <b>&oacute;</b>, etc.                                                   </li>
    <li>Follow the normal rules for hyphens (remove hyphens that split a word
        across lines and join the word back together on the top line).          </li>
    <li>Check very carefully the sequences like <kbd>A867103. R111277</kbd>.
        The usual format is a capital letter followed by 6 numbers, but the OCR
        scanner often has trouble with this, and frequently mis-reads them.     </li>
    <li>Contrary to the
        <a href="<?php echo $code_url; ?>/faq/proofreading_guidelines.php#abbrev">General Guidelines</a>,
        do not remove spaces between initials in names.
        For example, do not change
        <kbd>G.&nbsp;B.&nbsp;Shaw</kbd> to
        <kbd>G.B.&nbsp;Shaw</kbd>.


</ul>

<h3>1950 to mid-1973</h3>

  <ul>
    <li>Leave 2 blank lines before each author's name.                          </li>
    <li>On the first line of a main entry,
        reproduce the capitalization that appears in the image.
        (We used to ask proofreaders to capitalize the author's entire name,
        but that guideline was withdrawn.)
    <br>If the name runs over onto the next line (due to narrow columns), join
        it back together so it's all on one line.                               </li>
    <li>Do NOT leave a blank line after the author's name.                      </li>
    <li>Indent 3 spaces for the first line of each sub-entry.                   </li>
    <li>Indent 5 spaces for the remaining lines of each sub-entry.              </li>
    <li>Leave 1 blank line between each sub-entry under an author.              </li>
    <li>Leave 2 blank lines between each main entry (each new author).          </li>
    <li>Capitalize the word SEE when used to refer to another entry.
        For the name that appears after the SEE,
        reproduce the capitalization that appears in the image.
        (We used to ask proofreaders to ensure that the name was in Title Case,
        retyping it if necessary, but that guideline was withdrawn.)
    <br>

<li>
<b>Example:</b>
<table class="default-border" style="width: 50%">
  <tbody>
    <tr>
      <td>
      <kbd>

ABAILARD, PIERRE.                                                <br>
&nbsp;&nbsp;&nbsp;The letters of Abelard and Heloise;            <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;translated from the Latin by C. K. <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Scott Moncrieff, with a prefatory  <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;letter by George Moore. (The Blue  <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jade library) &#169; 19Feb26, (pub.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;abroad 17Sep25, AI-7349), A883674. <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R112040, 15May53, Alfred A. Knopf, <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;inc. (PWH)                         <br>
                                                                 <br>
&nbsp;&nbsp;&nbsp;Love letters of Abelard and Heloise,           <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;edited by Lloyd E. Smith. (Little  <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;blue book, no. 871) &#169; 31Aug25,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A867103. R111277, 23Apr53,         <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Haldeman-Julius Co. (PWH)          <br>
                                                                 <br>
                                                                 <br>
ABBOT, CHARLES G.                                                <br>
&nbsp;&nbsp;&nbsp;The earth and the stars. &#169; 2Sep25,        <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A864710. R112347, 20May53,         <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Charles G. Abbot (A)               <br>
                                                                 <br>
                                                                 <br>
BENNETT, MARIE MARGUERITE                                        <br>
&nbsp;&nbsp;&nbsp;Things that have interested me.                <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R109181. SEE Bennett, Arnold.      <br>

      </kbd>
      </td>
   <!-- would be good to have the corresponding image here, too! -->
    </tr>
  </tbody>
</table>
</ul>

<h3>mid-1973 to 1977</h3>
  <ul>
    <li> Leave two blank lines between each entry.
    </li>
    <li> Reproduce the capitalization that appears in the image.
    </li>
    <li> Reproduce the line-breaks that appear in the image,
         except to fix exposed hyphens.
    </li>
    <li> In each entry:
      <ul>
        <li>The first line is flush-left.</li>
        <li>The second line is indented 3 spaces.</li>
        <li>Subsequent lines are indented 5 spaces.</li>
      </ul>
    </li>

<li>
<b>Example:</b>
(for <a href="<?php echo $projects_url; ?>/projectID3f5be9a8c1685/269.png">this image</a>)
<table class="default-border" style="width: 50%">
  <tbody>
    <tr>
      <td>
      <pre>


R554715.
   Le Producteur de lait; guide du controle
     laitier et beurrier. Par Andre
     Max Leroy, Jacqueline Sentex &amp; Rodolphe
     Stoeckel. &copy; 21Feb46; AF1301. Andre
     Max Leroy (A); 28Jun73; R554715.


R554716.
   Kitchener, marechal de l'Empire
     britannique. By Leon Lemonnier.
     &copy; 14Mar46; AF1438. Madame Lemonnier,
     nee Renee Legrand (W) &amp; Alice Lemonnier
     (C); 28Jun73; R554716.


R554717.
   La France bourgeoise, dix huitieme-vingtieme
     siecles. Par Charles Moraze,
     pref. de Lucien Febvre. &copy; 8Apr46;
     AF2001. Charles Moraze (A); 28Jun73;
     R554717.


R554718.
   La Maison des deux pigeons. Par
     Andre DeLaTourrasse, illus. de
     A. Pecoud. &copy; 9May46; AF2304. Andre
     DeLaTourrasse (A); 28Jun73; R554718.
      </pre>
      </td>
    </tr>
  </tbody>
</table>
</ul>

<h3>Priority of guidelines:</h3>

<ul>
    <li>First, follow any special guidelines the Project Manager gives about this
        specific book in the Project Comments.
    <li>Then follow these general guidelines for Copyright Renewals.
    <li>Finally, for anything not covered here, follow the standard rules in the
        <a href="proofreading_guidelines.php">Proofreading Guidelines</a>.
        (Although really, if you find yourself going there to resolve a question,
        you'd probably be better off asking in the project's discussion topic.)
    </li>
</ul>

<?php
