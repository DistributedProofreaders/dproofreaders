<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

output_header('Post-Processing Verification Guidelines', NO_STATSBAR);
?>

<!--Updated May 2008 by stacy-->

<div style="margin-left: 5%; margin-right: 5%;">

<h1>Post-Processing Verification Guidelines</h1>

<h2>Contents</h2>

<p>

<a href="#gen">General Procedures</a><br>
<a href="#rr">Requests to Revise and Resubmit</a><br>
<a href="#checks">Specific Checks to Make</a><br>
<a href="#rptcard">Submitting a PPV Summary</a>
</p>

<h3><a name="gen">General Procedures</a></h3>

<ol>
<li>
Make <a href="#checks">all the checks</a> you would make on a project 
you are post-processing.
</li>   

<li>
Keep track of all errors and inconsistencies remaining in the project as well as any corrections you make.
</li>
<li>
Send the PPer a feedback note describing the required changes,
suggested amendments, the corrections you made, and providing
helpful hints for future work.
</li>
<li>
When the agreed-upon changes have been made, upload the project to PG.
</li>
<li>
Submit a <a href="#rptcard">PPV Summary</a>.
</li>
</ol>

<h3><a name="rr">Requests to Revise and Resubmit</a></h3>

<p>
Give PPers the option to make corrections and amendments themselves or they may
ask you to make the changes for them. If the PPer requests that they be allowed
to make any necessary corrections and/or amendments, use the option on the
project page to return the project to the PPer's queue, and send them an email
or private message noting the items to be corrected, and/or making suggestions
that may improve the project (specifying that these are not errors if that is
the case). When complete, the PPer should reupload the project for PPV and
notify the PPVer that the revised files are now available.
</p>

<p>
Sometimes a PPVer may decide to ask the PPer to revise and resubmit.
This is an optional decision on the PPVer's part; it is not required.
If you feel that the PPer would benefit from the experience of revising
the project, ask the PPer to revise and resubmit, following these steps:
</p>

<ol>
<li>
Use the option on the project page to return the project to the PPer's queue.
</li>
<li>
Send a private message or email to the PPer letting them know to expect the project to show up in their queue again, 
explaining the corrections that need to be made before re-uploading for PPV, 
and stating that if they do not wish/do not have time to revise, 
they can return the project to the PP queue so that someone else can work on it. 
Try to be as tactful and helpful as possible. 
Point them to posted projects to show them what the final product is supposed to look like. 
Provide links to relevant parts of the PP guidelines which they overlooked.
</li>
</ol>

<h3><a name="checks">Specific Checks to Make</a></h3>

<p>

Check for the following types of errors, using tools you usually use for PPing:
</p>

<h4>Level 1 (minor errors)</h4>

<ul>
<li>Spellcheck errors</li>
<li>Scanno check errors</li>
<li>Comma/period error</li>
<li>Gutcheck-type errors such as, dashes, hyphens, punctuation, short/long lines,
    brackets, quotation marks, extraneous spaces, special characters (e.g. ~ * / _),
    non-Latin-1 and/or non-ASCII characters</li>
