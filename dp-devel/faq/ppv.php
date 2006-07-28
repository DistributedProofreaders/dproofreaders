<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Post-Processing Verification Guidelines','header');
?>

<div style="margin-left: 5%; margin-right: 5%;">

<h1>Post-Processing Verification Guidelines</h1>

<h2>Contents</h2>

<p>

<a href="#gen">General Procedures</a><br>
<a href="#rr">Requests to Revise and Resubmit</a><br>
<a href="#checks">Specific Checks to Make</a><br>
<a href="#rptcard">Submitting a PPV Report Card</a>
</p>

<h3><a name="gen">General Procedures</a></h3>

<ol>
<li>
Make <a href="#checks">all the checks</a> you would make on a project 
you are post-processing.
</li>   

<li>
Keep track of all the corrections you make.
</li>
<li>
Send the PPer a feedback note describing the corrections you made, 
and providing helpful hints for future work.
</li>
<li>
Upload the project to PG.
</li>
<li>
Submit a <a href="#rptcard">PPV report card</a>.
</li>
</ol>

<h3><a name="rr">Requests to Revise and Resubmit</a></h3>

<p>
Sometimes the PPer requests that they be allowed to make any necessary 
corrections and reupload the project for PPV. 
In this case, use the option on the project page to return the project to the PPer's queue, 
and send them an email or private message noting the items to be corrected.  

</p>

<p>
Sometimes a PPVer may decide to ask the PPer to revise and resubmit. 
This is an optional decision on the PPVer's part; it is not required. 
If you feel it would be too time-consuming for you to make all the corrections necessary, 
or that the PPer would benefit from the experience of revising the project, 
ask the PPer to revise and resubmit, following these steps:
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
<li>Gutcheck errors</li>
<li>Jeebies errors</li>

