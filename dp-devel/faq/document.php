<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Proofing Guidelines','header');
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

<h1 align="center">Proofing Guidelines</h1>
<h3 align="center">Version 1.51, released Aug 16, 2003 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<!-- Real version:
<h4>Proofing Guidelines in <a href="doc-fr.html">French</font></a> /
    Directives de Formatage en <a href="doc-fr.html">Fran&ccedil;aise</font></a></h4>
 ** Temporary redirect:  -->
<h4>Proofing Guidelines in <a href="http://panic.et.tudelft.nl/~saibot/DPGF2.html">French</font></a> /
    Directives de Formatage en <a href="http://panic.et.tudelft.nl/~saibot/DPGF2.html">Fran&ccedil;aise</font></a></h4>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver" align="left"><font size="+2"><b>Table of Contents</b></font></td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">The Primary Rule </a>                      </li>
        <li><a href="#summary">Summary Guidelines</a>                   </li>
        <li><a href="#about">About This Document</a>                    </li>
        <li><a href="#comments">Project Comments</a>                    </li>
        <li><a href="#forums">Forum/Discuss this Project</a>            </li>
        <li><a href="#prev_pg">Fixing errors on Previous Pages</a>      </li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver" align="left">
        <ul>
          <li>Formatting of the...                                      </li>
        </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="white" align="left">
      <ul>
        <li>
          <ul>
            <li><a href="#title_pg">Front/Back Title Page</a>           </li>
            <li><a href="#toc">Table of Contents</a>                    </li>
            <li><a href="#blank_pg">Blank Page</a>                      </li>
            <li><a href="#page_hf">Page Headers/Footers</a>             </li>
            <li><a href="#chap_head">Chapter Headers</a>                </li>
            <li><a href="#para_side">Paragraph Side-Descriptions (SideNotes)</a></li>
            <li><a href="#para_space">Paragraph Spacing</a>             </li>
            <li><a href="#mult_col">Multiple Columns</a>                </li>
            <li><a href="#illust">Illustrations</a>                     </li>
            <li><a href="#footnotes">Footnotes/Endnotes</a>             </li>
            <li><a href="#italics">Italics</a>                          </li>
            <li><a href="#bold">Bold Text</a>                           </li>
            <li><a href="#supers">Superscripts</a>                      </li>
            <li><a href="#underl">Underlined Text</a>                   </li>
            <li><a href="#font_sz">Font size changes</a>                </li>
            <li><a href="#word_caps">Word in all Caps or Small Caps</a> </li>
            <li><a href="#drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a> </li>
            <li><a href="#eol_hyphen">End-of-line Hyphenation</a>       </li>
            <li><a href="#eop_hyphen">End-of-page Hyphenation</a>       </li>
            <li><a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a></li>
            <li><a href="#abbrev">Abbreviations</a>                     </li>
            <li><a href="#contract">Contractions</a>                    </li>
            <li><a href="#poetry">Poetry/Epigrams </a>                  </li>
            <li><a href="#letter">Letter Indenting</a>                  </li>
            <li><a href="#lists">Lists of Items</a>                     </li>
            <li><a href="#tables">Tables</a>                            </li>
            <li><a href="#block_qt">Block Quotations</a>                </li>
            <li><a href="#double_q">Double Quotes</a>                   </li>
            <li><a href="#single_q">Single Quotes</a>                   </li>
            <li><a href="#quote_ea">Quote Marks on each line</a>        </li>
            <li><a href="#period_s">Periods Between Sentences</a>       </li>
            <li><a href="#punctuat">Punctuation</a>                     </li>
            <li><a href="#line_br">Line Breaks</a>                      </li>
            <li><a href="#extra_sp">Extra spaces or tabs between Words</a></li>
            <li><a href="#line_no">Line Numbers</a>                     </li>
            <li><a href="#extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></li>
            <li><a href="#period_p">Period Pause "..."</a>              </li>
            <li><a href="#a_chars">Accented/Non-ASCII Characters</a>    </li>
            <li><a href="#f_chars">Non-English Characters</a>           </li>
            <li><a href="#fract_s">Fractions</a>                        </li>
            <li><a href="#page_ref">Page References "(See Pg. 123)"</a> </li>
            <li><a href="#bk_index">Indexes</a>                         </li>
            <li><a href="#trail_s">Trailing Space at End-of-line</a>    </li>
            <li><a href="#play_n">Play Actor Names/Stage Notes</a>      </li>
            <li><a href="#anything">Anything else that needs special handling or that you're unsure of</a> </li>
          </ul>
        </li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver" align="left">
      <ul>
        <li>Specific Guidelines for Special Books</li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="white" align="left">
      <ul>
        <li>
          <ul>
            <li><a href="#sp_copy">Copyright Renewal Books</a>             </li>
            <li><a href="#sp_ency">Encyclopedias</a>                       </li>
            <li><a href="#sp_poet">Poetry Books</a>                        </li>
            <li><a href="#sp_chem">Chemistry Books</a>   [to be completed.]</li>
            <li><a href="#sp_math">Mathemetics Books</a> [to be completed.]</li>
          </ul>
        </li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver" align="left">
      <ul>
        <li>Common Problems </li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="white" align="left">
      <ul>
        <li>
          <ul>
            <li><a href="#OCR_1lI">1-l-I OCR Problems</a>               </li>
            <li><a href="#OCR_other">Other OCR Problems</a>             </li>
            <li><a href="#hand_notes">Handwritten Notes in Book</a>     </li>
            <li><a href="#bad_image">Bad Image</a>                      </li>
            <li><a href="#bad_text">Wrong Image for Text</a>            </li>
            <li><a href="#round1">Previous Proofreader Mistakes</a>     </li>
            <li><a href="#p_errors">Printer Errors/Misspellings</a>     </li>
            <li><a href="#f_errors">Factual Errors in Texts</a>         </li>
            <li><a href="#uncertain">Uncertain Items</a>                </li>
          </ul>
        </li>
      </ul>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h3><a name="prime">The Primary Rule</a> </h3>
<p> In doing your proofreading, the primary rule to follow is that the final electronic
    book seen by a reader, possibly many years in the future, should <b>accurately
    convey the intent of the author</b>. </p>
<p> So the general rule is <em>"Don't change what the author wrote!"</em>.  If the
    author spelled words oddly, leave them spelled that way.  If the author wrote
    outrageous racist or biased statements, leave them that way. If the author seems to
    put italics or a footnote every third word, leave them italicized or footnoted. </p>
<p> On the other hand, we do change minor printing items that don't affect the sense of
    what the author wrote.
  <br>
    For example, typesetters in the 1700's &amp; 1800's often inserted a &frac34;ths
    space before punctuation such as a semicolon or comma.  Our OCR scanners generally
    read this as a space character.  But when proofreading, we remove that space; since
    it distracts modern readers and removing it doesn't affect the meaning of the
    author's words.  Typesetters of that time also used the "long s" (&#131;) in words,
    which looks similar to a modern "f".  In English, the "long s" and the regular "s"
    are the same, so we make them all the regular "s". </p>

<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<h3><a name="summary">Summary Guidelines</a>                   </h3>
<p> The <a href="summary.pdf">Summary of Guidelines</a> is a short, 2-page
    printer-friendly (.pdf) document that summarizes the main points of these
    Guidelines, and gives examples of how to proofread.  Beginning Proofreaders are
    encouraged to print out this document and keep it handy while proofing.

<h3><a name="about">About This Document</a> </h3>
<p> This document is written in order to reduce formatting differences when proofreading
    of one book is distributed among many proofreaders, each working on different pages
    of the book.  This helps us all do formatting <em>the same way</em>.  That makes it
    easier for the post-proofreader to eventually combine all these proofed pages into
    one e-book.
  <br>
    <i>But it's not intended as any kind of a general editorial or typesetting
    rulebook</i>. </p>
<p> We've included in this document all the items that new users have asked about
    formatting while proofreading.  If there are any items missing, or items that you
    consider should be done differently, or if something is vague, please let us know.
    </p>
<p> This document is a work in progress. Help us to progress by sending in your
    suggested changes. </p>
    <!-- Send 'em where???? -->

<h3><a name="comments">Project Comments</a> </h3>
<p> On the interface page where you start proofing pages, there is a section called
    "Project Comments" containing info specific to that project (book). <em>Read these
    before you start proofing pages!</em> If the Project Manager wants you to format
    something in this book different than the general rules in these Guidelines, that
    will be noted here.  Instructions in the Project Comments <em>overide</em> the rules
    in these Guidelines, so follow them.  (This is also where the Project Manager gives
    you interesting tidbits of information about the books, like where the source came
    from, etc.) </p>

<h3><a name="forums">Forum/Discuss this Project</a> </h3>
<p> On the interface page where you start proofing pages, on the line "Forum", there is
    a link titled "Discuss this Project".  Clicking on that link will take you to a
    forum for this specific project. That is the place to ask questions about this book,
    inform the Project Manager about problems, etc.  Using this Forum is the recommended
    way to communicate with the Project Manager and other proofreaders who are working
    on this book. </p>

<h3><a name="prev_pg">Fixing errors on Previous Pages</a> </h3>
<p> Each project has a project comments page, which is loaded when you click on a
    project name from the list of projects available for proofing on your personal page
    (which is the page that appears when you log in).  </p>