<li>Jeebies errors</li>
<li>Extraneous characters, such as ~ ` + = | \ &raquo; &laquo; < > / &pound;</li>
<li>Ellipses not formatted consistently, or not formatted according to guidelines
    and/or not formatted as specified in project comments</li>
<li>Ligature inconsistency (e.g., using [oe] and oe in same project, or ae and
    &aelig; in same project when page scan uses all &aelig;)</li>
<li>Italic markup inconsistently placed relative to punctuation</li>
<li>Inconsistently spaced initials</li>
<li>TOC not complete, or formatted inconsistently, and/or TOC headings not matching text headings</li>
<li>Chapter heading inconsistency (# blank lines before/after; 
    inconsistently centered or left-justified; 
    inconsistently capitalized; 
    period inconsistently missing at end of chapter heading or subheading)</li>
<li>Footnotes/footnote markers missing or out of order</li>
<li>Improper joining or separating of paragraphs at page break</li>
<li>Missing paragraph breaks within a page</li>
<li>Hyphenated/non-hyphenated variants not addressed</li>
<li>Hyphen/em dash confusion (i.e., hyphen used instead of em dash, or em dash
    used instead of hyphen)</li>
<li>Em dash incorrect length</li>
<li>HTML&mdash;title tag not filled in correctly</li>
<li>HTML&mdash;Tidy-reported issues have not been addressed as necessary**</li>
</ul>

<h4>Level 2 (major errors)</h4>

<ul>
<li>Text not rewrapped to required line length (usually 72 characters)</li>
<li>Rewrapping errors, e.g., rewrapped poetry</li>
<li>Poetry indentation not matching the original</li>
<li>Markup (e.g., italic markup, blockquote/poetry indentation) removed</li>
<li>Missing page(s)</li>
<li>Printers' errors not addressed***</li>
<li>Improper placement of pagenum markup, if a TOC or index in the project
    uses page numbers (otherwise, placement of pagenum markup is not important)</li>
<li>HTML&mdash;invalid HTML (obviously not caused by a last-minute change to code)</li>
<li>HTML&mdash;invalid CSS</li>
<li>HTML&mdash;non-working links</li>
<li>HTML&mdash;filenames and folder names not in lowercase&mdash;(for a first project treat this as a Level 1 error)</li>
<li>HTML&mdash;images not in "images" directory</li>
<li>HTML&mdash;images not optimized (i.e., inappropriate file size and/or
    dimensions<?php if($site_abbreviation = "DP" || $site_abbreviation = "DPT") { ?>&mdash;see
    <a href="http://www.pgdp.net/wiki/Guide_to_Image_Processing#Scaling_the_Image_Display_Dimensions">display dimensions</a>
    and
    <a href="http://www.pgdp.net/wiki/Guide_to_Image_Processing#Exporting_to_the_Final_Output_Format"> output format</a> in the wiki)
    <?php } ?>
    </li>
</ul>

<h4>Other errors (not counted toward rating)</h4>

<ul>
<li>HTML&mdash;Not removing unused css</li>
<li>A Thumbs.db file in the images folder</li>
<li>HTML does not convert well to e-reader versions
    (e.g. headings do not reflect structure of book,
    no cover image supplied)
</li>
<!--<li>ZIP file&mdash;A __MACOSX folder in the upload file</li>-->
</ul>

<p>
** When using Tidy, use the report mode, but do not allow Tidy to alter your code; 
instead, look at the report and make changes to your code as necessary.
</p>

<p>
*** Printers' errors should be addressed in one, or a combination, of the following ways:
</p>

<ol>
<li>Correct silently</li>
<li>Correct and note corrections in Transcriber's Note</li>
<li>Leave uncorrected, and note errors in Transcriber's Note</li>
</ol>

<p>
Anything that could make a reader think an error has been made in the transcription
should be mentioned in the Transcriber's Note.
</p>
<p>
In the html, changes to the text may be documented by way of HTML comments or similar,
and a note to that effect added to the transcriber's note.
</p>

<p>
"Not addressing printers' errors" means that all, or a large percentage, of printers'
errors have been left uncorrected and not noted. If just one or two have been missed,
and the rest addressed, then those missed would instead be counted as the relevant
type of error (spellcheck, gutcheck, etc.)
</p>

<h4>E-reader versions</h4>

<p>
With the clear expectation from PG and the DPF Board
that our projects should look good in e-reader versions,
the PPV community have agreed that PPVers should support PPers
(and check their work)
in producing HTML versions that convert successfully
to epub and Kindle formats.
</p>

<p>
The <a href="http://www.pgdp.net/wiki/Easy_Epub">Easy Epub</a> wiki pages
are intended to help with that process of checking e-reader versions
and making some simple changes to improve them if necessary.
In order to support PPers, particularly as they approach DU,
it would be helpful if PPVers could indicate in the comments
whether a PPer is using headings correctly,
and is confident with coverpages
(i.e able to include a supplied coverpage,
to create their own or to ask someone else to create it).
</p>

<p>
The wiki pages include <a href="http://www.pgdp.net/wiki/Easy_Epub/Viewing">Viewing</a>
- How to use epubmaker to view your project in e-reader format,
even if you don't have an e-reader.
</p>

<p>
Then there are just four things to check:
</p>
<ul>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Headings">Headings</a>
- How to use headings correctly.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Cover">Cover Pages</a>
- How to include a coverpage, whether one was supplied with the project or not.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/HRs">Horizontal Rules</a>
- Horizontal rules do not center automatically in epub versions,
but a very easy edit fixes this.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Dropcaps">Dropcaps</a>
- A couple of ideas to help with dropcaps if you have them,
at least until the Best Practices method is accepted by PG.
</li>
</ul>

<p>
These few simple changes will make our books look good on e-readers,
without having to understand
all the principles described in the Best Practices document.
</p>

<h3><a name="rptcard">Submitting a PPV Summary</a></h3>

<p>
Using the link on the project page, submit a PPV Summary for the project, 
using the following criteria for determining project difficulty and rating of PPer's work:
</p>

<h4>Determining whether a project is Easy, Average, or Difficult</h4>

<p>

Easy Project:
</p>

<ul>
<li>Straight fiction with little or no poetry</li>
<li>Straight poetry</li>
<li>Other straight text, e.g., essay</li>
<li>Can include some illustration markup</li>
<li>Can include a few simple footnotes, blockquotes, etc.</li>
<li>No index</li>
<li>100 or fewer gutcheck flags</li>
<li>100 or fewer spellcheck flags</li>
</ul>

<p>
Average Project:
</p>

<p>
Can include up to 3 of the following kinds of content:
</p>

<ul>
<li>poetry other than straight poetry covered under "Easy Project"</li>
<li>blockquotes</li>
<li>footnotes</li>
<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
<li>significant number of illustrations</li>
</ul>

<p>
AND:
</p>

<ul>
<li>100-500 gutcheck flags, AND/OR</li>
<li>100-500 distinct spellcheck flags&mdash;i.e. not including frequently repeated words</li>
</ul>

<p>
Difficult Project:
</p>

<p>
Includes significant amounts of more than 3 of the following kinds of content:
</p>

<ul>
<li>poetry other than straight poetry covered under "Easy Project"</li>
<li>blockquotes</li>
<li>footnotes</li>
<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
<li>illustrations requiring advanced preparation and/or difficult placement</li>
</ul>

<p>
AND:
</p>

<ul>

<li>500+ gutcheck flags, AND/OR</li>
<li>500+ distinct spellcheck flags&mdash;i.e. not including frequently repeated words</li>
</ul>

<p>
and/or includes any of the following kinds of content:
</p>

<ul>
<li>multiple languages*</li>
<li>Englifh</li>
<li>musical notation/lilypond/midi files</li>
<li>extensive mathematical or chemical notation</li>
<li>LaTeX</li>
</ul>

<p>
* How to define multiple languages:
</p>

<ul>
<li>
If the book is English on one page and Latin on the facing page, it counts
as multiple languages.
</li>
<li>
If the author is travelling and repeatedly reports conversations in the
foreign language of the country, it counts as multiple languages.
</li>
<li>
If extensive (paragraph or more) quotations in a language other than the
base language are present, it counts as multiple languages.
</li>
<li>
If the Frenchman in the novel says "Zut!" a lot, it does NOT count as
multiple languages.</li>
</ul>



<h4>Determining Allowable Errors for Various Ratings</h4>

<p>
File size referred to below is based on the plain text version.
</p>

<p>
Errors such as failure to grasp the italics guidelines is counted as one
error, not one error each time punctuation is wrongly placed. Errors such
as he/be errors are each counted as individual errors (i.e. three he for
be counts as three errors).
</p>


<p>
Excellent
</p>

<ul>
<li>No Level 2 errors </li>
<li>If project is:

<ul>
    <li>Easy
	<ul>
	<li>Maximum of one Level 1 error per 300kb, (or 1 error if file size &lt; 300kb)</li>
	</ul>
    </li>

    <li>Average
	<ul>
	<li>Maximum of one Level 1 error per 200kb, (or 1 error if file size &lt; 200kb)</li>
	</ul>
    </li>

    <li>Difficult
	<ul>
	<li>Maximum of one Level 1 error per 100kb, (or 1 error if file size &lt; 100kb)</li>
	</ul>
    </li>
    </ul>
</li>
</ul>

<p>
Very Good
</p>

<ul>
<li>No Level 2 errors</li>
<li>If project is:

<ul>
    <li>Easy
	<ul>
	<li>Maximum of one Level 1 error per 165kb, (or 1 error if file size &lt; 165kb)</li>
	</ul>
    </li>

    <li>Average
	<ul>
	<li>Maximum of one Level 1 error per 100kb, (or 1 error if file size &lt; 100kb)</li>
	</ul>
    </li>

    <li>Difficult
        <ul>
	<li>Maximum of one Level 1 error per 50kb, (or 1 error if file size &lt; 50kb)</li>
	</ul>
    </li>
</ul>
</li>
</ul>

<p>
Good
</p>

<ul>
<li>May contain Level 2 errors </li>
<li>If project is:

<ul>
    <li>Easy
	<ul>
	<li>More than one Level 1 error per 165kb, (or more than 1 error if file size &lt; 165kb)</li>
	</ul>
    </li>

    <li>Average
	<ul>
	<li>More than one Level 1 error per 100kb, (or more than 1 error if file size &lt; 100kb)</li>
	</ul>
    </li>

    <li>Difficult
	<ul>
	<li>More than one Level 1 error per 50kb, (or more than 1 error if file size &lt; 50kb)</li>
	</ul>
    </li>
</ul>
</li>
</ul>

<p>
Fair
</p>

<p>
Fair rating should be assigned if it is felt that the project contains too many
errors to be assigned a rating of Good.
</p>

<p>
Poor
</p>

<p>
Poor rating should be assigned at the PPVer's discretion if the project does not
fit into one of the above categories.
</p>

</div>
    
<?php
// vim: sw=4 ts=4 expandtab