<li>Extraneous characters, such as ~ ` + = | \ » « < > / £</li>
<li>Ellipses not formatted according to guidelines</li>
<li>Ligature inconsistency (e.g., using [oe] and oe in same project, or ae and æ in same project when page scan uses all æ)</li>
<li>Italic markup incorrectly placed relative to punctuation</li>
<li>Inconsistently spaced initials</li>
<li>TOC not complete, or formatted inconsistently</li>
<li>Chapter heading inconsistency (# blank lines before/after; 
inconsistently centered or left-justified; 
inconsistently capitalized; 
period missing at end of chapter heading or subheading)</li>
<li>Footnotes/footnote markers missing or out of order</li>

<li>Improper joining or separating of paragraphs at page break</li>
<li>Hyphenated/non-hyphenated variants not addressed</li>
<li>Hyphen/emdash confusion (i.e., hyphen used instead of emdash, or emdash
used instead of hyphen)</li>
<li>Emdash incorrect length</li>
<li>HTML - title tag not filled in correctly</li>
<li>HTML - Tidy-reported issues have not been addressed as necessary**</li>
</ul>

<h4>Level 2 (major errors)</h4>

<ul>
<li>Text not rewrapped to required line length</li>
<li>Rewrapping errors, e.g., rewrapped poetry</li>
<li>Poetry indentation not preserved</li>
<li>Markup (e.g., italic markup, blockquote/poetry indentation) removed</li>
<li>Missing page(s)</li>
<li>Printers' errors not addressed***</li>
<li>
Improper placement of pagenum markup, if a TOC or index in the project uses page numbers (otherwise, placement of pagenum markup is not important)
</li>
<li>HTML - invalid HTML (obviously not caused by a last-minute change to code)</li>

<li>HTML - invalid CSS</li>
<li>HTML - non-working links</li>
<li>HTML - filenames not in lowercase</li>
<li>HTML - images not in "images" directory</li>
<li>HTML - images not optimized</li>
</ul>

<h4>Other errors (not counted toward rating)</h4>

<ul>
<li>HTML - Not removing unused css</li>

</ul>

<p>
** When using Tidy, use the report mode, but do not allow Tidy to alter your code; 
instead, look at the report and make changes to your code as necessary.
</p>

<p>
*** Printers' errors should be addressed in one of the following ways:
</p>

<ol>
<li>Correct silently</li>
<li>Correct and note corrections in Transcriber's Note</li>
<li>Leave uncorrected, and note errors in Transcriber's Note</li>

</ol>

<p>
"Not addressing printers' errors" means that all, 
or a large percentage, 
of printers' errors have been left uncorrected and not noted. 
If just one or two have been missed, and the rest addressed, 
then those missed would instead be counted as the relevant type of error (spellcheck, gutcheck, etc.)
</p>

<h3><a name="rptcard">Submitting a Report Card</a></h3>

<p>
Using the link on the project page, submit a report card for the project, 
using the following criteria for determing project difficulty and rating of PPer's work:
</p>

<h4>Determining whether a project is Easy, Average, or Difficult</h4>

<p>

Easy Project:
</p>

<ul>
<li>Straight fiction with little or no poetry</li>
<li>Straight poetry</li>
<li>Other straight text, e.g., essay</li>
<li>Can include illustration markup</li>
<li>No footnotes, no index, no blockquotes, etc.</li>
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
<li>poetry</li>
<li>blockquotes</li>
<li>footnotes</li>

<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
</ul>

<p>
and/or:
</p>

<ul>
<li>100-500 gutcheck flags</li>

<li>100-500 spellcheck flags</li>
</ul>

<p>
Difficult Project:
</p>

<p>
Includes more than 3 of the following kinds of content:
</p>

<ul>
<li>poetry</li>
<li>blockquotes</li>

<li>footnotes</li>
<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
</ul>

<p>
and/or:
</p>

<ul>

<li>500+ gutcheck flags</li>
<li>500+ spellcheck flags</li>
</ul>

<p>
and/or includes any of the following kinds of content:
</p>

<ul>
<li>multiple languages*</li>
<li>Englifh</li>
<li>musical notation/lilypond/midi files</li>

<li>extensive mathematical or chemical notation</li>
<li>LATex</li>
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

<li>If the Frenchman in the novel says "Zut!" a lot, it does NOT count as
multiple languages.</li>
</ul>



<h4>Determining Allowable Errors for Various Ratings</h4>

<p>
Excellent
</p>

<ul>
<li>No Level 2 errors </li>
<li>If project is:


<ul>
<li>Easy
	<ul>
	<li>Up to 0.3 Level 1 errors/100k (or 1 error if file size < 300k)</li>
		</ul>
	</li>

<li>Average
		<ul>
	<li>Up to 0.5 Level 1 errors/100k (or 1 error if file size < 200k)</li>

		</ul>
	</li>

<li>Difficult
		<ul>
	<li>Up to 1 Level 1 error/100k (or 1 error if file size < 100k)</li>
		</ul>
	</li>
	</ul>

</li>
</ul>

<p>
Good
</p>

<ul>
<li>No Level 2 errors</li>
<li>If project is:
<ul>
<li>Easy
<ul>
	<li>Up to 0.6 Level 1 errors/100k (or 1 error if file size < 165k)</li>

</ul>
</li>

<li>Average
<ul>
	<li>Up to 1 Level 1 error/100k (or 1 error if file size < 100k)</li>
</ul>
</li>

<li>Difficult
<ul>
	<li>Up to 2 Level 1 errors/100k (or 1 error if file size < 50k)</li>

</ul>
</li>
</ul>
</li>
	</ul>

<p>
Fair
</p>

<ul>
<li>May contain Level 2 errors </li>
<li>If project is:
<ul>
<li>Easy

<ul>
	<li>More than 0.6 Level 1 errors/100k (or more than 1 error if file size < 165k)</li>
</ul>
</li>

<li>Average
<ul>
	<li>More than 1 Level 1 errors/100k (or more than 1 error if file size < 100k)</li>
</ul>
</li>

<li>Difficult
<ul>
	<li>More than 2 Level 1 errors/100k (or more than 1 error if file size < 50k)</li>
</ul>
</li>
</ul>
</li>
	</ul>


<p>
Poor
</p>

<p>
Poor rating should be assigned at the PPVer's discretion, 
if it is felt that the project contains too many errors to be assigned a rating of Fair.
</p>

</div>

    
<?
theme('','footer');
?>