<p> This page contains links to pages from this project that you have recently proofed.
    (If you haven't proofed any pages yet, there will be no links shown.) These links
    appear under one of two headings, depending on if you have told the system you have
    completed the proofing on a page or not.  </p>
<p> The two headings are "My Recently Completed" and "My Recently Proofread". Links to
    the fivemost recently proofed pages will show under each heading. Pages linked to
    from "My Recently Completed" are considered complete, and will proceed automatically
    to the next round when all other pages in the project have also been completed.
    Pages linked to from "My Recently Proofread" are waiting for you to complete them;
    they will not proceed automatically to the next round (neither will they hold the
    book up indefinitely: eventually, if not completed by you, the system will reclaim
    them, discard your changes, and make them available for someone else to proof).
    </p>
<p> When you first load a new page to proof, by clicking on the 'Start Proofing' link on
    the project comments page, a link to the page is created under 'My Recently
    Proofread'. Each time you press the "Save" button, this link is updated to point to
    the newly saved version of the page. You tell the system you have completed the
    proofing on a page by pressing either the "Save and Quit" or the "Save and Do
    Another" buttons. Pressing either of these buttons moves the link to the page from
    the "My Recently Proofread" section to the "My Recently Completed" section. This is
    the only way you can mark pages as complete and make sure your work will get through
    to the next round. Until you press one of those two buttons, the page is
    'incomplete', even if you press the "Quit" button.  </p>
<p> Pages listed under either "My Recently Completed" or "My Recently Proofed"  are
    available to you for to make proofing corrections or to finish proofing.  Just click
    on the link to the page.  So if you discover that you made a mistake on a page, or
    marked something incorrectly, you can click on that page here and re-open it to fix
    the error. (If it's already gone by, then use the  <a href="#forums">project
    forum</a> to leave a note for the next round.) </p>
<br>
<table border="0" cellspacing="0" width="100%" cellpadding="6">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Formatting of the...</font></td>
    </tr>
  </tbody>
</table>

<h3><a name="title_pg"><b>Front/Back Title Page</b></a> </h3>
<p> Leave only the title and author name, since due to Project Gutenberg procedures,
    that is all that will be put into the title page for final release of the e-book.
    Remove all references to year, city of publication, publisher, copyright date, etc.
    Put the title &amp; author name just as the book has it written, whether all
    capitals, upper &amp; lower case, etc.  Older books often show the first letter as a
    large &amp; ornate graphic--put this as just the letter.  (These pages are left in
    to simplify uploading of the project and for the folder to have the complete book in
    it for future reference.)</p>
    <!-- What about leaving in the dates on this page (but not publisher info)?  -->
<table width="100%" border="1"  cellpadding="4"
 cellspacing="0">
  <col width="256*"> <tbody>
    <tr>
      <td width="100%" valign="top">
      <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top"><img src="title.png" width="505"
          height="780" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>GREEN FANCY <br>
      <br>
      </p>
      <p>BY <br>
         GEORGE BARR McCUTCHEON <br>
      </p>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="toc">Table of Contents</a> </h3>
<p> Remove all page numbers from the table of contents, along with any ... or *** used
    to align the page numbers. Due to converting the book to electronic form, the page
    numbers will be removed from the entire book.  Put the Table of Contents items just
    as printed in the book, whether all capitals, upper &amp; lower case, etc. </p>
<table width="100%" border="1"  cellpadding="4"
 cellspacing="0">
  <col width="256*"> <tbody>
    <tr>
      <td width="100%" valign="top">
      <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="591" height="780"></p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p><tt>CONTENTS </tt></p>
      <p><tt>CHAPTER <br>
      </tt> </p>
      <tt>                                                          <br>
          I.     THE FIRST WAYFARER AND THE SECOND WAYFARER         <br>
          MEET AND PART ON THE HIGHWAY                              <br>
                                                                    <br>
          II.    THE FIRST WAYFARER LAYS HIS PACK ASIDE AND         <br>
          FALLS IN WITH FRIENDS                                     <br>
                                                                    <br>
          III.   MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,     <br>
          AND TWO MEN RIDE AWAY                                     <br>
                                                                    <br>
          IV.    AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT           <br>
          TRAGEDY, AND A MAN WHO SAID "THANK YOU"                   <br>
                                                                    <br>
          V.     THE FARM-BOY TELLS A GHASTLY STORY, AND AN         <br>
          IRISHMAN ENTERS                                           <br>
                                                                    <br>
          VI.    CHARITY BEGINS FAR FROM HOME, AND A STROLL IN      <br>
          THE WILDWOOD FOLLOWS                                      <br>
                                                                    <br>
          VII.   SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS ENCOUNTERS  <br>
                                                                    <br>
          VIII.  A NOTE, SOME FANCIES, AND AN EXPEDITION IN         <br>
          QUEST OF FACTS                                            <br>
                                                                    <br>
          IX.    THE FIRST WAYFARER, THE SECOND WAYFARER, AND       <br>
          THE SPIRIT OF CHIVALRY ASCENDANT                          <br>
                                                                    <br>
          X.     THE PRISONER OF GREEN FANCY, AND THE LAMENT OF     <br>
          PETER THE CHAUFFEUR                                       <br>
                                                                    <br>
          XI.    MR. SPROUSE ABANDONS LITERATURE AT AN EARLY        <br>
          HOUR IN THE MORNING                                       <br>
                                                                    <br>
          XII.   THE FIRST WAYFARER ACCEPTS AN INVITATION, AND      <br>
          MR. DILLINGFORD BELABORS A PROXY                          <br>
                                                                    <br>
          XIII.  THE SECOND WAYFARER RECEIVES TWO VISITORS AT       <br>
          MIDNIGHT                                                  <br>
                                                                    <br>
          XIV.   A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE       <br>
          OUTSIDE                                                   <br>
      </tt>
      <p> </p>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="blank_pg">Blank Page</a> </h3>
<p>Blank pages may be in the project you are working on due to the original book having
    a blank page and us trying to cut down on the work needed to find the page, remove
    it, and renumber the remaining pages.  Please put in the text box <tt>[Blank
    Page]</tt> if both the text box and the image are blank. If only one of them is
    blank, follow the directions for a <a href="#bad_image">Bad Image</a> or <a
     href="#bad_text">Bad Text</a>. </p>

<h3><a name="page_hf">Page Headers/Page Footers</a> </h3>
<p> Remove page headers and page footers (but not <a href="#footnotes">footnotes</a>)
    from the text. The page headers are normally at the top of the image and have a page
    number opposite them.  Page headers may be the same all through the book (often the
    title of the book and the authors name); they may be the same for each chapter
    (often the chapter number); or they may be different on each page (describing the
    action on that page).  Remove them all, regardless.
  <br>
    A <a href="#chap_head">chapter header</a> will start farther down the page and won't
    have the page numbers on the same line. Leave chapter headers in--see below. </p>
<p align="left" style="margin-bottom: 0cm;"><br>
</p>
<table width="100%" border="1"  cellpadding="4"
 cellspacing="0">
  <col width="256*"> <tbody>
    <tr>
      <td width="100%" valign="top">
      <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top"> <img src="foot.png" alt=""
          width="694" height="1068"><br>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
          <tt>In the United States?                                                  <br>
          [Footnote: The United States: "Its charter, the constitution. * * * Its flag the <br>
          symbol of its power; its seal, of its authority."--Dole.]                  <br>
          In a railroad? In a mining company?                                        <br>
          In a bank? In a church? In a college?                                      <br>
                                                                                     <br>
          Write a list of all the corporations that you know or have                 <br>
          ever heard of, grouping them under the heads &lt;i&gt;public&lt;/i&gt; and <br>
          &lt;i&gt;private&lt;/i&gt;.                                                <br>
                                                                                     <br>
          How could a pastor collect his salary if the church should                 <br>
          refuse to pay it?                                                          <br>
                                                                                     <br>
          Could a bank buy a piece of ground "on speculation?" To                    <br>
          build its banking-house on? Could a county lend money if it                <br>
          had a surplus? State the general powers of a corporation.                  <br>
          Some of the special powers of a bank. Of a city.                           <br>
                                                                                     <br>
          A portion of a man's farm is taken for a highway, and he is                <br>
          paid damages; to whom does said land belong? The road intersects           <br>
          the farm, and crossing the road is a brook containing                      <br>
          trout, which have been put there and cared for by the farmer;              <br>
          may a boy sit on the public bridge and catch trout from that               <br>
          brook? If the road should be abandoned or lifted, to whom                  <br>
          would the use of the land go?                                              <br>
                                                                                     <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*                                <br>
                                                                                     <br>
                                                                                     <br>
                                                                                     <br>
                                                                                     <br>
          CHAPTER XXXV.                                                              <br>
                                                                                     <br>
                                                                                     <br>
          COMMERCIAL PAPER.                                                          <br>
                                                                                     <br>
          &lt;b&gt;Kinds and Uses&lt;/b&gt;.--If a man wishes to buy some commodity  <br>
          from another but has not the money to pay for                              <br>
          it, he may secure what he wants by giving his written                      <br>
          promise to pay at some future time. This written                           <br>
          promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers to an oral promise <br>
          for several reasons, only two of which need be mentioned                   <br>
          here: first, because it is &lt;i&gt;prima facie&lt;/i&gt; evidence of      <br>
          the debt; and, second, because it may be more easily                       <br>
          transferred or handed over to some one else.                               <br>
                                                                                     <br>
          If J. M. Johnson, of Saint Paul, owes C. M. Jones,                         <br>
          of Chicago, a hundred dollars, and Nelson Blake, of                        <br>
          Chicago, owes J. M. Johnson a hundred dollars, it is                       <br>
          plain that the risk, expense, time and trouble of sending                  <br>
          the money to and from Chicago may be avoided,                              <br>
          </tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="chap_head">Chapter Headers</a> </h3>
<p> Leave chapter headers in the text. A chapter header will start a bit farther down
    the page than the <a href="#page_hf">page header</a> and won't have the page numbers
    on the same line.  Chapter Headers are often printed all caps, if so, keep them as
    all caps. Include 4 blank lines before the "CHAPTER XXX", and 2 blank lines after,
    between it and the chapter description.  Include the blank lines even if the chapter
    starts on a new page; there are no 'pages' in an e-book, so the blank lines are needed.
  <br> &nbsp;&nbsp;&nbsp;&nbsp;
    Old books often printed the first word of every chapter in all caps, sometimes even
    the first word of every paragraph; change these to a normal word (first letter only
    capitalized).
  <br> &nbsp;&nbsp;&nbsp;&nbsp;
    Watch out for a missing double quote at the start of the first paragraph, which some
    publishers did not include or which the OCR missed due to a large capital in the
    original.  If the author started the paragraph with dialog, insert the double quote,
    even if the publisher left it out or used a large capital instead.
</p>
<p align="left" style="margin-bottom: 0cm;"><br>
</p>
<table width="100%" border="1"  cellpadding="4"
 cellspacing="0">
  <col width="256*"> <tbody>
    <tr>
      <td width="100%" valign="top">
      <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top"> <img src="chap1.png" alt=""
          width="768" height="1040"><br>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <tt>
          GREEN FANCY <br>
                      <br>
                      <br>
                      <br>
                      <br>
          CHAPTER I   <br>
                <br>
                <br>
          THE FIRST WAYFARER AND THE SECOND WAYFARER            <br>
          MEET AND PART ON THE HIGHWAY                          <br>
                <br>
          A solitary figure trudged along the narrow            <br>
          road that wound its serpentinous way                  <br>
          through the dismal, forbidding depths of              <br>
          the forest: a man who, though weary and footsore,     <br>
          lagged not in his swift, resolute advance. Night      <br>
          was coming on, and with it the no uncertain           <br>
          prospects of storm. Through the foliage that overhung <br>
          the wretched road, his ever-lifting and apprehensive  <br>
          eye caught sight of the thunder-black, low-lying      <br>
          clouds that swept over the mountain and bore          <br>
          down upon the green, whistling tops of the trees.     <br>
                <br>
          At a cross-road below he had encountered a small     <br>
          girl driving homeward the cows. She was afraid       <br>
          of the big, strange man with the bundle on his back  <br>
          and the stout walking stick in his hand: to her a    <br>
          remarkable creature who wore "knee pants" and        <br>
          stockings like a boy on Sunday, and hob-nail shoes,  <br>
          and a funny coat with "pleats" and a belt, and a     <br>
          green hat with a feather sticking up from the band.
        </tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="para_side">Paragraph Side-Descriptions (SideNotes)</a> </h3>
<p> Some books will have short descriptions of the paragraph along the side of the text.
    Move these "Sidenotes" to just above the paragraph that they belong to, surrounded
    with <tt>[Sidenote: the-description-text]</tt>.  If the paragraph began on a
    previous page, put the Sidenote at the top of the page and mark it with <tt>*</tt>
    so that the post-proofreader can see that it belongs on the previous page.  Like
    this: <tt>*[Sidenote: the-description-text]</tt>. If there are multiple sidenotes
    for a single paragraph, put them one after another at the start of the paragraph, 
    each on their own line.</p>
<table width="100%" border="1"  cellpadding="4"
       cellspacing="0"> <col width="128*">
  <tbody>
    <tr valign="top">
      <td width="50%"> <p>Sample Image</p>
      </td>
      <td width="50%"> <p>Correct Text</p>
      </td>
    </tr>
    <tr valign="top">
      <td width="50%" align="center"><img src="side.png" alt=""
          width="502" height="248"><br>
      </td>
      <td width="50%">
        <p><tt>
          [Sidenote: The comparative size of the image depends on <br>
          the amount of light (30--39).]                          <br>
                                                                  <br>
          The eye will hold and retain in itself                  <br>
          the image of a luminous body better than                <br>
          that of a shaded object. The reason is that             <br>
          the eye is in itself perfectly dark and since           <br>
          two things that are alike cannot be distinguished,      <br>
          therefore the night, and other dark                     <br>
          objects cannot be seen or recognised by                 <br>
          the eye. Light is total contrary and gives              <br>
          more distinctness, and counteracts and differs          <br>
          from the usual darkness of the eye, hence               <br>
          it leaves the impression of its image.                  <br>
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="para_space">Paragraph Spacing/Indenting</a> </h3>
<p> Do not indent the start of paragraphs; instead put a blank line between paragraphs.
    See the <a href="#chap_head">Chapter Headers</a> image/text for an example. (But if
    all paragraphs are already indented, don't bother removing those spaces--that can be
    done automatically in post-proofing.) </p>

<h3><a name="mult_col">Multiple Columns</a> </h3>
<p> Break up multiple-column text into a single column.  Place the left-most column
    first and then the next one after it, and so on. You don't need to do anything
    to mark where the columns were split, just run them all together.
    </p>

<h3><a name="illust">Illustrations</a> </h3>
<p> Text for an illustration should be surrounded by <tt>[Illustration:
    the-text-caption]</tt>.  If there is no text caption, just place
    <tt>[Illustration]</tt> there. If it is in the middle of a paragraph or on the side
    of a paragraph, move the <tt>[Illustration: the-text] </tt>to either above or below
    the paragraph, based on where you can put it without it being in the middle of a
    paragraph.  If the text caption is printed on multiple lines, put it that way in
    your proofed text.  </p>
<p align="left" style="margin-bottom: 0cm;"><br>
</p>
<table width="100%" border="1"  cellpadding="4"
 cellspacing="0">
  <col width="256*"> <tbody>
    <tr>
      <td width="100%" valign="top">
      <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="570" height="780"> <br>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
      <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
          <p><tt>[Illustration: Martha told him that he had always been her ideal and <br>
                 that she worshipped him.                                             <br>
                 &lt;i&gt;Frontispiece                                                <br>
                 Her Weight in Gold&lt;/i&gt;]
          </tt></p>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="footnotes">Footnotes/Endnotes</a> </h3>
<p> Footnotes are normally done in-line; that is, the text of the footnote is moved from
    the bottom of the page to the place on the page where it is referenced in the text.
    But in some books, the Project Manager may ask that you leave the the footnotes
    out-of-line; that is, leave the text of the footnote at the bottom of the page. Do
    it that way when the Project Comments ask you to.  </p>
<p><b>In-Line Footnotes:</b>
    Replace the footnote number, *, dagger (&dagger; or &Dagger;), etc. that marks a
    footnote location with the actual text of the footnote, surrounded by <tt>[Footnote:
    ]</tt>.  That is, footnotes should be be moved from the bottom of the page to the
    place on the page where they are referenced in the text.  Many proofreaders start
    the <tt>[Footnote:</tt> on a new line, and also start the text following the
    <tt>]</tt> on a new line, because this makes it easier for the Second Round Proofer.
    (See below for an example.) </p>
<p> If a footnote was a continuation of a footnote from a previous page, move it to the
    top of the page and surround it with <tt>[*Footnote: ]</tt>.  (The <tt>*</tt>
    indicates that the footnote was continued, and brings it to the attention of the
    post-processor.  </p>
<p> If a footnote continues on the next page (the page ends before the footnote does),
    move the footnote text to where it is referenced on the page, surrounded by
    <tt>[Footnote:  *]</tt>.  (The <tt>*</tt> indicates that the footnote ended, and
    brings it to the attention of the post-processor.  </p>
<p> If a footnote or endnote is referenced in the text but does not appear on that page,
    keep the footnote/endnote number or marker and surround it with with brackets
    (<tt>[</tt> and <tt>]</tt>) and put a <tt>*</tt> next to it so the post-proofer can
    find it. (This is common in scientific & technical books, where footnotes are often
    grouped at the end of chapters.)
<p><b>Out-of-Line Footnotes:</b>
    For these, Surround the footnote number, *, or other character that marks a footnote
    location with brackets (<tt>[</tt> and <tt>]</tt>).  Don't leave any space before
    the <tt>[</tt>--keep it right next to the word being footnoted.  Then at the bottom
    of the page, where the text for that footnote it, surround it with <tt>[Footnote _:
    ]</tt>, keeping the footnote number or marker where the underline is.  </p>
<!--
    See the <a href="#page_hf">Page Headers/Footers</a>
    image/text for an example footnote.</p>
-->
<table border="1"  cellpadding="4" cellspacing="0" width="97%" align="center">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top" align="left">Original Text:</th>
    </tr>
    <tr>
      <td valign="top">The principal persons involved in this argument were
          Caesar<sup>1</sup>, former military                                    <br>
          leader and Imperator, and the orator Cicero<sup>2</sup>.
          Both were of the aristocratic                                          <br>
          (Patrician) class, and were quite wealthy.                             <br>
     <hr align="left" width="50%" noshade size=2>
     <font size=-1><sup>1</sup> Gaius Julius Caesar.                             <br>
     <font size=-1><sup>2</sup> Marcus Tullius Cicero. </font>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left">Proofed with In-Line Footnotes:</th>
    </tr>
    <tr>
      <td valign="top">
        <tt>
          The principal persons involved
          in this argument were Caesar                                           <br>
          [Footnote: Gaius Julius Caesar],                                       <br>
          former military                                                        <br>
          leader and Imperator, and the orator Cicero                            <br>
          [Footnote: Marcus Tullius Cicero].                                     <br>
          Both were of the aristocratic                                          <br>
          (Patrician) class, and were quite wealthy.
          </tt>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left">Proofed with Out-of-Line Footnotes: </th>
    </tr>
      <td valign="top">
        <tt>
          The principal persons involved
          in this argument were Caesar[1],
          former military                                                        <br>
          leader and Imperator, and the orator Cicero[2].
          Both were of the aristocratic                                          <br>
          (Patrician) class, and were quite wealthy.
     <hr align="left" width="50%" noshade size=2>
          [Footnote 1: Gaius Julius Caesar]                                      <br>
                                                                                 <br>
          [Footnote 2: Marcus Tullius Cicero]
      </td>
    </tr>
  </tbody>
</table>
<p> <b>Endnotes</b> are just footnotes that have been located together at the end of a
    chapter or at the end of the book, instead of on the bottom of each page.  If you
    are proofing a page with endnotes on it, surround the text of the note with
    <tt>[Footnote _: ]</tt>, keeping the footnote number or marker where the underline
    is.  Put a blank line after each one, so that they stay separate paragraphs when the
    text is reflowed in post-processing. </p>
<!-- Need an example of Endnotes, maybe? -->

<p> <b>Footnotes in Poetry or Tables</b> should be put on a separate line at the end of a stanza,
    or at the end of the poem (just after the closing <tt>*/</tt>), so as not to
    interrupt the flow of the Author's poetry. The same applies to Footnotes in Tables;
    put them at the bottom of the table so as not to mess up the table format.</p>
<table border="1"  cellpadding="4" cellspacing="0">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top">Original Footnoted Poetry</th>
      <th valign="top">Proofed Text</th>
    </tr>
    <tr>
      <td valign="top">Mary had a little lamb<sup>1</sup>                        <br>
     &nbsp;&nbsp;&nbsp;Whose fleece was white as snow                            <br>
                       And everywhere that Mary went                             <br>
     &nbsp;&nbsp;&nbsp;The lamb was sure to go!                                  <br>
     <hr align="left" width="50%" noshade size=2>
     <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,  <br>
     well known for the pure whiteness of their wool. </font>
      </td>
      <td valign="top">
        <tt> /*                                                                  <br>
          Mary had a little lamb[1]                                              <br>
          Whose fleece was white as snow                                         <br>
          And everywhere that Mary went                                          <br>
          The lamb was sure to go!                                               <br>
          */                                                                     <br>
          [Footnote 1: This lamb was obviously of the Hampshire breed,           <br>
          well known for the pure whiteness of their wool.]                      <br>
          </tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="italics">Italics</a> </h3>
<p> Text that is italicized should have <tt>&lt;i&gt;</tt> inserted at the start and
    <tt>&lt;/i&gt;</tt> inserted at the end of the italics. (Note the "/" in the closing
    symbol.) If the italics start at the top of a page, place a <tt>*</tt> in front of
    the <tt>&lt;i&gt;</tt> so that the post-proofreader can see if there was more on the
    previous page. If the italics go to the bottom of a page, place a <tt>*</tt> after
    the <tt>&lt;/i&gt;</tt> so that the post-proofreader can see if there is more on the
    next page.
  <br>
    (Note: either lower-case <tt>&lt;i&gt;</tt> or upper-case <tt>&lt;I&gt;</tt> is
    OK, whichever you prefer.) </p>
<p>
    Punctuation goes
    OUTSIDE the italics, unless it is an entire sentence or section that is being
    italicized. That is not a big deal, but is an answer for those curious.
  <br>
    See the <a href="#illust">Illustration</a> image for an example of how to do the
    italics.  </p>
<p>
    Many fonts, especially older ones, used the same design for numbers in both regular
    and italics.  So for dates &amp; similar phrases, just mark the whole phrase as
    italics, rather than marking the words as italics and the numbers as non-italics.
    For example:
    </p>

<table border="1"  cellpadding="4" cellspacing="0">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top">Original Text</th>
      <th valign="top">Correct Text</th>
      <th valign="top">Over-corrected Text</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><tt>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</tt> </td>
      <td valign="top"><tt>&lt;i&gt;Enacted&lt;/i&gt; 4 &lt;i&gt;July&lt;/i&gt;, 1776</tt> </td>
    </tr>
  </tbody>
</table>

<h3><a name="bold">Bold Text</a> </h3>
<p> Bold text (text printed in a heavier typeface) should be marked with
    <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt>after it.
    See the <a href="#page_hf">Page Headers/Footers</a> image/text for an example.
  <br>
    (Note: either lower-case <tt>&lt;b&gt;</tt> or upper-case <tt>&lt;B&gt;</tt> is
    OK, whichever you prefer.) </p>
  <br>
    Previously, we used to change bold text into all capitals.  We no longer do it that
    way, unless the Project Manager specifies that method in the <a
    href="#comments">Project Comments</a>. </p>

<h3><a name="supers">Superscripts</a> </h3>
<p> Older books often abbreviated words as contractions, and printed them as
    superscripts, for example: <font color="red">
  <br> &nbsp; &nbsp; &nbsp; &nbsp; Gen<sup>rl</sup> Washington defeated L<sup>d</sup>
    Cornwall's army. <br>    </font> Unless the <a href="#comments">Project Comments</a>
    specify something else, insert a single quote (apostrophe) to identify this as an
    abbreviation/contraction, like this:
  <br> &nbsp; &nbsp; &nbsp; &nbsp;    <tt>Gen'rl Washington defeated L'd Cornwall's
    army.</tt> <br> Only change to the full word if the <a href="#comments">Project
    Comments</a> specificly say to do so, like this:
  <br> &nbsp; &nbsp; &nbsp; &nbsp; <tt>General Washington defeated Lord Cornwall's
    army.</tt> </p>

<h3><a name="underl">Underlined Text</a> </h3>
<p> Mark as <a href="#italics">Italics</a>, with <tt>&lt;i&gt;</tt> and
    <tt>&lt;/i&gt;</tt>, unless the <a href="#comments">Project Comments</a> specify
    something else for this book.  Underlining was used to indicate italics when the
    typesetter was unable to actually italicize the text, for example in a typewritten
    document.  </p>

<h3><a name="#font_sz">Font size changes</a> </h3>
<p> Don't do anything to mark changes in font size.  When the book is converted to
    electronic form, it will all be plain ASCII text in the same font.   The exception
    to this is when the font size changes to indicate a section of <a
    href="#poetry">poetry</a> or <a href="#block_qt">block quotations</a>; then mark the
    text as specified for those (<tt>/*</tt> at the start and <tt>*/</tt> at the end. </p>

<h3><a name="word_caps">Word in all Caps or Small Caps</a> </h3>
<p> If a word or words in the text are printed in all capital letters (including small
    caps), leave it that way in your proofed copy.  The exception to this is the <a
    href="#chap_head">first word of a chapter or paragraph</a>: many old books put the
    first word of these in all caps; this should be changed to a normal word (first
    letter capitalized, the rest lower case). </p>

<h3><a name="drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a> </h3>
<p> Often the opening letter in a chapter, section, or paragraph is printed as
    a large &amp; ornate graphic of the letter--proof this as just the letter.</p>

<h3><a name="eol_hyphen">End-of-line Hyphenation</a> </h3>
<p> Unless it really is a hyphenated word like well-meaning, remove the end-of-line
    hyphenation, and join the two halves of a previously hyphenated word back together.
    Keep the joined word on the top line, and put a line break after it to preserve the
    line formatting -- this makes it easier for the 2nd Round Proofreader.  See the <a
    href="#chap_head">Chapter Headers</a> image for examples of each kind
    (<tt>nar-row</tt> turns into <tt>narrow</tt>, but <tt>low-lying</tt> keeps the
    hyphen). </p>
<p> Words like to-day and to-morrow that we don't commonly hyphenate now were often
    hyphenated in the old books we are working on.  Leave them hyphenated the way the
    author did. If you're not sure if the author hyphenated it or not, leave the hyphen,
    put an <tt>*</tt> after it, and join the word together.  Like this:
    <tt>to-*day</tt>.  The asterisk will bring it to the attention of the post
    processor, who has access to all the pages, and can determine how the author
    typically wrote this word.  </p>

<h3><a name="eop_hyphen">End-of-page Hyphenation</a> </h3>
<p> Leave the hyphen at the end of the last line, but mark it with a <tt>*</tt> after
    the hyphen so the post-proofer will notice it. On pages that start with part of a
    word from the previous page, place a <tt>*</tt> before the word.</p>

<h3><a name="em_dashes">Dashes, Hyphens, and Minus Signs</a> </h3>
<p> There are generally four such marks you will see in books:
  <ol compact>
    <li><i>Hyphens</i>.  These are used to <b>join</b> words together, or sometimes to
        join prefixes or suffixes to a word.
    <br>Leave these as a single hyphen, with no spaces on either side.
    <li><i>En-dashes</i>.  These are just a little longer, and are used for a
        <b>range</b> of numbers, or for a mathematical <b>minus</b> sign.
    <br>Proof these too as a single hyphen.  Spaces before or after is determined by the
        way it was done in the book; usually no spaces in number ranges, usually spaces
        around mathematical minus signs, sometimes both sides, sometimes just before.
    <li><i>Em-dashes &amp; long dashes</i>.  These serve as <b>separators</b> between
        words--sometimes for emphasis like this--or when a speaker gets a word caught in
        his throat--!
    <br>Proof these as two hyphens. Don't leave a space before or after,
        even if it looks like there was a space in the original book image.
    <li><i>Still longer dashes</i>.  These represent <b>omitted</b> or <b>censored</b>
        words or names.
    <br>Proof these as 4 hyphens (6 for a really long dash).  When it represents a word,
        we leave appropriate space around it like it's really a word.  If it's only part
        of a word, then no spaces -- join it with the rest of the word.
  </ol>
<p> Note: If a dash appears at the start or end of a line of your OCR'd text, join it with the
    other line so that there are no spaces or line breaks around it. Only if the
    author used a dash to start or end the paragraph or line of poetry or dialog
    should you leave it at the start or end of a line.  </p>
<p>
    Some examples:
    </p>

<table align="center" border="1"  cellpadding="4" cellspacing="0">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top">Original Image</th>
      <th valign="top">Proofed Text</th>
      <th valign="top">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached     </td>
      <td valign="top"><tt>semi-detached</tt> </td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">four-part harmony </td>
      <td valign="top"><tt>four-part harmony</tt> </td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25 </td>
      <td valign="top"><tt>See pages 21-25</tt> </td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt> </td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt> </td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague on both your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>I am hurt;--A plague on both your houses!--I am dead.</tt> </td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt> </td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified, </td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt> </td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified, </td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt> </td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt> </td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt> </td>
      <td>long dash</td>
    </tr>
  </tbody>
</table>

<h3><a name="abbrev">Abbreviations</a> </h3>
<p> Remove all spaces in abbreviations, even if it appears that the typesetter included
    spaces (or partial spaces) in the printed version.  For example, proof as <tt>H.M.S.
    Pinafore</tt>, not as <tt>H.&nbsp;M.&nbsp;S.&nbsp;Pinafore</tt>.  Also proof as
    <tt>G.B. Shaw</tt>, not as <tt>G.&nbsp;B.&nbsp;Shaw</tt>.  (This avoids the problem
    of having the abbreviation being broken across lines when text is rewrapped on some
    e-book screens, which would be much less readable.) </p>

<h3><a name="contract">Contractions</a> </h3>
<p> Remove any extra space in contractions, for example: <tt>would&nbsp;n't</tt> should
    be <tt>wouldn't</tt>.  (This was an early printers convention, where they retained
    the space to indicate that 'would' and 'not' were originally separate words.  If you
    see the extra space next to the apostrophe, (<tt>wouldn&nbsp;'t</tt>) that is
    probably an OCR scanning issue.  Remove the extra space in either case.) </p>

<h3><a name="poetry">Poetry/Epigrams</a> </h3>
<p> Mark poetry or epigrams so the post-processor can find the poetry quicker. Insert a
    separate line with <tt>/*</tt> at the start of the poetry or epigram and a separate
    line with <tt>*/</tt> at the end. </p>
<p> Don't bother trying to center lines of poetry, even if it was centered on the
    printed page; that won't work for an e-book viewed with many different screen sizes.
    Just put it all at the left margin.  (If there is some other indenting that you
    think is needed to reflect the author's meaning, then try to duplicate the format of
    the original.) But for most poetry, the meaning is in the words and line breaks;
    indenting format is not critical.  </p>
<p> Sometimes a line of poetry doesn't fit on the printed page, and part of it is split
    onto the next line (usually indented quite a bit, and usually starts with a
    lower-case letter).  Rejoin these words back onto the end of previous line.  (Since
    our final e-book does not have 'pages', we don't have to worry about a line being
    too long for the 'page'.) </p>
<p> <b>Footnotes</b> in poetry should go at the end of the poem or stanza.  See
    <a href="#footnotes">footnotes</a> for details.
</p>

<table width="100%" border="1"  cellpadding="4"
       cellspacing="0"> <col width="256*">
  <tbody>
    <tr>
      <td width="100%" valign="top"> <p>Sample Image</p>
      </td>
    </tr>
    <tr align="center">
      <td width="100%" valign="top"> <img src="poetry.png" alt=""
          width="372" height="211"> <br>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top"> <p>Correct Text</p>
      </td>
    </tr>
    <tr>
      <td width="100%" valign="top">
          <tt>
                                               <br>
          /*                                   <br>
          Pansies, lilies, kingcups, daisies,  <br>
          Let them live upon their praises;    <br>
          Long as there's a sun that sets,     <br>
          Primroses will have their glory;     <br>
          Long as there are violets            <br>
          They will have a place in story;     <br>
          But for flowers my bowls to fill,    <br>
          Give me just the daffodil.           <br>
          */                                   <br>
                                               <br>
          As Wordsworth ought to have said.
          </tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="letter">Letter Indentation</a> </h3>
<p> Leave letters unindented, like you would for <a href="#para_space">paragraphs</a>.
    Instead of indenting put a blank line before the start of the letter. </p>

<h3><a name="lists">Lists of Items</a> </h3>
<p> Mark these similar to poetry; insert a line with <tt>/*</tt> before the list, and
    insert a line with <tt>*/</tt> after the list.  This marking tells the post
    processor that this section of text should be kept as seperate lines, and not
    re-flowed into paragraphs.  Mark it this way for any such list that should not be
    reformatted, including lists of questions &amp; answers, items in a recipe, etc.
    (But often in a cookbook made up mostly of recipes the Project Manager will say not
    to bother marking each list of ingredients.) </p>

<h3><a name="tables">Tables</a> </h3>
<p> Mark tables so the post-processor can find them by surrounding them with <tt>/*</tt>
    and <tt>*/</tt>, like <a href="#poetry">poetry</a> above. Format the table with
    spaces to look approximately like the original table.  Don't make the table wider
    than 70 characters. <br> It's often hard to format tables in plain ASCII text; just
    do your best. </p>
<p> <b>Footnotes</b> in tables should go at the end of the table.  See
    <a href="#footnotes">footnotes</a> for details.
</p>

<h3><a name="block_qt">Block Quotations</a> </h3>
<p> These are long quotations (typically several lines) included in the text. They are
    often printed narrower (wider margins) and in a smaller font size.  Mark block
    quotations so the post-processor can find them by surrounding them with <tt>/*</tt>
    and <tt>*/</tt>, like <a href="#poetry">poetry</a> above.  </p>

<h3><a name="double_q">Double Quotes</a> </h3>
   <!-- This first sentence is unclear to me! -->
<p> Single spacing around quote marks and punctuation is the correct formatting for the
    text. Please try to just use the plain <font color="red">"</font> double quote,
    since the directional double quotes <font color="red">&ldquo;</font> and <font
    color="red">&rdquo;</font> will have to be replaced to make it ASCII compliant.</p>
<p> For quotes from non-English languages, use the quotation marks that are appropriate
    to that language.  For example, in German mark quotations <font color="red">
    &bdquo;</font><tt>like this</tt><font color="red">&rdquo;</font>; and in French mark
    them with guillemets <font color="red">&laquo;</font>&nbsp;<tt>like this</tt>&nbsp;
    <font color="red">&raquo;</font>.  (As always, the Project Manager might instruct
    you to do these differently for a particular book). </p>
<p> Also, do not change single quotes to double quotes.  Leave them as the Author wrote
    them.  See the <a href="#chap_head">Chapter Headers</a> image and text for an
    example. </p>

<h3><a name="single_q">Single Quotes</a> </h3>
<p> Single quotes are used for a variety of purposes.  They sometimes are used
    throughout where current usage would be double quotes--they should be left as
    printed.  They are also used for slang words or shortened versions (contractions).
    Try to put them the way the image shows them.  Last of all, please try to just use
    the plain ASCII <tt>'</tt> single quote (apostrophe) to make it easier for
    everyone.</p>

<h3><a name="quote_ea">Quote Marks on each line</a>  </h3>
<p> Old books sometimes put quote marks at the beginning of each line of a quotation;
    remove them except for the first line.  But if the quotation goes on for multiple
    paragraphs, each paragraph should have an opening quote mark on the first line of
    the paragraph.  Often there is no closing quote mark until the very end of the
    quotation, after the last paragraph; if so, leave it that way.  Sometimes that last
    paragraph of the quotation is on the next page, so you will not see any closing
    quote mark at all.  If so, leave it that way -- don't add closing quote marks that
    aren't on the page image.  (Note that this means that the proofed text for
    multi-paragraph quotes will have more opening quotes than ending quotes.  That's
    fine, our post-proofing software expects this.) </p>

<h3><a name="period_s">Periods Between Sentences</a> </h3>
<p> Single space after periods (not two spaces). But don't bother to remove double
    spaces after periods if they're already in the scanned text -- we can do that
    automatically in post-processing.  See the <a href="#chap_head">Chapter Headers</a>
    image and text for an example. </p>

<h3><a name="punctuat">Punctuation characters</a> </h3>
<p> In general, there is no space before punctuation characters (except the opening
    quote). Books typeset in the 1700's &amp; 1800's often had a partial space inserted
    before punctuation such as a semicolon or comma.  If scanned text has a space before
    punctuation, remove it. </p>
<table border="1"  cellpadding="4" cellspacing="0">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top">Scanned Text</th>
      <th valign="top">Correct Text</th>
    </tr>
    <tr>
      <td valign="top"><tt>and so it goes&ensp;; ever and ever.</tt></td>
      <td valign="top"><tt>and so it goes; ever and ever.</tt> </td>
    </tr>
  </tbody>
</table>

<h3><a name="line_br">Line Breaks</a> </h3>
<p> Leave all line breaks in so that the next proofreader can compare the text easily.
    Especially watch this when fixing <a href="#eol_hyphen">hyphenated words</a>.  Extra
    blank lines that are not in the image should be removed.  But a blank line at
    the top or bottom of the page is OK--these are removed automatically in
    post-processing.  (Exceptions: the way we mark text for
    <a href="#footnotes">Footnotes</a>, and for
    <a href="#poetry">Poetry/Epigrams</a> will sometimes cause extra line breaks.)
    See the <a href="#chap_head">Chapter Headers</a> image and text for
    an example. </p>
<!-- We should have an example right here for this. -->

<h3><a name="extra_sp">Extra spaces or tabs between Words</a> </h3>
<p> Extra spaces between words are a common OCR error.  You don't need to bother
    removing those spaces--that can be done automatically in post-proofing.  Same with
    tab characters instead of spaces--those too can be fixed automatically.  But extra
    spaces around punctuation, em-dashes, quote marks, etc. do need to be removed when
    they seperate the symbol from the word.  For example, in <b>A
    horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse.</b> the space between "horse"
    and the semicolon needs to be removed.  But the 2 spaces after the semicolon are
    OK--you needn't bother deleting one of them. </p>

<h3><a name="line_no">Line Numbers</a> </h3>
<p> These are numbers in the margin for each line -- common in some poetry books.
    Since the text of poetry will not be reformatted in the e-book version, line 
    numbers should be kept. In ordinary text, that will be reformatted, they should be removed.</p>
<!-- We need an example image and text for this. -->

<h3><a name="extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></h3>
<p> Some pages will have extra spacing between paragraphs due to "thought breaks",
    change in scene, or a bit of suspense. These are intended by the author, so we
    preserve them by putting a blank line, 5 *'s indented 7 spaces and then 7 spaces
    apart, like so:<br>
<tt><br>
end of the line of text.<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*<br>
<br>
Text starts up again.</tt><br>
(The enhanced proofing interface has this separator available to cut
&amp; paste.) <br> See the <a href="#page_hf">Page Headers/Footers</a>
image/text for an example.</p>

<h3><a name="period_p">Period Pause "..." (Ellipsis)</a> </h3>
<p> Leave a space before the three dots, and a space after.  The exception is at the end
    of a sentence, when there would be no space, four dots, and a space after.  This is
    also the case for any other ending punctuation mark: the 3 dots follow immediately,
    without any space.  For example:
    <tt>
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
    </tt></p>

<h3><a name="a_chars">Accented/Non-ASCII Characters</a> </h3>
<p> Try to put in the proper accented or Non-ASCII characters. <br>
    But there is a problem with using accented characters -- they are upper
    ASCII, or characters above code 127. This can cause display problems for
    any computer not using the Western European character set (MS Windows
    codepage 1252 / ISO 8859-1 or -15). Furthermore, Apple never accepted
    ISO 8859 and some characters don't match up (a good example is the
    curly open quote (0147), which Apple maps to an accented capital O).
    Use with caution. </p>
<p> <b>For Windows</b>, there are several ways to get these characters: </p>
<ul compact>
  <li>you can use the character map program
     (Start: Run: charmap) to select an individual letter, and then cut &amp; paste.
  </li>
  <li>if using the enhanced proofing interface, the <i>more</i> tag creates a pop-up
      window containing these characters, which you can then cut &amp; paste.
  </li>
  <li>or you can type the Alt+NumberPad shortcut codes for these characters.
      <br>(They're a lot faster than using cut &amp; paste,
          once you get used to the codes.)
      <br>You hold the Alt key and type the four digits on the
          <i>Number Pad</i> -- the number row over the letters won't work.
      <br>And you must type all 4 digits, including the leading 0.
          Note that the capital version of a letter is 32 less than the lower case.
      <br>(This is for the US-English keyboard layout. It may not work for other keyboards.)
      <br>The table below shows the codes we use.
          (<a href="charwin.pdf">Print-friendly version of this table)</a>
      <br>Don't use other special characters unless the Project Manager tells you to in
          the <a href="#comments">Project Comments</a>.
  </li>
</ul>
<br>
<table align=center border="6" rules="all">
  <tbody>
  <tr bgcolor=cornsilk  >
      <th colspan=14>Windows Shortcuts for Upper ASCII symbols</th>
  <tr bgcolor=cornsilk  >
      <th colspan=2>grav&egrave; </th>
      <th colspan=2>acut&eacute; (&eacute;gu)</th>
      <th colspan=2>^circumflex</th>
      <th colspan=2>~ tilde    </th>
      <th colspan=2>&uuml;mlaut</th>
      <th colspan=2>&deg; ring </th>
      <th colspan=2>&AElig; ligature</th>
  <tr><td align=center bgcolor=mistyrose>&agrave; </td><td>Alt-0224</td>
      <td align=center bgcolor=mistyrose>&aacute; </td><td>Alt-0225</td>
      <td align=center bgcolor=mistyrose>&acirc;  </td><td>Alt-0226</td>
      <td align=center bgcolor=mistyrose>&atilde; </td><td>Alt-0227</td>
      <td align=center bgcolor=mistyrose>&auml;   </td><td>Alt-0228</td>
      <td align=center bgcolor=mistyrose>&aring;  </td><td>Alt-0229</td>
      <td align=center bgcolor=mistyrose>&aelig;  </td><td>Alt-0230&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&Agrave; </td><td>Alt-0192</td>
      <td align=center bgcolor=mistyrose>&Aacute; </td><td>Alt-0193</td>
      <td align=center bgcolor=mistyrose>&Acirc;  </td><td>Alt-0194</td>
      <td align=center bgcolor=mistyrose>&Atilde; </td><td>Alt-0195</td>
      <td align=center bgcolor=mistyrose>&Auml;   </td><td>Alt-0196</td>
      <td align=center bgcolor=mistyrose>&Aring;  </td><td>Alt-0197</td>
      <td align=center bgcolor=mistyrose>&AElig;  </td><td>Alt-0198&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&egrave; </td><td>Alt-0232</td>
      <td align=center bgcolor=mistyrose>&eacute; </td><td>Alt-0233</td>
      <td align=center bgcolor=mistyrose>&ecirc;  </td><td>Alt-0234</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&euml;   </td><td>Alt-0235</td>
      <td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
  <tr><td align=center bgcolor=mistyrose>&Egrave; </td><td>Alt-0200</td>
      <td align=center bgcolor=mistyrose>&Eacute; </td><td>Alt-0201</td>
      <td align=center bgcolor=mistyrose>&Ecirc;  </td><td>Alt-0202</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&Euml;   </td><td>Alt-0203</td>
      <td>                                        </td><td>        </td>
      <th colspan=2>                              </th>
  <tr><td align=center bgcolor=mistyrose>&igrave; </td><td>Alt-0236</td>
      <td align=center bgcolor=mistyrose>&iacute; </td><td>Alt-0237</td>
      <td align=center bgcolor=mistyrose>&icirc;  </td><td>Alt-0238</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&iuml;   </td><td>Alt-0239</td>
      <td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
  <tr><td align=center bgcolor=mistyrose>&Igrave; </td><td>Alt-0204</td>
      <td align=center bgcolor=mistyrose>&Iacute; </td><td>Alt-0205</td>
      <td align=center bgcolor=mistyrose>&Icirc;  </td><td>Alt-0206</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor=cornsilk>/ slash</th>
      <th colspan=2 bgcolor=cornsilk>&OElig; ligature</th>
  <tr><td align=center bgcolor=mistyrose>&ograve; </td><td>Alt-0242</td>
      <td align=center bgcolor=mistyrose>&oacute; </td><td>Alt-0243</td>
      <td align=center bgcolor=mistyrose>&ocirc;  </td><td>Alt-0244</td>
      <td align=center bgcolor=mistyrose>&otilde; </td><td>Alt-0245</td>
      <td align=center bgcolor=mistyrose>&ouml;   </td><td>Alt-0246</td>
      <td align=center bgcolor=mistyrose>&oslash; </td><td>Alt-0248</td>
      <td align=center bgcolor=mistyrose>&oelig;  </td><td>Use "oe"&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&Ograve; </td><td>Alt-0210</td>
      <td align=center bgcolor=mistyrose>&Oacute; </td><td>Alt-0211</td>
      <td align=center bgcolor=mistyrose>&Ocirc;  </td><td>Alt-0212</td>
      <td align=center bgcolor=mistyrose>&Otilde; </td><td>Alt-0213</td>
      <td align=center bgcolor=mistyrose>&Ouml;   </td><td>Alt-0214</td>
      <td align=center bgcolor=mistyrose>&Oslash; </td><td>Alt-0216</td>
      <td align=center bgcolor=mistyrose>&OElig;  </td><td>Use "Oe"&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&ugrave; </td><td>Alt-0249</td>
      <td align=center bgcolor=mistyrose>&uacute; </td><td>Alt-0250</td>
      <td align=center bgcolor=mistyrose>&ucirc;  </td><td>Alt-0251</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&uuml;   </td><td>Alt-0252</td>
  <tr><td align=center bgcolor=mistyrose>&Ugrave; </td><td>Alt-0217</td>
      <td align=center bgcolor=mistyrose>&Uacute; </td><td>Alt-0218</td>
      <td align=center bgcolor=mistyrose>&Ucirc;  </td><td>Alt-0219</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&Uuml;   </td><td>Alt-0220</td>
      <th colspan=2 bgcolor=cornsilk>currency     </th>
      <th colspan=2 bgcolor=cornsilk>mathematics  </th>
  <tr><td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&yuml;   </td><td>Alt-0255</td>
      <td align=center bgcolor=mistyrose>&cent;   </td><td>Alt-0162</td>
      <td align=center bgcolor=mistyrose>&plusmn; </td><td>Alt-0177</td>
  <tr><td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
      <th colspan=2 bgcolor=cornsilk>literary     </th>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&Yuml;   </td><td>Alt-0159</td>
      <td align=center bgcolor=mistyrose>&pound;  </td><td>Alt-0163</td>
      <td align=center bgcolor=mistyrose>&times;  </td><td>Alt-0215</td>
  <tr><th colspan=2 bgcolor=cornsilk>&ccedil;edilla </th>
      <th colspan=2>                              </th>
      <td align=center bgcolor=mistyrose>&copy;   </td><td>Alt-0169</td>
      <th colspan=2 bgcolor=cornsilk>~ tilde      </th>
      <th colspan=2 bgcolor=cornsilk>punctuation  </th>
      <td align=center bgcolor=mistyrose>&yen;    </td><td>Alt-0165</td>
      <td align=center bgcolor=mistyrose>&divide; </td><td>Alt-0247</td>
  <tr><td align=center bgcolor=mistyrose>&ccedil; </td><td>Alt-0231</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&reg;    </td><td>Alt-0174</td>
      <td align=center bgcolor=mistyrose>&ntilde; </td><td>Alt-0241</td>
      <td align=center bgcolor=mistyrose>&iquest; </td><td>Alt-0191</td>
      <td align=center bgcolor=mistyrose>&euro;   </td><td>Alt-0128</td>
      <td align=center bgcolor=mistyrose>&not;    </td><td>Alt-0172</td>
  <tr><td align=center bgcolor=mistyrose>&Ccedil; </td><td>Alt-0199</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&trade;  </td><td>Alt-0153</td>
      <td align=center bgcolor=mistyrose>&Ntilde; </td><td>Alt-0209</td>
      <td align=center bgcolor=mistyrose>&iexcl;  </td><td>Alt-0161</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&deg;    </td><td>Alt-0186</td>
  </tbody>
</table>
  &dagger;&nbsp;
    Note that the ligatures &aelig; &amp; &AElig; (and even more so the &oelig; and
    &OElig; ones) are becoming obsolete in English.  In most cases, the two individual
    letters have replaced them.  (Thus &AElig;sop's Fables becomes Aesop's Fables.)
    Check the <a href="#comments">Project Comments</a> to see if the Project Manager
    wants you to use the ligature, otherwise use the two individual letters.
<p> <b>For Apple Macs</b>, there are also several ways to get these characters: </p>
<ul compact>
  <li>you can use the Apple Key Caps program as a reference.  (In OS 9 &amp; earlier, it's
      located in the Apple Menu; in OS X, it's located in Applications, Utilities folder.)
      This brings up a picture of the keyboard, and pressing shift, opt, command, or
      combinations of those keys shows how to produce each character.  Use this
      reference to see how to type that character, or you can cut &amp; paste it from
      here to the document.
  </li>
  <li>if using the enhanced proofing interface, the
      <i>more</i> tag creates a pop-up window containing these characters, which you can
      then cut &amp; paste.
  <li>or you can type the Apple Opt- shortcut codes for these characters.
      <br>(They're a lot faster than using cut &amp; paste,
          once you get used to the codes.)
      <br>You hold the Opt key and type the accent symbol, then type the letter to be accented
          (or, for some codes, only hold the Opt key and type the symbol).
      <br>(This is for the US-English keyboard layout. It may not work for other keyboards.)
      <br>The table below shows the codes we use.
          (<a href="charapp.pdf">Print-friendly version of this table)</a>
      <br>Don't use other special characters unless the Project Manager tells you to in
          the <a href="#comments">Project Comments</a>.
  </li>
</ul>
<br>
<table align=center border="6" rules="all">
  <tbody>
  <tr bgcolor=cornsilk  >
      <th colspan=14>Apple Mac Shortcuts for Upper ASCII symbols</th>
  <tr bgcolor=cornsilk  >
      <th colspan=2>grav&egrave; </th>
      <th colspan=2>acut&eacute; (&eacute;gu)</th>
      <th colspan=2>^circumflex</th>
      <th colspan=2>~ tilde    </th>
      <th colspan=2>&uuml;mlaut</th>
      <th colspan=2>&deg; ring </th>
      <th colspan=2>&AElig;ligature</th>
  <tr><td align=center bgcolor=mistyrose>&agrave; </td><td>Opt-~, a</td>
      <td align=center bgcolor=mistyrose>&aacute; </td><td>Opt-e, a</td>
      <td align=center bgcolor=mistyrose>&acirc;  </td><td>Opt-i, a</td>
      <td align=center bgcolor=mistyrose>&atilde; </td><td>Opt-n, a</td>
      <td align=center bgcolor=mistyrose>&auml;   </td><td>Opt-u, a</td>
      <td align=center bgcolor=mistyrose>&aring;  </td><td>Opt-a   </td>
      <td align=center bgcolor=mistyrose>&aelig;  </td><td>Opt-' &nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&Agrave; </td><td>Opt-~, A</td>
      <td align=center bgcolor=mistyrose>&Aacute; </td><td>Opt-e, A</td>
      <td align=center bgcolor=mistyrose>&Acirc;  </td><td>Opt-i, A</td>
      <td align=center bgcolor=mistyrose>&Atilde; </td><td>Opt-n, A</td>
      <td align=center bgcolor=mistyrose>&Auml;   </td><td>Opt-u, A</td>
      <td align=center bgcolor=mistyrose>&Aring;  </td><td>Opt-A   </td>
      <td align=center bgcolor=mistyrose>&AElig;  </td><td>Opt-" &nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&egrave; </td><td>Opt-~, e</td>
      <td align=center bgcolor=mistyrose>&eacute; </td><td>Opt-e, e</td>
      <td align=center bgcolor=mistyrose>&ecirc;  </td><td>Opt-i, e</td>
      <td>                                        </td><td>        </td>
      <td align=center bgcolor=mistyrose>&euml;   </td><td>Opt-u, e</td>
      <td>                                        </td><td>        </td>
      <td>                                        </td><td>        </td>
  <tr><td align=center bgcolor=mistyrose>&Egrave; </td><td>Opt-~, E</td>
      <td align=center bgcolor=mistyrose>&Eacute; </td><td>Opt-e, E</td>
      <td align=center bgcolor=mistyrose>&Ecirc;  </td><td>Opt-i, E</td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&Euml;   </td><td>Opt-u, E</td>
      <td>                                           </td><td>     </td>
  <tr><td align=center bgcolor=mistyrose>&igrave; </td><td>Opt-~, i</td>
      <td align=center bgcolor=mistyrose>&iacute; </td><td>Opt-e, i</td>
      <td align=center bgcolor=mistyrose>&icirc;  </td><td>Opt-i, i</td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&iuml;   </td><td>Opt-u, i</td>
      <td>                                           </td><td>     </td>
      <td>                                           </td><td>     </td>
  <tr><td align=center bgcolor=mistyrose>&Igrave; </td><td>Opt-~, I</td>
      <td align=center bgcolor=mistyrose>&Iacute; </td><td>Opt-e, I</td>
      <td align=center bgcolor=mistyrose>&Icirc;  </td><td>Opt-i, I</td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor=cornsilk>/ slash</th>
      <th colspan=2 bgcolor=cornsilk>&OElig; ligature</th>
  <tr><td align=center bgcolor=mistyrose>&ograve; </td><td>Opt-~, o</td>
      <td align=center bgcolor=mistyrose>&oacute; </td><td>Opt-e, o</td>
      <td align=center bgcolor=mistyrose>&ocirc;  </td><td>Opt-i, o</td>
      <td align=center bgcolor=mistyrose>&otilde; </td><td>Opt-n, o</td>
      <td align=center bgcolor=mistyrose>&ouml;   </td><td>Opt-u, o</td>
      <td align=center bgcolor=mistyrose>&oslash; </td><td>Opt-o   </td>
      <td align=center bgcolor=mistyrose>&oelig;  </td><td>Use "oe"&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&Ograve; </td><td>Opt-~, O</td>
      <td align=center bgcolor=mistyrose>&Oacute; </td><td>Opt-e, O</td>
      <td align=center bgcolor=mistyrose>&Ocirc;  </td><td>Opt-i, O</td>
      <td align=center bgcolor=mistyrose>&Otilde; </td><td>Opt-n, O</td>
      <td align=center bgcolor=mistyrose>&Ouml;   </td><td>Opt-u, O</td>
      <td align=center bgcolor=mistyrose>&Oslash; </td><td>Opt-O   </td>
      <td align=center bgcolor=mistyrose>&OElig;  </td><td>Use "Oe"&nbsp;&dagger;</td>
  <tr><td align=center bgcolor=mistyrose>&ugrave; </td><td>Opt-~, u</td>
      <td align=center bgcolor=mistyrose>&uacute; </td><td>Opt-e, u</td>
      <td align=center bgcolor=mistyrose>&ucirc;  </td><td>Opt-i, u</td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&uuml;   </td><td>Opt-u, u</td>
  <tr><td align=center bgcolor=mistyrose>&Ugrave; </td><td>Opt-~, U</td>
      <td align=center bgcolor=mistyrose>&Uacute; </td><td>Opt-e, U</td>
      <td align=center bgcolor=mistyrose>&Ucirc;  </td><td>Opt-i, U</td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor=cornsilk>currency     </th>
      <th colspan=2 bgcolor=cornsilk>mathematics  </th>
  <tr><td>                                           </td><td>     </td>
      <td>                                           </td><td>     </td>
      <td>                                           </td><td>     </td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&yuml;   </td><td>Opt-u, y</td>
      <td align=center bgcolor=mistyrose>&cent;   </td><td>Opt-4   </td>
      <td align=center bgcolor=mistyrose>&plusmn; </td><td>Opt-+   </td>
  <tr><td>                                           </td><td>     </td>
      <td>                                           </td><td>     </td>
      <th colspan=2 bgcolor=cornsilk>literary     </th>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&Yuml;   </td><td>Opt-u, Y</td>
      <td align=center bgcolor=mistyrose>&pound;  </td><td>Opt-3   </td>
      <td align=center bgcolor=mistyrose>&times;  </td><td>Opt-V   </td>
  <tr><th colspan=2 bgcolor=cornsilk>&ccedil;edilla </th>
      <th colspan=2>                                </th>
      <td align=center bgcolor=mistyrose>&copy;   </td><td>Opt-g   </td>
      <th colspan=2 bgcolor=cornsilk>~ tilde        </th>
      <th colspan=2 bgcolor=cornsilk>punctuation  </th>
      <td align=center bgcolor=mistyrose>&yen;    </td><td>Opt-y   </td>
      <td align=center bgcolor=mistyrose>&divide; </td><td>Opt-/   </td>
  <tr><td align=center bgcolor=mistyrose>&ccedil; </td><td>Opt-c   </td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&reg;    </td><td>Opt-r   </td>
      <td align=center bgcolor=mistyrose>&ntilde; </td><td>Opt-n, n</td>
      <td align=center bgcolor=mistyrose>&iquest; </td><td>Opt-?   </td>
      <td align=center bgcolor=mistyrose>&euro;   </td><td>Opt-@   </td>
      <td align=center bgcolor=mistyrose>&not;    </td><td>Opt-l   </td>
  <tr><td align=center bgcolor=mistyrose>&Ccedil; </td><td>Opt-C   </td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&trade;  </td><td>Opt-2   </td>
      <td align=center bgcolor=mistyrose>&Ntilde; </td><td>Opt-n, N</td>
      <td align=center bgcolor=mistyrose>&iexcl;  </td><td>Opt-1   </td>
      <td>                                           </td><td>     </td>
      <td align=center bgcolor=mistyrose>&deg;    </td><td>Opt-*   </td>
  </tbody>
</table>
  &dagger;&nbsp;
    Note that the ligatures &aelig; &amp; &AElig; (and even more so the &oelig; and
    &OElig; ones) are becoming obsolete in English.  In most cases, the two individual
    letters have replaced them.  (Thus &AElig;sop's Fables becomes Aesop's Fables.)
    Check the <a href="#comments">Project Comments</a> to see if the Project Manager
    wants you to use the ligature, otherwise use the two individual letters.


<h3><a name="f_chars">Non-English Characters</a> </h3>
<p>For text printed in non-english characters; that is, characters other than the Latin
    A...Z characters, for example <b>Greek</b>, <b>Cyrillic</b> (used in Russian &amp;
    other Slavic languages), <b>Hebrew</b>, or <b>Arabic</b> characters, there are two
    ways to deal with these. </p>
<p> The simplest way is just to mark it, and leave it for the post-proofer to deal with.
    To do this, just surround the text with <tt>[Greek: **]</tt>, <tt>[Cyrillic:
    **]</tt>, <tt>[Hebrew: **]</tt>, or <tt>[Arabic: **]</tt> and leave it as scanned.
    Include the <tt>**</tt> so the post-proofer can deal with it later. </p>
<p> The second method is more work, but provides a more accurate text. This involves
    converting each character of the foreign text into the equivalent ASCII Latin
    letter(s). The result should still be enclosed in <tt>[Greek: ]</tt> etc. tags. For
    example, <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b>
    would become <tt>[Greek: Biblos]</tt>. ("Book"--so appropriate for DP!)</p>
<p> <b>Tip:</b> Greek occurs so frequently there is a clickable tool in the interface to
    help you do the transliteration. Press the "Greek" button near the bottom of the
    screen to make it pop-up, then you can click on the Greek characters to have their
    standard transliterations automatically appear in the text box, ready to be
    transferred (manually by you, using cut-n-paste) back to the page you are
    proofing when you have finished the transliteration. </p>
<ul compact>
  <li> Greek:
       <a href="http://www.gutenberg.net/vol/greek.html">Greek to ASCII Primer</a> (from
       Project Gutenberg) Or see the "Greek" pop-up tool in the proofing interface.
  </li>
  <li> Cyrillic:
       <a href="http://www.history.uiuc.edu/steinb/translit/translit.htm">Transliteration Table</a>
  </li>
  <li> Hebrew:
       Not recommended unless you are fluent in Hebrew.  There are significant
       difficulties in Hebrew-to-ASCII transliteration (right-to-left reading order,
       vowels typically omitted in writing, different pronunciation schemes by region,
       etc.) and neither <a href="..">Distributed
       Proofreaders</a> nor <a href="http://www.gutenberg.net/">Project Gutenberg</a>
       has yet chosen a standard method.  Join the Forum discussion on this if you are
       interested.
  </li>
  <li> Arabic:
       Not recommended unless you are fluent in Arabic.  Like Hebrew, there are
       significant difficulties in Arabic-to-ASCII transliteration and neither <a
       href="..">Distributed Proofreaders</a> nor <a
       href="http://www.gutenberg.net/">Project Gutenberg</a> has yet chosen a
       standard method.
  </li>
</ul>

<h3><a name="fract_s">Fractions</a> </h3>
<p> For <b>fractions</b>, convert them like this: <tt>2&frac12;</tt> becomes
    <tt>2-1/2</tt>.</p>

<h3><a name="page_ref">Page References</a> </h3>
<p> Some non-fiction works will reference themselves by giving a page number to go to,
    for example: (see p. 123). Please leave these references as they are within the text
    (even though we remove page numbers from the Table of Contents &amp; Index pages).
    Do not mark these with a <tt>*</tt> either, unless the Project Manager specifies
    this in the <a href="#comments">Project Comments</a>. </p>

<h3><a name="bk_index">Indexes</a> </h3>
<p> Remove all page numbers from index pages, along with any ... or *** used to align
    the page numbers.  You can leave any trailing commas or semicolons.
    (When the book is converted to electronic form, there will no longer be any 'pages'
    in the book, so page numbers are not useful.  Some Project Managers prefer to
    remove the index pages entirely because of this -- if so, they will tell you this in
    the <a href="#comments">Project Comments</a>.  Other PM's leave index pages, as they
    are an indication of what the author thought important enough to list.  Include
    index pages unless told otherwise.)</p>
<p>
    Indexes are often printed in 2 columns; this narrower space can cause entries to
    split onto the next line. Rejoin these back onto a single line.  See example below:
    </p>
<table border="1"  cellpadding="4" cellspacing="0">
  <col width="256*">
  <tbody>
    <tr>
      <th valign="top">Scanned Text</th>
      <th valign="top">Proofed Text</th>
    </tr>
    <tr>
      <td valign="top">Elizabeth I, her royal Majesty the                  <br>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.  <br>
                       &nbsp;&nbsp;birth of, 145.                          <br>
                       &nbsp;&nbsp;christening, 146-147.                   <br>
                       &nbsp;&nbsp;death and burial, 152.                  <br>
                       Ethelred II, the Unready                            </td>
      <td valign="top"><tt>Elizabeth I, her royal Majesty the Queen,       <br>
                       &nbsp;&nbsp;birth of,                               <br>
                       &nbsp;&nbsp;christening,                            <br>
                       &nbsp;&nbsp;death and burial,                       <br>
                       Ethelred II, the Unready                            </td>
    </tr>
  </tbody>
</table>
<p>
    For sub-topic listings in an index, start each one on a new line, indented 2 spaces.
    For example:                <br>
      <tt>
        &nbsp;&nbsp;Hooker, Jos., maj. gen. U. S. V.,                                 <br>
        &nbsp;&nbsp;&nbsp;&nbsp;assigned to command Porter's corps;                   <br>
        &nbsp;&nbsp;&nbsp;&nbsp;afterwards, McDowell's;                               <br>
        &nbsp;&nbsp;&nbsp;&nbsp;in pursuit of Lee;                                    <br>
        &nbsp;&nbsp;&nbsp;&nbsp;at South Mt.;                                         <br>
        &nbsp;&nbsp;&nbsp;&nbsp;unacceptable to Halleck, retires from active service. <br>
        &nbsp;&nbsp;Hopkins, Henry H.,                                                <br>
        &nbsp;&nbsp;&nbsp;&nbsp;notorious secessionist in Kanawha valley;             <br>
        &nbsp;&nbsp;&nbsp;&nbsp;controversy with Gen. Cox over escaped slave.         <br>
        &nbsp;&nbsp;Hosea, Lewis M.,                                                  <br>
        &nbsp;&nbsp;&nbsp;&nbsp;capt. on Gen. Wilson's staff,                         <br>
      </tt>
</p>
<p>
    Old books sometimes printed the first word of each letter in the index in all caps;
    change this to a normal word (first letter only capitalized). </p>

<h3><a name="trail_s">Trailing Space at End-of-line</a> </h3>
<p> Don't bother putting spaces at the ends of lines of text. Waste of your time for
    something that we can take care of automatically later. Similarly don't waste time
    taking out extra spaces at the ends of lines.</p>

<h3><a name="play_n">Play Actor Names/Stage Notes</a> </h3>
<p>Mark all actor names in italics if they are <a href="#italics">Italics</a> in the
    original text, mark them as bold if they are <a href="#bold">bold</a> in the
    original text.  And Stage Notes too: format them like they are in the original text.
    </p>

<h3><a name="anything">Anything else that needs special handling or that you're unsure of</a> </h3>
<p>If you run into something that isn't covered in these guidelines that you think needs
   special handling, or you run into something
   that you're not sure of, put an asterisk (<tt>*</tt>) next to it
   in your proofed text.  This signals the next proofer to stop and examine this text
   &amp; the matching image, and deal with it. (This does slow down the proofing
   of this text, so don't do this unneccessarily.)
   </p>
<p>You can also put a <tt>[**Note: I'm not sure...]</tt> there explaining the problem.
   Notes are quite helpful -- they save the next proofer time by explaining what the
   question is.  Put square brackets <tt>[</tt> and <tt>]</tt> around your note so that
   is clearly seperated from the Author's text. And mark it with 2 asterisks <tt>**</tt>
   for the next proofer.</p>
<br>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<br>

<h2><a name="sp_copy"></a>
    <a name="sp_ency"></a>
    <a name="sp_chem"></a>
    <a name="sp_math"></a>
    <a name="sp_poet"></a>
    Specific Guidelines for Special Books</h2>
<p> These special types of books have specific guidelines for them, that add to or
    modify the normal guidelines given in this document.  These books are often
    somewhat difficult, and are not recommended except to experienced proofreaders
    (over 100 pages) or people who are experts in the particular field.
 </p>
<p> Click on the link below when you need to see the guidelines for one of these
    special types of books. </p>
<ul compact>
  <li><b><a href="doc-copy.php">Copyright Renewal Books</a>          </b></li>
  <li><b><a href="doc-ency.php">Encyclopedias</a>                    </b></li>
  <li><b><a href="doc-poet.php">Poetry Books </a>                    </b></li>
  <li><b>                        Mathemetics Books  [to be completed.]</b></li>
  <li><b>                        Chemistry Books    [to be completed.]</b></li>
</ul>

<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<h2>Common Problems</h2>

<h3><a name="OCR_1lI">1-l-I OCR Problems </a> </h3>
<p> OCR scanners commonly have trouble distinguishing between the digit one (1), the
    lowercase letter l, and the uppercase letter I.  This is especially true for some of
    our older books where the pages may be in poor condition. Watch out for these.  Read
    the context of the sentence to determine which is the correct character, but be
    careful--often your mind will automatically 'correct' these when you are reading.
    </p>

<h3><a name="OCR_other">Other OCR Problems </a> </h3>
<p> OCR scanners often have trouble with dashes &amp; hyphens, so watch for these.  The
    rule is to use one hyphen (minus sign) for a <a
    href="#eol_hyphen">hyphenated-word</a>; use 2 hyphens for a longer <a
    href="#em-dashes">dash (Em-dash)</a>.  And no spaces around either one. OCR'd text
    often has only one hyphen for a dash that should have 2. </p>

<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  [...more to be added...] </p>

<h3><a name="hand_notes">Handwritten Notes in Book</a> </h3>
<p> Do not include handwritten notes in a book, unless it is impossible to read the text
    that the handwritten note is replacing.</p>

<h3><a name="bad_image">Bad Images</a> </h3>
<p> If an image is bad (not loading, chopped off, unable to be read), please put a post
    about this bad image in the project comments <a href="#forums">forum</a>.  Do not
    click on "Return Page to Round"; if you do, the page will be reissued to the next
    proofer.  Instead, click on the "Report Bad Page" button so this page is
    'quarantined'. </p>
<p> Note that some page images are quite big, and it's fairly common for your browser to
    have difficulty displaying them, especially if you have several windows open or are
    using an older computer.  Before reporting this as a bad page, try clicking on the
    "Image" line on the bottom of the page to bring up just the image in a new window.
    If that brings up a good image, then the problem is probably in your browser of
    system.  </p>
<p> It's fairly common for the image to be good, but the OCR scan is missing the first
    line or two of the text.  Please just type in the missing line(s).  If nearly all of
    the lines are missing in the scan, then either type in the whole page (if you are
    willing to do that), or just click on the "Return Page to Round" button and the page
    will be reissued to someone else.  If there are several pages like this, you might
    post a note in the project comments <a href="#forums">forum</a> to notify the
    Project Manager. </p>

<h3><a name="bad_text">Wrong Image for Text</a> </h3>
<p> If there is a wrong image for the text given, please put a post about this bad image
    in the project comments <a href="#forums">forum</a>.  Do not click on "Return Page
    to Round"; if you do, the page will be reissued to the next proofer.  Instead, click
    on the "Report Bad Page" button so this page is 'quarantined'.  </p>

<h3><a name="round1">Previous Proofreader Mistakes</a> </h3>
<p> If the previous proofreader made a lot of mistakes or missed a lot of things, you
    can send them a message by clicking on their name. That will allow you to send them
    a private forum message. <em>Please be nice!</em> These are people volunteering
    their help, usually trying their best.  The point of the message should be to inform
    them of the correct way to do proofing, rather than criticize them as incompetent,
    careless, etc. <br> (If the previous proofreader did an outstanding job, you can
    also send them a message about that.) </p>

<h3><a name="p_errors">Printer Errors/Misspellings</a> </h3>
<p> Due to printer errors, there can be some misspellings. If you believe these to be
    true printer errors, then you should fix them. Some may be due to differences in
    spelling of words from a long time ago. Do not try to correct those to modern
    English.</p>
<p> Whenever you make a change like this, include a note describing what you changed:
    <tt>[*Transcriber's note: typo fixed, changed from "3/8 = .378" to "3/8 = .375"]</tt>
    Include an <tt>*</tt> so the post proofer will notice it. </p>

<h3><a name="f_errors">Factual Errors in Texts</a> </h3>
<p> In general, don't correct factual errors in the author's book.  Many of the books we
    are proofing have statements of fact in them that we no longer accept as accurate.
    Leave them as the author wrote them. </p>
<p> A possible exception is in technical or scientific books, where a known formula or
    equation may be given incorrectly.  (Especially if it is shown correctly on other
    pages of the book.) Notify the Project Manager about these, either by sending them a
    message via the <a href="#forums">Forum</a>, or by inserting <tt>[sic*
    explain-your-concern]</tt> at that point in the text. </p>
<p> Whenever you make a change like this, include a note describing what you changed:
    <tt>[*Transcriber's note: typo fixed, changed from "3/8 = .378" to "3/8 = .375"]</tt>
    Include an <tt>*</tt> so the post proofer will notice it. </p>

<h3><a name="uncertain">Uncertain Items</a> </h3>
<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  [...to be completed...] </p>

<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
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